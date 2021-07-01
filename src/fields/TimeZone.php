<?php
/**
 * Time Traveler plugin for Craft CMS 3.x
 *
 * Add the time zone control on user level
 *
 * @link      https://frontend.services/
 * @copyright Copyright (c) 2021 Mato Tominac
 */

namespace matotominac\timetraveler\fields;

use craft\fields\data\SingleOptionFieldData;
use craft\fields\Dropdown;
use matotominac\timetraveler\TimeTraveler;

use Craft;

/**
 * TimeZone Field
 *
 * Whenever someone creates a new field in Craft, they must specify what
 * type of field it is. The system comes with a handful of field types baked in,
 * and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Mato Tominac
 * @package   TimeTraveler
 * @since     1.0.0
 */
class TimeZone extends Dropdown
{
    // Public Properties
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        // set our options before Craft's BaseOptionsField normalizes them.
        $this->getTimezoneOptions();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'time-traveler/_components/fields/TimeZone_settings',
            [
                'field' => $this,
            ]
        );
    }

    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('time-traveler', 'TimeZone');
    }

    /**
     * @inheritdoc
     */
    public static function valueType(): string
    {
        return SingleOptionFieldData::class;
    }

    // Public Methods
    // =========================================================================

    // Protected Methods
    // =========================================================================

    /**
     * Get all timezones
     *
     * @return void
     */
    protected function getTimezoneOptions(): void
    {
        $this->options = [
            [
                'label' => Craft::t('site', 'Default'),
                'value' => '',
                'disabled' => true,
            ]
        ];

        $plugin = TimeTraveler::getInstance();
        if ($plugin) {
            $settings = $plugin->getSettings();
            if ($settings) {
                foreach ($settings->getTimezoneList() as $timezone => $label) {
                    $this->options[] = [
                        'label' => $label,
                        'value' => $timezone,
                        'default' => $timezone == Craft::$app->getTimeZone() ? 1 : null
                    ];
                }
            }
        }
    }

    public function defaultValue()
    {
        return parent::defaultValue();
    }
}
