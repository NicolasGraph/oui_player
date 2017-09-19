<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2017 Nicolas Morand
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
         * Constructor
         *
         * @see \callback_event()
         *      \add_privs()
         *      \register_callback()
         *      \get_pref()
         */

        public function __construct()
        {
            if (txpinterface === 'admin') {
                // Gets the plugin name from the class namespace.
                static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
                // Adds an event to plug providers and store them.
                static::$providers = \callback_event(static::$plugin, 'plug_providers', 0, 'Provider');
                // Completes plugin main prefs.
                static::$prefs['provider']['valid'] = static::$providers;
                static::$prefs['provider']['default'] = static::$prefs['provider']['valid'][0];
                static::$prefs['providers']['default'] = implode(', ', static::$prefs['provider']['valid']);

                \add_privs('plugin_prefs.' . static::$plugin, static::$privs);
                \add_privs('prefs.' . static::$plugin, static::$privs);

                \register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . static::$plugin);
                \register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . static::$plugin, null, 1);

                // Adds privilieges to provider prefs only if they are enabled.
                foreach (static::$providers as $provider) {
                    $group = static::$plugin . '_' . strtolower($provider);
                    $pref = $group . '_prefs';

                    if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && \get_pref($pref))) {
                        \add_privs('prefs.' . $group, static::$privs);
                    }
                }
            } else {
                // Registers plugin tags.
                foreach (static::$tags as $tag => $attributes) {
                    \Txp::get('\Textpattern\Tag\Registry')->register($tag);
                }
            }
        }

        /**
         * Plugin lifecycle events handler.
         *
         * @param string $evt Textpattern event
         * @param string $stp Textpattern step
         * @see   setPrefs()
         *        deleteOldPrefs()
         *        \safe_delete()
         */

        public function lifeCycle($evt, $stp)
        {
            switch ($stp) {
                case 'installed':
                    $this->setPrefs();
                    $this->deleteOldPrefs();
                    break;
                case 'deleted':
                    \safe_delete('txp_prefs', "event LIKE '" . static::$plugin . "%'");
                    \safe_delete('txp_lang', "owner = '" . static::$plugin . "'");
                    break;
            }
        }

        /**
         * Links 'options' to the prefs panel.
         */

        public function optionsLink()
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

        public function prefWidget($options)
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
         * @see    getPrefs()
         *         \gtxt()
         *         \selectInput()
         *         \fInput()
         */

        public static function prefFunction($name, $val)
        {
            $prefs = self::getPrefs();
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
         * Collects plugin prefs
         *
         * @return array Preferences
         * @see    getPrefs()
         */

        public static function getPrefs()
        {
            $prefs = array();

            // Collects the plugin main prefs.
            foreach (static::$prefs as $pref => $options) {
                $options['group'] = static::$plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach (static::$providers as $provider) {
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
                $obj = new $class;
                $prefs = $obj->getPrefs($prefs);
            }

            return $prefs;
        }

        /**
         * Set plugin prefs.
         *
         * @see getPrefs()
         *      \get_pref()
         *      \set_pref()
         *      prefWidget()
         */

        public function setPrefs()
        {
            $prefs = $this->getPrefs();
            $position = 250;

            foreach ($prefs as $pref => $options) {
                if ($pref === 'oui_player_providers' || \get_pref($pref, null) === null) {
                    \set_pref(
                        $pref,
                        $options['default'],
                        $options['group'],
                        $pref === 'oui_player_providers' ? PREF_HIDDEN : PREF_PLUGIN,
                        isset($options['widget']) ? $options['widget'] : $this->prefWidget($options),
                        $position
                    );
                }

                $position += 10;
            }
        }

        /**
         * Deletes old unused plugin prefs.
         *
         * @see getPrefs()
         *      \safe_delete()
         */

        public function deleteOldPrefs()
        {
            $prefs = $this->getPrefs();

            \safe_delete(
                'txp_prefs',
                "event LIKE '" . static::$plugin . "%' AND name NOT IN ( '" . implode(array_keys($prefs), "', '") . "' )"
            );
        }
    }

    Admin::getInstance();
}
