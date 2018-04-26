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
         *
         * @see \callback_event()
         *      \add_privs()
         *      \register_callback()
         *      \get_pref()
         */

        public function __construct()
        {
            global $event;

            // Gets the plugin name from the class namespace.
            static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));

            \add_privs('plugin_prefs.' . static::$plugin, self::$privs);
            \add_privs('prefs.' . static::$plugin, self::$privs);

            \register_callback(array($this, 'uninstall'), 'plugin_lifecycle.' . static::$plugin, 'deleted');

            if ($event === 'prefs') {
                \register_callback(array($this, 'install'), 'admin_side', 'head_end');
            }

            \register_callback('Oui\Player\Admin::optionsLink', 'plugin_prefs.' . static::$plugin, null, 1);
        }

        public function install()
        {
            $this->plugProviders();

            if (static::$providers) {
                $this->setPrefs();
                $this->deleteOldPrefs();
            } else {
                $this->uninstall();
            }
        }

        public function uninstall()
        {
            \safe_delete('txp_prefs', "event LIKE '" . static::$plugin . "%'");
        }

        /**
         * Links 'options' to the prefs panel.
         */

        public static function optionsLink()
        {
            header('Location: ?event=prefs#prefs_group_' . static::$plugin);
        }

        /**
         * Defines a plugin pref widget.
         *
         * @param  array  $options Current pref options
         * @return string HTML
         * @see    \yesnoradio()
         *         \oui_player_truefalseradio()
         *         \oui_player_pref_widget()
         *         \text_input()
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
         * @see    GetAllPrefs()
         *         \gtxt()
         *         \selectInput()
         *         \fInput()
         */

        public static function prefFunction($name, $val)
        {
            $prefs = self::GetAllPrefs();
            $valid = $prefs[$name]['valid'];

            if (is_array($valid)) {
                $vals = array();

                foreach ($valid as $value) {
                    $value === '' ?: $vals[$value] = \gtxt($name . '_' . strtolower($value));
                }

                return \selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
            } else {
                return \fInput($valid, $name, $val);
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

            $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

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
         * @see    radioSet()
         * @return string HTML
         */

        public static function truefalseradio($field, $checked = '', $tabindex = 0, $id = '')
        {
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
         * @see    getAllPrefs()
         */

        public static function PlugProviders()
        {
            return static::$providers = \callback_event(static::$plugin, 'plug_providers', 0, 'Provider');
        }

        /**
         * $allPrefs property setter
         * Collects plugin prefs
         *
         * @return array $allPrefs
         * @see    getAllPrefs()
         */

        public static function SetAllPrefs()
        {
            $prefs = array();

            static::$prefs['provider']['valid'] = static::$providers;
            static::$prefs['provider']['default'] = static::$prefs['provider']['valid'][0];
            static::$prefs['providers']['default'] = implode(', ', static::$prefs['provider']['valid']);

            // Collects the plugin main prefs.
            foreach (static::$prefs as $pref => $options) {
                $options['group'] = static::$plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach (static::$providers as $provider) {
                // Adds privilieges to provider prefs only if they are enabled.
                $group = static::$plugin . '_' . strtolower($provider);
                $pref = $group . '_prefs';

                if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && \get_pref($pref))) {
                    \add_privs('prefs.' . $group, self::$privs);
                }

                // Adds a pref per provider to display/hide its own prefs group.
                $options = array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = static::$plugin;
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
         * @see    setAllPrefs()
         */

        public static function GetAllPrefs()
        {
            static::$allPrefs or self::SetAllPrefs();

            return static::$allPrefs;
        }

        /**
         * Set plugin prefs.
         *
         * @see GetAllPrefs()
         *      \get_pref()
         *      \set_pref()
         *      prefWidget()
         */

        public function setPrefs()
        {
            $prefs = $this->GetAllPrefs();
            $position = 250;

            $existing = array();

            if ($rs = safe_rows_start('name, html', doSlash('txp_prefs'), "name LIKE '".doSlash('oui_player_%')."'")) {
                while ($row = nextRow($rs)) {
                    $existing[$row['name']] =  $row['html'];
                }
            }

            foreach ($prefs as $pref => $options) {
                $widget = isset($options['widget']) ? $options['widget'] : self::prefWidget($options);

                if ($pref === 'oui_player_providers' || !isset($existing[$pref])) {
                    \create_pref(
                        $pref,
                        $options['default'],
                        $options['group'],
                        $pref === 'oui_player_providers' ? PREF_HIDDEN : PREF_PLUGIN,
                        $widget,
                        $position
                    );
                } elseif (isset($existing[$pref]) && $existing[$pref] !== $widget) {
                    \update_pref(
                        $pref,
                        $options['default'],
                        null,
                        null,
                        $widget,
                        null
                    );
                }

                $position += 10;
            }
        }

        /**
         * Deletes old unused plugin prefs.
         *
         * @see GetAllPrefs()
         *      \safe_delete()
         */

        private function deleteOldPrefs()
        {
            $prefs = $this->GetAllPrefs();

            \safe_delete(
                'txp_prefs',
                "event LIKE '" . static::$plugin . "%' AND name NOT IN ( '" . implode(array_keys($prefs), "', '") . "' )"
            );
        }
    }

    global $event;

    if (txpinterface === 'admin' && ($event === 'prefs' || $event === 'plugin')) {
        new Admin;
    }
}
