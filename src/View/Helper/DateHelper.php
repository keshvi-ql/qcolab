<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class DateHelper extends Helper
{
    /**
     * Formats the given date and/or time based on the format stored in the settings table.
     *
     * @param \Cake\I18n\FrozenTime|string|null $date The date to format.
     * @param bool $includeTime Whether to include the time in the format.
     * @param string|null $defaultDateFormat The fallback date format if not found in settings.
     * @param string|null $defaultTimeFormat The fallback time format if not found in settings.
     * @return string|null The formatted date or null if no date is provided.
     */
    public function format($date, $includeTime = true, $defaultDateFormat = 'jS M Y', $defaultTimeFormat = 'h:i A')
    {
        if ($date === null) {
            return null;
        }

        // Fetch the date and time formats from the settings table
        $settingsTable = TableRegistry::getTableLocator()->get('Settings');
        
        // Fetch date format
        $dateFormat = $settingsTable->findByName('date_format')->first();
        $dateFormatValue = $dateFormat->value ?? $defaultDateFormat;

        // Fetch time format if needed
        $timeFormat = null;
        if ($includeTime) {
            $timeFormatSetting = $settingsTable->findByName('time_format')->first();
            $timeFormat = $timeFormatSetting->value ?? $defaultTimeFormat;
        }

        // Combine date and time formats if necessary
        $format = $timeFormat ? "{$dateFormatValue} {$timeFormat}" : $dateFormatValue;

        // Convert the date to a FrozenTime object if it's not already
        if (!$date instanceof FrozenTime) {
            $date = new FrozenTime($date);
        }

        return $date->format($format);
    }
}
