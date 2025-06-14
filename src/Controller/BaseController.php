<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Mailer;

class BaseController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function getLoginUserDetail(?string $key = null)
    {
        $identity = $this->Authentication->getIdentity();
        if ($identity) {
            if ($key) {
                // Return the specific detail if key is provided
                return $identity->get($key);
            } else {
                // Return all details if no key is provided
                return null;
            }
        }
        return null;
    }

    public function getEmailTemplate($slug)
    {
        $emailTemplateTable = TableRegistry::getTableLocator()->get('EmailTemplates');
        $result = $emailTemplateTable->find()->where(['slug' => $slug])->first();

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public static function sendEmail($email, $template_subject, $template_body)
    {
        $mailer = new Mailer('gmail');

        try {
            $mailer
                ->setTo($email)
                ->setSubject($template_subject)
                ->setEmailFormat('html')
                ->setViewVars(['htmlContent' => $template_body]);

            $mailer->viewBuilder()->setTemplate('html_template');
            $mailer->deliver();

            return true;
        } catch (Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return false;
        }
    }

    public static function sendSalaryMail($payroll, $templateSubject, $templateBody, $attachmentPath = null)
    {
        $mailer = new Mailer('gmail');

        try {
            $mailer
                ->setTo($payroll->users->email)
                ->setSubject($templateSubject)
                ->setEmailFormat('html')
                ->setViewVars(['htmlContent' => $templateBody]);

            $mailer->viewBuilder()->setTemplate('html_template');

            if ($attachmentPath && file_exists($attachmentPath)) {

                $pdfName =  $payroll->users->first_name . '_' . $payroll->users->last_name . '_' . date('M_Y', strtotime($payroll->month)) . '.pdf';

                $mailer->setAttachments([
                    $pdfName => [
                        'file' => $attachmentPath,
                        'mimetype' => 'application/pdf'
                    ]
                ]);
            }

            $mailer->deliver();

            return true;
        } catch (Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return false;
        }
    }
}
