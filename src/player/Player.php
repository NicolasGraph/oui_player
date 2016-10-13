<?php

/*
 * oui_player - Easily embed customized players..
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * This file is part of oui_player.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace Oui {

    class Player
    {
        protected static $plugin;
        protected static $instance = null;
        protected $pophelp = 'http://help.ouisource.com/';
        protected $tags = array(
            'oui_player' => array(
                'class' => array(
                    'default' => '',
                ),
                'label' => array(
                    'default' => '',
                ),
                'labeltag' => array(
                    'default' => '',
                ),
                'provider' => array(
                    'default' => '',
                ),
                'play' => array(
                    'default' => '',
                ),
                'wraptag' => array(
                    'default' => '',
                ),
            ),
            'oui_if_player' => array(
                'play' => array(
                    'default' => '',
                ),
                'provider' => array(
                    'default' => '',
                ),
            ),
        );
        protected $privs = '1, 2';
        protected $prefs = array(
            'custom_field' => array(
                'widget'  => 'oui_player_custom_fields',
                'default' => 'article_image',
            ),
            'provider' => array(
            ),
        );

        public static function getInstance()
        {
            $class = get_called_class();
            if (is_null(self::$instance)) {
                self::$plugin = strtolower(str_replace('\\', '_', $class));
                self::$instance = new $class;
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->providers = callback_event(self::$plugin, 'plug_providers', 0, 'Provider');
            $this->tags['oui_player']['provider']['valid'] = $this->providers;
            $this->tags['oui_if_player']['provider']['valid'] = $this->providers;
            $this->prefs['provider']['default'] = $this->providers[0];
            $this->prefs['provider']['valid'] = $this->providers;
        }

        final private function __clone()
        {
        }

        /**
         * Admin:  Set privs, callbacks, prefs.
         * Public: Register tags.
         */
        public function setPlugin()
        {
            if (txpinterface === 'admin') {
                add_privs('plugin_prefs.' . self::$plugin, $this->privs);
                add_privs('prefs.' . self::$plugin, $this->privs);

                register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . self::$plugin);
                register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . self::$plugin, null, 1);

                // Add privs to provider prefs only if they are enabled.
                foreach ($this->providers as $provider) {
                    $group = self::$plugin . '_' . strtolower($provider);
                    $pref = $group . '_prefs';
                    if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                        add_privs('prefs.' . $group, $this->privs);
                    }
                }
            } else {
                // Register Textpattern tags for TXP 4.6+.
                foreach ($this->tags as $tag => $attributes) {
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
                case 'enabled':
                    $this->setPrefs();
                    $this->deleteOldPrefs();
                    break;
                case 'deleted':
                    safe_delete('txp_prefs', "event LIKE '" . self::$plugin . "%'");
                    safe_delete('txp_lang', "owner = '" . self::$plugin . "'");
                    break;
            }
        }

        /**
         * Jump to the prefs panel.
         */
        public function optionsLink()
        {
            $url = '?event=prefs#prefs_group_' . self::$plugin;
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
                if ($valid === array('0', '1')) {
                    $widget = 'yesnoradio';
                } elseif ($valid === array('true', 'false')) {
                    $widget = self::$plugin . '_truefalseradio';
                } else {
                    $widget = self::$plugin . '_pref';
                }
            } else {
                $widget = 'text_input';
            }

            return $widget;
        }

        /**
         * Build select inputs for plugin prefs
         *
         * @param string $name The name of the preference (Textpattern variable)
         * @param string $val  The value of the preference (Textpattern variable)
         */
        public function prefSelect($name, $val)
        {
            $prefs = $this->getPrefs();

            foreach ($prefs as $pref => $options) {
                if ($pref === $name) {
                    $valid = $options['valid'];
                    $vals = array();

                    foreach ($valid as $value) {
                        $value === '' ?: $vals[$value] = gtxt($pref . '_' . $value);
                    }

                    return selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
                }
            }
        }

        /**
         * Collect plugin prefs
         */
        public function getPrefs()
        {
            $prefs = array();

            foreach ($this->prefs as $pref => $options) {
                $options['group'] = self::$plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach ($this->providers as $provider) {
                $options = array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = self::$plugin;
                $pref = $options['group'] . '_' . strtolower($provider) . '_prefs';
                $prefs[$pref] = $options;

                $class = __CLASS__ . '\\' . $provider;
                $instance =  $class::getInstance();
                $prefs = $instance->getPrefs($prefs);
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
                if (get_pref($pref, null) === null) {
                    set_pref(
                        $pref,
                        $options['default'],
                        $options['group'],
                        PREF_PLUGIN,
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

            safe_delete(
                'txp_prefs',
                "event LIKE '" . self::$plugin . "%' AND name NOT IN ( '" . implode(array_keys($prefs), "', '") . "' )"
            );
        }

        /**
         * Get a tag attribute list
         *
         * @param string $tag The plugin tag
         */
        public function getAtts($tag)
        {
            $get_atts = array();

            foreach ($this->tags[$tag] as $att => $options) {
                $get_atts[$att] = $options;
            }

            if ($tag === self::$plugin) {
                foreach ($this->providers as $provider) {
                    $class = __CLASS__ . '\\' . $provider;
                    $instance =  $class::getInstance();
                    $get_atts = $instance->getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Get the video provider and the video id from its url
         *
         * @param string $play The item url
         */
        public function getItemInfos($play)
        {
            foreach ($this->providers as $provider) {
                $class = __CLASS__ . '\\' . $provider;
                $instance =  $class::getInstance();
                $match = $instance->getItemInfos($play);
                if ($match) {
                    return $match;
                }
            }

            return false;
        }
    }

    $player = Player::getInstance();
    $player->setPlugin();

}
