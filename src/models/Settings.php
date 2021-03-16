<?php
/**
 * Time Traveler plugin for Craft CMS 3.x
 *
 * Add the time zone control on user level
 *
 * @link      https://frontend.services/
 * @copyright Copyright (c) 2021 Mato Tominac
 */

namespace matotominac\timetraveler\models;


use Craft;
use craft\base\Model;
use craft\helpers\UrlHelper;
use DateTime;
use DateTimeZone;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

    public $timezoneList = null;
    public $fieldName = 'timeZone';


    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
    }

    public function getTimezoneList()
    {
        return $this->_getSettingValue('timezoneList') ?? $this->_allTimezones();
    }

    public function getFieldName()
    {
        return $this->_getSettingValue('fieldName');
    }


    // Private Methods
    // =========================================================================

    private function _allTimezones()
    {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime('now', new DateTimeZone('UTC'));

            foreach (DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = $this->_niceTimezoneName($timezone);
            }

            array_multisort($offsets, $timezones);
        }

        return $timezones;
    }

    private function _niceTimezoneName($timezone) {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $now->setTimezone(new DateTimeZone($timezone));
        $offset = $now->getOffset();
        return '(' . $this->_format_GMT_offset($offset) . ') ' . $this->_format_timezone_name($timezone);
    }

    private function _format_GMT_offset($offset) {
        $hours = (int)($offset / 3600);
        $minutes = abs((int)($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    private function _format_timezone_name($name) {
        $name = str_replace(array('/', '_', 'St '), array(', ', ' ', 'St. '), $name);
        return $name;
    }

    private function _getSettingValue($value)
    {
        $currentSite = Craft::$app->getSites()->getCurrentSite();
        $siteSettings = $this->siteSettings[$currentSite->handle] ?? [];

        // Allow global override
        if ($this->$value) {
            return $this->$value;
        }

        if (Craft::$app->getIsMultiSite() && $siteSettings && isset($siteSettings[$value])) {
            return $siteSettings[$value];
        }

        return null;
    }

}
