<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * Admin
 *
 * Manages admin side plugin features.
 *
 * @package Oui\Player
 */

namespace Oui {

    class PlayerAdmin extends PlayerBase
    {
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

            register_callback(array($this, 'install'), 'prefs', '', 1);

            register_callback(
                'Oui\PlayerAdmin::optionsLink',
                'plugin_prefs.' . $plugin,
                null,
                1
            );

            foreach (self::getProviders() as $provider => $author) {
                $extension = $author . '_' . $provider;

                add_privs('plugin_prefs.' . $extension, self::getPrivs());

                register_callback(
                    'Oui\PlayerAdmin::optionsLink',
                    'plugin_prefs.' . $extension,
                    null,
                    1
                );
            }
        }

        /**
         * $providers property setter.
         */

        public static function setProviders()
        {
            foreach(array_map('strtolower', get_declared_classes()) as $name) {
                if (is_subclass_of($name, 'Oui\Provider')) {
                    $nameParts = explode('\\', $name);
                    $author = $nameParts[0];
                    $provider = $nameParts[1];

                    if (!array_key_exists($provider, static::$providers)) {
                        static::$providers[$provider] = $author;
                    }
                }
            }
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
            global $event;

            header('Location: ?event=' . str_replace('plugin_prefs.', 'prefs#prefs_group_', $event));
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
                    $widget = 'Oui\PlayerAdmin::truefalseradio';
                } else {
                    $widget = 'Oui\PlayerAdmin::prefFunction';
                }
            } elseif ($valid) {
                $widget = 'Oui\PlayerAdmin::prefFunction';
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

            $customFields = safe_rows(
                "name, val",
                'txp_prefs',
                "name LIKE 'custom_%_set' AND val<>'' ORDER BY name"
            );

            if ($customFields) {
                foreach ($customFields as $row) {
                    $vals[strtolower($row['val'])] = $row['val'];
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
                'true'  => gTxt('yes'),
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
            $providerNames = array_keys($providers);

            self::setPref('provider', 'valid', $providerNames);
            self::setPref('provider', 'default', $providerNames[0]);
            self::setPref('providers', 'default', http_build_query($providers));

            // Collects the plugin main prefs.
            foreach (self::getPrefs() as $pref => $options) {
                $options['group'] = $plugin;
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            foreach ($providers as $provider => $author) {
                // Adds privilieges to provider prefs only if they are enabled.
                $group = $plugin . '_' . strtolower($provider);
                $pref = $group . '_prefs';

                if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                    add_privs('prefs.' . $group, self::getPrivs());
                }

                // Adds a pref per provider to display/hide its own prefs group.
                $options = array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                );
                $options['group'] = $plugin;
                $pref = $options['group'] . '_' . strtolower($provider) . '_prefs';
                $prefs[$pref] = $options;

                // Collects provider prefs.
                $class = $author . '\\' . $provider;
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

    $pluginPrefs = 'plugin_prefs.' . PlayerAdmin::getPlugin();

    if (txpinterface === 'admin') {
        new PlayerAdmin;
    }
}
