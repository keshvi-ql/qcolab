<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;

/**
 * EmailTemplates Controller
 *
 * @property \App\Model\Table\EmailTemplatesTable $EmailTemplates
 */
class EmailTemplatesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Email Templates');

        $this->loadDefaultTemplates();

        $query = $this->EmailTemplates->find();
        $emailTemplates = $this->paginate($query);

        $this->set('emailTemplates', $emailTemplates);
    }

    private function loadDefaultTemplates()
    {
        $templates = $this->defaultTemplates();

        foreach ($templates as $template) {
            $templateExists = $this->EmailTemplates->find()
                ->where(['slug' => $template['slug']])
                ->count();

            if (!empty($template['name']) && !empty($template['slug'])) {

                $data = [
                    'name' => $template['name'],
                    'slug' => $template['slug'],
                    'subject' => $template['subject'],
                    'placeholders' => serialize($template['placeholders']) // Serialize for storage
                ];

                if ($templateExists == 0) {
                    // Insert new template
                    $entity = $this->EmailTemplates->newEntity($data);
                    $this->EmailTemplates->save($entity);
                } else {
                    // Update existing template
                    $entity = $this->EmailTemplates->findBySlug($template['slug'])->first();
                    $entity = $this->EmailTemplates->patchEntity($entity, $data);
                    $this->EmailTemplates->save($entity);
                }
            }
        }
    }

    private function defaultTemplates()
    {
        return [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome-email',
                'subject' => 'Welcome to {company_name}',
                'placeholders' => [
                    '{firstname}' => 'User Firstname',
                    '{lastname}' => 'User Lastname',
                    '{email}' => 'User Email',
                    '{login_url}' => 'Login URL',
                    '{username}' => 'Username',
                    '{password}' => 'Password',
                    '{company_name}' => 'Company Name'
                ]
            ],
            [
                'name' => 'Forgot Password',
                'slug' => 'forgot-password',
                'subject' => 'Reset Password Instructions',
                'placeholders' => [
                    '{firstname}' => 'User Firstname',
                    '{lastname}' => 'User Lastname',
                    '{email}' => 'User Email',
                    '{reset_password_url}' => 'Reset Password URL',
                    '{email_signature}' => 'Email Signature',
                    '{company_name}' => 'Company Name'
                ]
            ],
            [
                'name' => 'General Notification',
                'slug' => 'general_notification',
                'subject' => '{EVENT_TITLE}',
                'placeholders' => [
                    '{APP_TITLE}' => 'App Title',
                    '{EVENT_TITLE}' => 'EVENT TITLE',
                    '{EVENT_DETAILS}' => 'EVENT Detail',
                ]
            ],
            [
                'name' => 'Salary Slip',
                'slug' => 'salary-slip',
                'subject' => 'Salary Slip {month}',
                'placeholders' => [
                    '{firstname}' => 'User Firstname',
                    '{month}' => 'Month',
                ]
            ],
        ];
    }

    /**
     * Edit method
     *
     * @param string|null $id Email Template id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Email Templates');

        $emailTemplate = $this->EmailTemplates->get($id);

        $placeholders = unserialize($emailTemplate->placeholders);

        if ($placeholders === false && $emailTemplate->placeholders !== 'b:0;') {
            ControllerHelper::flashMessage($this, 'error', 'The placeholders data could not be unserialized. Please check the database entry.');
            $placeholders = [];
        } else {
            if (is_array($placeholders)) {
                asort($placeholders);
            } else {
                $placeholders = [];
            }
        }

        // Check if the request is a POST or PUT (form submission)
        if ($this->request->is(['post', 'put'])) {
            // Patch the entity with the submitted form data
            $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());

            // Attempt to save the updated email template
            if ($this->EmailTemplates->save($emailTemplate)) {
                ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');
                // Redirect to the email templates list
                return $this->redirect(['action' => 'index']);
            } else {
                ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
            }
        }

        $this->set(compact('emailTemplate', 'placeholders'));
    }
}
