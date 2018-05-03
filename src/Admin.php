<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2018 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Admin
 *
 * Manages admin side plugin features.
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Admin extends Player
    {
        /**
         * Caches the class instance.
         *
         * @var object
         */

        private static $instance = null;

        /**
         * Caches the collected prefs.
         *
         * @var object
         */

        private static $allPrefs = null;

        /**
         * Constructor
         */

        public function __construct()
        {
            global $event;

            $plugin = self::getPlugin();
            $privs = self::getPrivs();

            foreach (array('plugin_prefs.', 'prefs.') as $ev) {
                add_privs($ev . $plugin, $privs);
            }

            register_callback(
                array($this, 'uninstall'),
                'plugin_lifecycle.' . $plugin,
                'deleted'
            );

            if ($event === 'prefs') {
                register_callback(array($this, 'install'), 'admin_side', 'head_end');
            }

            register_callback(
                'Oui\Player\Admin::optionsLink',
                'plugin_prefs.' . $plugin,
                null,
                1
            );
        }

        /**
         * $providers property setter.
         */

        public static function setProviders()
        {
            static::$providers = callback_event(self::getPlugin(), 'plug_providers', 0, array());
        }

        public function install()
        {
            if (self::getProviders()) {
                $this->setPrefs();
                $this->deleteOldPrefs();
            } else {
                $this->uninstall();
            }
        }

        public function uninstall()
        {
            safe_delete('txp_prefs', "event LIKE '" . self::getPlugin() . "%'");
        }

        /**
         * Links 'options' to the prefs panel.
         */

        public static function optionsLink()
        {
            header('Location: ?event=prefs#prefs_group_' . self::getPlugin());
        }

        /**
         * Defines a plugin pref widget.
         *
         * @param  array  $options Current pref options
         * @return string HTML
         */

        private static function prefWidget($options)
        {
            $valid = isset($options['valid']) ? $options['valid'] : false;

            if ($valid && is_array($valid)) {
                $yesno_diff = array_diff($valid, array('0', '1'));
                $truefalse_diff = array_diff($valid, array('true', 'false'));

                if (empty($yesno_diff)) {
                    $widget = 'yesnoradio';
                } elseif (empty($truefalse_diff)) {
                    $widget = 'Oui\Player\Admin::truefalseradio';
                } else {
                    $widget = 'Oui\Player\Admin::prefFunction';
                }
            } elseif ($valid) {
                $widget = 'Oui\Player\Admin::prefFunction';
            } else {
                $widget = 'text_input';
            }

            return $widget;
        }

        /**
         * Builds a plugin pref widget.
         *
         * @param  string $name The preference name (Txp var)
         * @param  string $val  The preference value (Txp var)
         * @return string HTML
         */

        public static function prefFunction($name, $val)
        {
            $prefs = self::getAllPrefs();
            $valid = $prefs[$name]['valid'];

            if (is_array($valid)) {
                $vals = array();

                foreach ($valid as $value) {
                    $value === '' ?: $vals[$value] = gtxt($name . '_' . strtolower($value));
                }

                return selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
            } else {
                return fInput($valid, $name, $val);
            }
        }

        /**
         * Generates a select list of custom + article_image + excerpt fields.
         *
         * @param  string $name The name of the preference (Textpattern variable)
         * @param  string $val  The value of the preference (Textpattern variable)
         * @return string HTML
         */

        public static function customFields($name, $val)
        {
            $vals = array();
            $vals['article_image'] = gtxt('article_image');
            $vals['excerpt'] = gtxt('excerpt');

            $custom_fields = safe_rows(
                "name, val",
                'txp_prefs',
                "name LIKE 'custom_%_set' AND val<>'' ORDER BY name"
            );

            if ($custom_fields) {
                foreach ($custom_fields as $row) {
                    $vals[$row['val']] = $row['val'];
                }
            }

            return selectInput($name, $vals, $val);
        }

        /**
         * Generates a Yes/No radio button toggle using 'true'/'false' as values.
         *
         * @param  string $field    The field name
         * @param  string $checked  The checked button, either 'true', 'false'
         * @param  int    $tabindex The HTML tabindex
         * @param  string $id       The HTML id
         * @return string HTML
         */

        public static function truefalseradio(
            $field,
            $checked = '',
            $tabindex = 0,
            $id = ''
        ) {
            $vals = array(
                'false' => gTxt('no'),
                'true' => gTxt('yes'),
            );

            return radioSet($vals, $field, $checked, $tabindex, $id);
        }

        /**
         * $allPrefs property setter
         * Collects plugin prefs
         *
         * @return array $allPrefs
         */

        public static function setAllPrefs()
        {
            $prefs = array();
            $plugin = self::getPlugin();
            $providers = self::getProviders();

            self::setPref('provider', 'valid', $providers);
            self::setPref('provider', 'default', self::getPref('provider', 'valid')[0]);
            self::setPref('providers', 'default', implode(', ', self::getPref('provider', 'valid')));

            // Collects the plugin main prefs.
            foreach (self::getPrefs() as $pref => $options) {
                $options['group'] = $plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach ($providers as $provider) {
                // Adds privilieges to provider prefs only if they are enabled.
                $group = $plugin . '_' . strtolower($provider);
                $pref = $group . '_prefs';

                if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                    add_privs('prefs.' . $group, self::getPrivs());
                }

                // Adds a pref per provider to display/hide its own prefs group.
                $options = array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = $plugin;
                $pref = $options['group'] . '_' . strtolower($provider) . '_prefs';
                $prefs[$pref] = $options;

                // Collects provider prefs.
                $class = __NAMESPACE__ . '\\' . $provider;
                $prefs = $class::GetPrefs($prefs);
            }

            return static::$allPrefs = $prefs;
        }

        /**
         * $allPrefs property getter
         *
         * @return array $allPrefs
         */

        public static function getAllPrefs()
        {
            static::$allPrefs or self::setAllPrefs();

            return static::$allPrefs;
        }

        /**
         * Set plugin prefs.
         */

        public function setPrefs()
        {
            $prefs = $this->getAllPrefs();
            $position = 250;

            $existing = array();

            $rs = safe_rows_start(
                'name, html',
                doSlash('txp_prefs'),
                "name LIKE '".doSlash('oui_player_%')."'"
            );

            if ($rs) {
                while ($row = nextRow($rs)) {
                    $existing[$row['name']] =  $row['html'];
                }
            }

            foreach ($prefs as $pref => $options) {
                $widget = isset($options['widget']) ? $options['widget'] : self::prefWidget($options);

                if ($pref === 'oui_player_providers') {
                    set_pref(
                        $pref,
                        $options['default'],
                        $options['group'],
                        PREF_HIDDEN,
                        $widget,
                        $position
                    );
                } elseif (!isset($existing[$pref])) {
                    create_pref(
                        $pref,
                        $options['default'],
                        $options['group'],
                        PREF_PLUGIN,
                        $widget,
                        $position
                    );
                } elseif (isset($existing[$pref]) && $existing[$pref] !== $widget) {
                    update_pref($pref, $options['default'], null, null, $widget, null);
                }

                $position += 10;
            }
        }

        /**
         * Deletes old unused plugin prefs.
         */

        private function deleteOldPrefs()
        {
            $prefs = $this->getAllPrefs();

            safe_delete(
                'txp_prefs',
                "event LIKE '" .self::getPlugin() . "%' AND name NOT IN ( '" . implode(array_keys($prefs), "', '") . "' )"
            );
        }
    }

    global $event;

    $pluginPrefs = 'plugin_prefs.' . Admin::getPlugin();

    if (txpinterface === 'admin' && (in_array($event, array('plugin', 'prefs')) || substr($event, 0, strlen($pluginPrefs)) === $pluginPrefs)) {
        new Admin;

        $providers = Admin::getProviders();

        if ($providers) {
            foreach ($providers as $provider) {
                if (in_array($event, array($pluginPrefs . '_' . lcfirst($provider), 'prefs'))) {
                    $provider::getInstance();
                }
            }
        }
    }
}
