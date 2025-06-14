<?php

declare(strict_types=1);

namespace App\Utility;

use Cake\Http\Response;
use Cake\ORM\TableRegistry;

class SettingHelper
{
    public static function getSettingValue($name)
    {
        $settingsTable = TableRegistry::getTableLocator()->get('Settings');
        $result = $settingsTable->find()->where(['name' => $name])->first();

        if ($result) {
            return $result->value;
        } else {
            return null;
        }
    }

    public static function getNotificationSettingValue($type)
    {
        $notificationSettingTable = TableRegistry::getTableLocator()->get('NotificationSettings');
        $result = $notificationSettingTable->find('all', [
            'conditions' => ['type' => $type]
        ])->first();

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }
}
