<?php

/**
 * Time Traveler config file with US timezones
 *
 * Rename this file to `time-traveler.php` and copy to Craft's `/config/` folder
 */

return [
    '*' => [
        'timezoneList' => [
            'America/Anchorage' => 'Alaska Daylight Time',
            'America/Chicago' => 'Central Daylight Time',
            'America/New_York' => 'Eastern Daylight Time',
            'America/Adak' => 'Hawaii-Aleutian Daylight Time',
            'Pacific/Honolulu' => 'Hawaii Standard Time',
            'America/Denver' => 'Mountain Daylight Time',
            'America/Phoenix' => 'Mountain Standard Time',
            'America/Los_Angeles' => 'Pacific Daylight Time',
        ],
        'fieldName' => 'timeZone'
    ],
];