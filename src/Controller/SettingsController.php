<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;

/**
 * Settings Controller
 *
 */
class SettingsController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Settings');

        $query = $this->Settings->find();
        $settings = $this->paginate($query);

        $this->set(compact('settings'));
    }

    public function add()
    {
        $this->set('title', 'Settings');

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $allSavedSuccessfully = false;

            if (isset($data['log_activity'])) {
                $data['log_activity'] = '1';
            } else {
                $data['log_activity'] = '0';
            }

            foreach ($data as $name => $value) {
                $setting = $this->Settings->find('all', [
                    'conditions' => ['name' => $name]
                ])->first();

                if ($setting) {
                    $setting->value = $value;
                } else {
                    $setting = $this->Settings->newEntity([
                        'name' => $name,
                        'value' => $value
                    ]);
                }

                if ($this->Settings->save($setting)) {
                    $allSavedSuccessfully = true;
                }
            }

            if ($allSavedSuccessfully) {
                $this->Flash->success(__('Settings saved successfully.'));
            } else {
                $this->Flash->error(__('Unable to save some settings. Please try again.'));
            }

            return $this->redirect(['action' => 'add']);
        }

        $existingSettings = $this->Settings->find('all')->toArray();

        $settingsArray = [];
        foreach ($existingSettings as $setting) {
            $settingsArray[$setting->name] = $setting->value;
        }

        $this->set(compact('settingsArray'));
    }
}
