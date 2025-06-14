<?php

declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;
use Cake\I18n\FrozenDate;
use App\Utility\FileHelper;
use Cake\ORM\TableRegistry;
use Cake\Http\Cookie\Cookie;
use App\Utility\SettingHelper;
use App\Service\ServiceProvider;
use App\Controller\AppController;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Users Controller
 *
 */
class UsersController extends BaseController
{
    private $fileService;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'forgotPassword', 'resetPassword']);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->fileService = ServiceProvider::getFileService();
    }

    public function upload() {}

    public function uploadTempFiles()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('file');

            if ($file) {
                $filename = FileHelper::uploadFile($file, 'temp_uploads');
                return AjaxResponseHelper::createResponse(true, 'File Temparary uploaded successfully.', ['filename' => $filename]);
            }
        }
        return AjaxResponseHelper::createResponse(false, 'Invalid file upload.', [], 400);
    }

    public function uploadMultipleTempFiles()
    {
        if ($this->request->is('post')) {
            $files = $this->request->getData('file'); // Change 'file' to 'files'

            if ($files) {
                $uploadedFiles = [];

                foreach ($files as $file) {
                    if ($file) {
                        $filename = FileHelper::uploadFile($file, 'temp_uploads');
                        $uploadedFiles[] = $filename;
                    }
                }
                return AjaxResponseHelper::createResponse(true, 'Files temporarily uploaded successfully.', ['filename' => $uploadedFiles]);
            }
        }
        return AjaxResponseHelper::createResponse(false, 'Invalid file upload.', [], 400);
    }

    public function deleteTempFiles()
    {
        $this->request->allowMethod(['post', 'delete']);

        $filename = $this->request->getData('id');

        if ($filename) {
            try {
                FileHelper::deleteFile($filename, 'temp_uploads');
                return AjaxResponseHelper::createResponse(true, 'File deleted successfully');
            } catch (NotFoundException $e) {
                return AjaxResponseHelper::createResponse(false, 'File not found.');
            }
        }
        return AjaxResponseHelper::createResponse(false, 'File not found.');
    }

    public function saveUploadedFiles()
    {
        if ($this->request->is('ajax')) {

            $uploadedFiles = explode(',', $this->request->getData('uploaded_files'));
            $uploadedDescriptions = json_decode($this->request->getData('uploaded_descriptions'), true);
            $getuserId = $this->request->getData('user_id');
            $userId = isset($getuserId) ? $getuserId : $this->request->getAttribute('identity')->get('id');

            $savedFiles = [];
            foreach ($uploadedFiles as $filename) {
                if ($filename) {
                    $tempFilePath = WWW_ROOT . 'temp_uploads' . DS . $filename;
                    $finalFilePath = WWW_ROOT . 'uploads' . DS . $filename;

                    $description = $uploadedDescriptions[$filename] ?? '';

                    if (file_exists($tempFilePath)) {
                        rename($tempFilePath, $finalFilePath);

                        $imagesTable = TableRegistry::getTableLocator()->get('GeneralFiles');
                        $image = $imagesTable->newEntity([
                            'file_name' => $filename,
                            'file_id' => '',
                            'description' => $description,
                            'file_size' => filesize($finalFilePath),
                            'user_id' => $userId,
                            'uploaded_by' => $this->request->getAttribute('identity')->get('id'),
                            'deleted' => 0,
                        ]);

                        $savedImage = $imagesTable->save($image);
                        if ($savedImage) {
                            $savedFiles[] = [
                                'id' => $savedImage->id,
                                'uploaded_by' => $this->request->getAttribute('identity')->get('first_name'),
                                'deleteUrl' => Router::url(['controller' => 'Users', 'action' => 'deleteFile', $savedImage->id, '_full' => true,]),
                                'url' => Router::url('/uploads/' . $filename, true),
                                'file_name' => preg_replace('/^[^-]+-/', '', $filename),
                                'description' => $description,
                                'file_size' => filesize($finalFilePath)
                            ];
                        } else {
                            return AjaxResponseHelper::createResponse(false, 'Failed to save the file to the database.', []);
                        }
                    }
                }
            }
            FileHelper::deleteAllTempFiles('temp_uploads');
            return AjaxResponseHelper::createResponse(true, 'Files uploaded successfully.', ['files' => $savedFiles]);
        }

        return AjaxResponseHelper::createResponse(false, 'Invalid request', []);
    }

    public function deleteFile()
    {
        $this->request->allowMethod(['post', 'delete']);

        $fileId = $this->request->getData('recordId');

        $generalFilesTable = TableRegistry::getTableLocator()->get('GeneralFiles');
        $file = $generalFilesTable->find()
            ->where(['id' => $fileId])
            ->first();

        if ($file) {
            try {
                // Delete the file from the folder if it exists
                FileHelper::deleteFile($file->file_name, 'uploads');

                // Delete the record from the database
                if ($generalFilesTable->delete($file)) {
                    return AjaxResponseHelper::createResponse(true, 'File deleted successfully');
                } else {
                    return AjaxResponseHelper::createResponse(false, 'Unable to delete file record from database.');
                }
            } catch (\Exception $e) {
                return AjaxResponseHelper::createResponse(false, 'Error occurred: ' . $e->getMessage());
            }
        }
        return AjaxResponseHelper::createResponse(false, 'File not found.');
    }

    public function uploadProfileImage()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        $image = $this->request->getData('profile_image');
        $id = $this->request->getData('id');

        if (!empty($image->getClientFilename())) {
            list($width, $height) = getimagesize($image->getStream()->getMetadata('uri'));

            if ($width <= 200 && $height <= 200) {

                $userId = $this->Authentication->getIdentity()->get('id');
                $user = $this->Users->get($id);

                // Check if the user already has a profile image
                if (!empty($user->profile_image)) {
                    FileHelper::deleteFile($user->profile_image, 'profile_img_uploads');
                }

                $filename = FileHelper::uploadFile($image, 'profile_img_uploads');
                $user->profile_image = $filename;

                $saveuser = $this->Users->save($user);

                if ($saveuser) {
                    if ($id == $userId) {
                        ControllerHelper::updateAllIdentityValues($this->request, $saveuser);
                    }
                    return AjaxResponseHelper::createResponse(true, 'Profile image uploaded successfully.', ['filename' => $filename]);
                } else {
                    return AjaxResponseHelper::createResponse(false, 'Unable to save profile image. Please try again.');
                }
            } else {
                return AjaxResponseHelper::createResponse(false, 'Image dimensions should not exceed 200x200 pixels.');
            }
        } else {
            return AjaxResponseHelper::createResponse(false, 'No image selected.');
        }
    }

    public function saveCropProfileImage()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {

            $id = $this->request->getData('id');
            $imageData = $this->request->getData('image');
            $image_array_1 = explode(";", $imageData);
            $image_array_2 = explode(",", $image_array_1[1]);

            $data = base64_decode($image_array_2[1]);

            $imageName = uniqid() . '.png';
            $imagePath = WWW_ROOT . 'profile_img_uploads' . DS . $imageName;

            file_put_contents($imagePath, $data);

            $userId = $this->Authentication->getIdentity()->get('id');
            $user = $this->Users->get($id);

            if (!empty($user->profile_image)) {
                FileHelper::deleteFile($user->profile_image, 'profile_img_uploads');
            }

            $user->profile_image = $imageName;
            $imageUrl = Router::url('/profile_img_uploads/' . $imageName, true);

            $saveuser = $this->Users->save($user);
            if ($saveuser) {
                if ($id == $userId) {
                    ControllerHelper::updateAllIdentityValues($this->request, $saveuser);
                }
                return AjaxResponseHelper::createResponse(true, 'Image uploaded successfully', ['imageUrl' => $imageUrl]);
            } else {
                return AjaxResponseHelper::createResponse(false, 'Unable to save profile image. Please try again.');
            }
        }
        return AjaxResponseHelper::createResponse(false, 'Invalid request method.', [], 405);
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('auth');
        $this->set('title', 'Login');

        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $userIdentity = $this->Authentication->getIdentity();

            $user = $this->Users->get($userIdentity->getIdentifier(), [
                'contain' => ['Roles']
            ]);

            $user->last_login_at = date('Y-m-d H:i:s');
            $this->Users->save($user);

            // Save permissions in session
            $session = $this->request->getSession();
            ControllerHelper::setLoginUserPermissionsInSession($user->id, $session);

            $userIdentity = $user->toArray();
            $userIdentity['role'] = $user->role->name;

            $rememberMe = $this->request->getData('remember_me');
            if ($rememberMe) {
                // Set cookie to remember user
                $cookie = new Cookie(
                    'remember_me_token',   // Cookie name
                    $user->id,             // Cookie value
                    new DateTime('+1 month'), // Expiration date
                    '/qcolab/login',          // Path
                    '',                    // Domain
                    false,                 // Secure (true for HTTPS)
                    true,                  // HttpOnly
                    'Lax',                 // SameSite (use 'Lax', 'Strict', or 'None')
                    ''                     // Priority
                );
                $this->response = $this->response->withCookie($cookie);
            }

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $email = $data['email'];

            $user = $this->Users->findByEmail($email)->first();

            if (!$user) {
                ControllerHelper::flashMessage($this, 'error', 'alerts.email_not_exist');
            } else {
                if (!$result->isValid()) {
                    if ($user->status == '0') {
                        ControllerHelper::flashMessage($this, 'error', 'alerts.account_not_active');
                    } else {
                        ControllerHelper::flashMessage($this, 'error', 'alerts.incorrect_password');
                    }
                }
            }
        }
    }

    public function forgotPassword()
    {
        $this->viewBuilder()->setLayout('auth');

        $this->set('title', 'Forgot Password');

        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $user = $this->Users->findByEmail($email)->first();

            if ($user) {
                $resetToken = md5((string)rand());
                $user->token = $resetToken;
                $user->token_requested_at = date('Y-m-d H:i:s');
                $this->Users->save($user);

                $this->sendResetEmail($user);

                ControllerHelper::flashMessage($this, 'success', 'alerts.password_reset_link');
            } else {
                ControllerHelper::flashMessage($this, 'error', 'alerts.invalid_email');
            }
        }
    }

    private function sendResetEmail($user)
    {
        $get_email_template = $this->getEmailTemplate('forgot-password');

        if (!$get_email_template) {
            return false;
        }

        $resetLink = Router::url([
            'controller' => 'Users',
            'action' => 'resetPassword',
            $user->token,
            '_full' => true,
        ]);

        $company_name = SettingHelper::getSettingValue('company_name');

        $placeholders = [
            '{firstname}' => $user->first_name,
            '{lastname}' => $user->last_name,
            '{email}' => $user->email,
            '{reset_password_url}' => $resetLink,
            '{email_signature}' => $company_name,
            '{company_name}' => $company_name
        ];

        $template_body = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->message);
        $template_subject = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->subject);

        $this->sendEmail($user->email, $template_subject, $template_body);
    }

    public function resetPassword($token = null)
    {
        $this->viewBuilder()->setLayout('auth');

        $this->set('title', 'Reset Password');

        if ($token) {
            $user = $this->Users->findByToken($token)->first();

            if ($user) {
                $tokenRequestedAt = $user->token_requested_at->getTimestamp();
                $timestamp_now_minus_1_hour = time() - (60 * 60);

                if ($timestamp_now_minus_1_hour > $tokenRequestedAt) {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.password_reset_link_expired');
                    return $this->redirect(['action' => 'forgotPassword']);
                }

                if ($this->request->is(['post', 'put'])) {
                    $password = $this->request->getData('password');
                    $confirm_password = $this->request->getData('password_confirm');

                    if (!$this->validatePassword($password)) {
                        ControllerHelper::flashMessage($this, 'error', 'alerts.password_must_be_valid_length');
                        return;
                    }

                    if ($password === $confirm_password) {
                        $user->password = $password;
                        $user->password_updated_at = date('Y-m-d H:i:s');
                        $user->token = null;
                        $user->token_requested_at = null;

                        if ($this->Users->save($user)) {
                            ControllerHelper::flashMessage($this, 'success', 'alerts.password_updated');
                            return $this->redirect(['action' => 'login']);
                        }
                    } else {
                        ControllerHelper::flashMessage($this, 'error', 'alerts.password_not_matched');
                    }
                }
            } else {
                ControllerHelper::flashMessage($this, 'error', 'alerts.invalid_request');
                return $this->redirect(['action' => 'forgotPassword']);
            }
        } else {
            ControllerHelper::flashMessage($this, 'error', 'alerts.token_missing');
            return $this->redirect(['action' => 'forgotPassword']);
        }
    }

    private function validatePassword($password)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/', $password);
    }

    public function logout()
    {
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    public function index()
    {
        $this->set('title', 'Staff');

        $query = $this->Users->find('all')->contain(['Roles']);

        $roles = $this->Users->Roles->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $users = $this->paginate($query);

        $this->set(compact('users', 'roles'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title', 'Add Staff');

        $roles = $this->Users->Roles->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $data['password'] = $data['password'] ?? 'Password123#';

            $getPassword = $data['password'];

            $roleName = ControllerHelper::getRoleName($data['role_id']);
            if ($roleName == 'Admin') {
                $data['is_admin'] = 1;
            }

            $data['email_verified_at'] = date('Y-m-d H:i:s');

            $user = $this->Users->patchEntity($user, $data);

            if ($user->hasErrors()) {
                $errorMessages = [];
                foreach ($user->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->Users->save($user)) {

                    $this->sendWelcomeEmail($user, $getPassword);

                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_created');

                    return $this->redirect(['controller' => 'Users', 'action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_created_error');
                }
            }
        }

        $this->set(compact('user', 'roles'));
    }

    private function sendWelcomeEmail($user, $password)
    {
        $getEmailTemplate = $this->getEmailTemplate('welcome-email');

        if (!$getEmailTemplate) {
            return false;
        }

        $company_name = SettingHelper::getSettingValue('company_name');

        $placeholders = [
            '{firstname}' => $user->first_name,
            '{lastname}' => $user->last_name,
            '{email}' => $user->email,
            '{login_url}' => 'login',
            '{username}' => $user->email,
            '{password}' => $password,
            '{company_name}' => $company_name
        ];

        $template_body = str_replace(array_keys($placeholders), array_values($placeholders), $getEmailTemplate->message);
        $template_subject = str_replace(array_keys($placeholders), array_values($placeholders), $getEmailTemplate->subject);

        $this->sendEmail($user->email, $template_subject, $template_body);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Staff');

        // Fetch the user entity by ID
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        if ($user->dob instanceof FrozenDate) {
            $user->dob = $user->dob->format('m/d/Y');
        }

        if ($user->date_of_joining instanceof FrozenDate) {
            $user->date_of_joining = $user->date_of_joining->format('m/d/Y');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            if (!empty($data['password'])) {
                if ($data['password'] !== $data['confirm_password']) {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.password_not_matched');
                    return;
                }

                // Validate the password strength
                if (!$this->validatePassword($data['password'])) {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.password_must_be_valid_length');
                    return;
                }

                $data['password_updated_at'] = date('Y-m-d H:i:s');
            } else {
                // If password is empty, remove it from data to avoid validation
                unset($data['password']);
                unset($data['confirm_password']);
            }

            unset($data['profile_image']);

            $user = $this->Users->patchEntity($user, $data);

            if (!empty($this->request->getData('dob'))) {
                $submittedDob = $this->request->getData('dob');

                $user->dob = FrozenDate::parseDate($submittedDob, 'MM/dd/yyyy');
            }

            if (!empty($this->request->getData('date_of_joining'))) {
                $submittedDateOfJoining = $this->request->getData('date_of_joining');

                $user->date_of_joining = FrozenDate::parseDate($submittedDateOfJoining, 'MM/dd/yyyy');
            }

            if ($user->hasErrors()) {
                $errorMessages = [];
                foreach ($user->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->Users->save($user)) {
                    $loginUserId = $this->Authentication->getIdentity()->get('id');
                    if ($loginUserId == $id) {
                        ControllerHelper::updateAllIdentityValues($this->request, $user);
                    }
                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');

                    return $this->redirect(['action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
                }
            }
        }

        // Handle the active tab
        $activeTab = $this->request->getQuery('tab') ?: 'tab1';
        $this->set(compact('user', 'activeTab'));

        $generalFilesTable = TableRegistry::getTableLocator()->get('GeneralFiles');

        $files = $generalFilesTable->find()
            ->where(['GeneralFiles.user_id' => $id])
            ->contain([
                'Users' => function ($q) {
                    return $q->select(['id', 'first_name', 'email']);
                },
                'UploadedByUsers' => function ($q) {
                    return $q->select(['id', 'first_name', 'email']);
                }
            ])
            ->all();

        $roles = $this->Users->Roles->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $this->set(compact('user', 'roles', 'files'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        // If no IDs are provided, get the single ID from the request data
        $userId = $this->request->getData('recordId') ?? ($id);

        if (!$userId) {
            return AjaxResponseHelper::createResponse(
                false,
                'No record(s) selected for deletion.',
                []
            );
        }

        $allDeleted = true;

        if ($userId) {
            // Single deletion
            try {
                $user = $this->Users->get($userId);
                if (!$this->Users->delete($user)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        }

        $response = $allDeleted
            ? AjaxResponseHelper::createResponse(
                true,
                'The record(s) have been deleted.',
                []
            )
            : AjaxResponseHelper::createResponse(
                false,
                'Some record(s) could not be deleted. Please, try again.',
                []
            );

        if ($this->request->is('ajax')) {
            return $response;
        }
    }

    public function updateStatus($id)
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        $user = $this->Users->get($id);
        $status = $this->request->getData('status');

        $user->status = $status;
        if ($this->Users->save($user)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Status updated successfully.',
                ['newStatus' => $user->status]
            );
        } else {
            return AjaxResponseHelper::createResponse(
                false,
                'Failed to update status. Please try again.'
            );
        }
    }
}
