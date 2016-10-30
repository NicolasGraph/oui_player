<?php

/*
 * oui_player - An extendable plugin to easily embed iframe
 * customizable players in Textpattern CMS.
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
 * along with this program; if not, see https://www.gnu.org/licenses/.
 */

namespace Oui\Player {

    class Admin extends Player
    {
        public function __construct()
        {
            if (txpinterface === 'admin') {
                $this->plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
                $this->providers = callback_event($this->plugin, 'plug_providers', 0, 'Provider');

                $this->prefs['provider']['valid'] = $this->providers;
                $this->prefs['provider']['default'] = $this->prefs['provider']['valid'][0];
                $this->prefs['providers']['default'] = implode(', ', $this->prefs['provider']['valid']);

                add_privs('plugin_prefs.' . $this->plugin, $this->privs);
                add_privs('prefs.' . $this->plugin, $this->privs);

                register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . $this->plugin);
                register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . $this->plugin, null, 1);

                // Add privs to provider prefs only if they are enabled.
                foreach ($this->providers as $provider) {
                    $group = $this->plugin . '_' . strtolower($provider);
                    $pref = $group . '_prefs';
                    if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && \get_pref($pref))) {
                        add_privs('prefs.' . $group, $this->privs);
                    }
                }
            } else {
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
                    safe_delete('txp_prefs', "event LIKE '" . $this->plugin . "%'");
                    safe_delete('txp_lang', "owner = '" . $this->plugin . "'");
                    break;
            }
        }

        /**
         * Jump to the prefs panel.
         */
        public function optionsLink()
        {
            $url = '?event=prefs#prefs_group_' . $this->plugin;
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
                    $widget = $this->plugin . '_truefalseradio';
                } else {
                    $widget = $this->plugin . '_pref_widget';
                }
            } elseif ($valid) {
                $widget = $this->plugin . '_pref_widget';
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
        public function prefFunction($name, $val)
        {
            $prefs = $this->getPrefs();
            $valid = $prefs[$name]['valid'];

            if (is_array($valid)) {
                $vals = array();

                foreach ($valid as $value) {
                    $value === '' ?: $vals[$value] = gtxt($name . '_' . $value);
                }

                return selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
            } else {
                return fInput($valid, $name, $val);
            }
        }

        /**
         * Collect plugin prefs
         */
        public function getPrefs()
        {
            $prefs = array();

            foreach ($this->prefs as $pref => $options) {
                $options['group'] = $this->plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach ($this->providers as $provider) {
                $options = array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = $this->plugin;
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
                if (\get_pref($pref, null) === null) {
                    \set_pref(
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
                "event LIKE '" . $this->plugin . "%' AND name NOT IN ( '" . implode(array_keys($prefs), "', '") . "' )"
            );
        }
    }

    new Admin;
}
