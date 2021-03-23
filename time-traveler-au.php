<?php

/**
 * Time Traveler config file with Australian timezones
 *
 * Rename this file to `time-traveler.php` and copy to Craft's `/config/` folder
 */

return [
    '*' => [
        'timezoneList' => [
            'Australia/Adelaide' => 'Central Daylight Time',
            'Australia/Darwin' => 'Central Standard Time',
            'Australia/Eucla' => 'Central Western Standard Time',
            'Australia/Sydney' => 'Eastern Daylight Time',
            'Australia/Brisbane' => 'Eastern Standard Time',
            'Australia/Lord_Howe' => 'Lord Howe Daylight Time',
            'Australia/Perth' => 'Western Standard Time',
        ],
        'fieldName' => 'timeZone'
    ],
];