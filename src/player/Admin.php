<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Oui\Player {

    class Admin extends Player
    {
        public function __construct()
        {
            if (txpinterface === 'admin') {
                static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
                static::$providers = \callback_event(static::$plugin, 'plug_providers', 0, 'Provider');

                static::$prefs['provider']['valid'] = static::$providers;
                static::$prefs['provider']['default'] = static::$prefs['provider']['valid'][0];
                static::$prefs['providers']['default'] = implode(', ', static::$prefs['provider']['valid']);

                \add_privs('plugin_prefs.' . static::$plugin, static::$privs);
                \add_privs('prefs.' . static::$plugin, static::$privs);

                \register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . static::$plugin);
                \register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . static::$plugin, null, 1);

                // Add privs to provider prefs only if they are enabled.
                foreach (static::$providers as $provider) {
                    $group = static::$plugin . '_' . strtolower($provider);
                    $pref = $group . '_prefs';
                    if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && \get_pref($pref))) {
                        \add_privs('prefs.' . $group, static::$privs);
                    }
                }
            } else {
                foreach (static::$tags as $tag => $attributes) {
                    \Txp::get('\Textpattern\Tag\Registry')->register($tag);
                }
            }
        }

        /**
         * Handler for plugin lifecycle events.
         *
         * @param string $evt Textpattern action event
         * @param string $stp Textpattern action step
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
         * Jump to the prefs panel.
         */
        public function optionsLink()
        {
            $url = '?event=prefs#prefs_group_' . static::$plugin;
            header('Location: ' . $url);
        }

        /**
         * Define the pref widget
         *
         * @param array $options Current pref options
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
                    $widget = static::$plugin . '_truefalseradio';
                } else {
                    $widget = static::$plugin . '_pref_widget';
                }
            } elseif ($valid) {
                $widget = static::$plugin . '_pref_widget';
            } else {
                $widget = 'text_input';
            }

            return $widget;
        }

        /**
         * Build plugin pref inputs
         *
         * @param string $name The name of the preference (Textpattern variable)
         * @param string $val  The value of the preference (Textpattern variable)
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
         * Collect plugin prefs
         */
        public static function getPrefs()
        {
            $prefs = array();

            foreach (static::$prefs as $pref => $options) {
                $options['group'] = static::$plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach (static::$providers as $provider) {
                $options = array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = static::$plugin;
                $pref = $options['group'] . '_' . strtolower($provider) . '_prefs';
                $prefs[$pref] = $options;

                $class = __NAMESPACE__ . '\\' . $provider;
                $obj = new $class;
                $prefs = $obj->getPrefs($prefs);
            }

            return $prefs;
        }

        /**
         * Install plugin prefs
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
                $position = $position + 10;
            }
        }

        /**
         * Delete potential old unused plugin prefs
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
