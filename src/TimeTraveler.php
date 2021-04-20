<?php
/**
 * Time Traveler plugin for Craft CMS 3.x
 *
 * Add the time zone control on user level
 *
 * @link      https://frontend.services/
 * @copyright Copyright (c) 2021 Mato Tominac
 */

namespace matotominac\timetraveler;

use craft\events\TemplateEvent;
use craft\web\View;
use matotominac\timetraveler\fields\TimeZone as TimeZoneField;
use matotominac\timetraveler\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Mato Tominac
 * @package   TimeTraveler
 * @since     1.0.0
 *
 * @property  TimeZonesService $timeZones
 */
class TimeTraveler extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * TimeTraveler::$plugin
     *
     * @var TimeTraveler
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.3';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * TimeTraveler::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        // Set correct Timezone
        $currentUser = Craft::$app->getUser();
        $fieldName = $this->getSettings()->getFieldName();

        if ($currentUser->getIdentity()) {
            $user = $currentUser->getIdentity();
            $timezone = null;

            if (isset($user->{$fieldName}) && $user->{$fieldName}->value) $timezone = $user->{$fieldName}->value;
            if ($timezone) {
                Craft::$app->setTimeZone($timezone);
            }
        }

        parent::init();
        self::$plugin = $this;

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = TimeZoneField::class;
            }
        );

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        Craft::info(
            Craft::t(
                'time-traveler',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

        // If not control panel request, bail
        if (Craft::$app->getRequest()->getIsCpRequest()) {
            // Load JS before template is rendered
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {

                    // Get view
                    $view = Craft::$app->getView();

                    // Load additional JS
                    $timezones = $this->getSettings()->getTimezoneList();
                    $js = "window.tttimezones = [{'timezone': '', 'label': 'Default'},";
                    foreach ($timezones as $timezone => $label) {
                        $js .= "{'timezone': '".$timezone."', 'label': '".$label."'},";
                    }
                    $js .= "]";
                    $view->registerJs($js, View::POS_BEGIN);
                }
            );
        }

    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }
}
