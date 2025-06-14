<?php

namespace App\View\Helper;

use Cake\View\Helper;
use DateTime;

class TimeHelper extends Helper
{
    /**
     * @param $datetime
     * @return mixed
     */
    public function timeAgo($datetime)
    {
        $now       = new DateTime();
        $givenTime = new DateTime($datetime);
        $interval  = $now->diff($givenTime);

        if ($interval->y >= 1) {
            return $interval->y . ' year' . ($interval->y > 1 ? 's' : '');
        } elseif ($interval->m >= 1) {
            return $interval->m . ' month' . ($interval->m > 1 ? 's' : '');
        } elseif ($interval->d >= 7) {
            $weeks = floor($interval->d / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '');
        } elseif ($interval->d >= 1) {
            return $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
        } elseif ($interval->h >= 1) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
        } elseif ($interval->i >= 1) {
            return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
        } else {
            return $interval->s . ' second' . ($interval->s > 1 ? 's' : '');
        }
    }
}
