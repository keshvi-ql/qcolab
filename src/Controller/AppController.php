<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        $this->loadComponent('Authentication.Authentication');

        $rememberMeToken = $this->request->getCookie('remember_me_token');
        if ($rememberMeToken && !$this->Authentication->getIdentity()) {
            $user = $this->Users->get($rememberMeToken);
            if ($user) {
                $this->Authentication->setIdentity($user);
            }
        }

        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {

            $userId = $this->Authentication->getIdentity()->get('id');
            
            $timeLog = $this->getTableLocator()->get('TimeLogs')->find()
                ->where(['user_id' => $userId, 'status !=' => 'completed'])
                ->order(['created' => 'DESC'])
                ->first();
            
            $this->set('currentStatus', $timeLog ? $timeLog->status : 'none');
        }

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }
}
