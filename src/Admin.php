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
 * PalyerAdmin
 *
 * Manages admin side plugin features.
 *
 * @package Oui\Player
 */

namespace Oui\Player;

class Admin extends Base
{
    /**
     * Plugin preferences visibility related privilege levels.
     *
     * @var string
     * @see getPrivs();
     */

    protected static $privs = '1, 2';

    /**
     * Associative array of all preference names and their initial related infos.
     * TODO: complete docs.
     *
     * @var array
     * @see setIniPrefs(), getIniPrefs().
     */

    protected static $iniPrefs;

    /**
     * Associative array of all preference names and their current related values.
     *
     * @var array.
     * @see getPrefs();
     */

    protected static $prefs;

    /**
     * Constructor
     * - Add main privileges;
     * - register callbacks.
     */

    public function __construct()
    {
        parent::__construct();

        $plugin = self::getPlugin();

        foreach (array('plugin_prefs.', 'prefs.') as $event) {
            add_privs($event . $plugin, self::getPrivs());
        }

        register_callback(array($this, 'managePrefs'), 'prefs', '', 1);
        register_callback('Oui\Player\Admin::optionsLink', 'plugin_prefs.' . $plugin, null, 1);
        register_callback(array($this, 'uninstall'), 'plugin_lifecycle.' . $plugin, 'deleted');

        foreach (self::getProviders() as $provider => $author) {
            $extension = strtolower($author . '_' . $provider);

            add_privs('plugin_prefs.' . $extension, self::getPrivs());
            register_callback('Oui\Player\Admin::optionsLink', 'plugin_prefs.' . $extension, null, 1);
        }
    }

    /**
     * $providers setter.
     * Trigger an error on duplicate provider related extensions.
     */

    public static function setProviders()
    {
        foreach(get_declared_classes() as $className) {
            if (is_subclass_of($className, 'Oui\Player\Provider') && $className !== 'Oui\Player\Oembed') {
                list($author, $provider) = explode('\\', $className);

                if (array_key_exists($provider, static::$providers)) {
                    trigger_error(gtxt(array(
                        'oui_player_duplicate_provider_extensions',
                        '{provider}' => $provider,
                    )));
                } else {
                    static::$providers[$provider] = $author;
                }
            }
        }
    }

    /**
     * $privs getter.
     *
     * @return array
     */

    public static function getPrivs()
    {
        return self::$privs;
    }

    /**
     * $iniPrefs property setter
     */

    public static function setIniPrefs()
    {
        $providers = self::getProviders();
        $providerNames = array_keys($providers);

        $mainPrefs = array(
            'custom_field' => array(
                'widget'  => 'Oui\Player\Admin::customFields',
                'default' => 'article_image',
            ),
            'provider' => array(
                'default' => $providerNames[0],
                'valid'   => $providerNames,
            ),
            'providers' => array(
                'default' => http_build_query($providers),
                'type'    => PREF_HIDDEN,
            ),
        );

        $plugin = self::getPlugin();

        // Collect the plugin main prefs.
        foreach ($mainPrefs as $pref => $options) {
            $options['group'] = $plugin;
            $pref = $options['group'] . '_' . $pref;
            static::$iniPrefs[$pref] = $options;
        }

        foreach ($providers as $provider => $author) {
            // Add privilieges to provider prefs only if they are enabled.
            $group = $plugin . '_' . strtolower($provider);
            $pref = $group . '_prefs';

            if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                add_privs('prefs.' . $group, self::getPrivs());
            }

            // Add a pref per provider to display/hide its own prefs group.
            $options = array(
                'default' => '1',
                'valid'   => array('0', '1'),
            );
            $options['group'] = $plugin;
            static::$iniPrefs[$pref] = $options;

            // Collect provider prefs.
            $class = $author . '\\' . $provider;
            static::$iniPrefs = array_merge(static::$iniPrefs, $class::GetIniPrefs());
        }
    }

    /**
     * $iniPrefs getter
     *
     * @return array
     */

    public static function getIniPrefs()
    {
        static::$iniPrefs or self::setIniPrefs();

        return static::$iniPrefs;
    }

    /**
     * $prefs setter.
     */

    protected static function setPrefs()
    {
        $rs = safe_rows_start(
            'name, html',
            doSlash('txp_prefs'),
            "name LIKE '" . doSlash(self::getPlugin() . '_%') . "'"
        );

        if ($rs) {
            static::$prefs = array();

            while ($row = nextRow($rs)) {
                static::$prefs[$row['name']] = $row['html'];
            }
        }
    }

    /**
     * $prefs getter.
     * Call setter if necesary.
     *
     * @return array
     */

    public static function getPrefs()
    {
        static::$prefs or self::setPrefs();

        return static::$prefs;
    }

    /**
     * Install/update/remove preferences.
     */

    public function managePrefs()
    {
        if (self::getProviders()) {
            $this->upsertPrefs();
            $this->deleteOldPrefs();
        } else {
            $this->uninstall();
        }
    }

    /**
     * Upsert plugin preferences.
     */

    public function upsertPrefs()
    {
        $iniPrefs = $this->getIniPrefs();
        $position = 250;
        $existing = self::getPrefs();

        foreach ($iniPrefs as $pref => $options) {
            $default = isset($options['default']) ? $options['default'] : '';

            if (isset($options['widget'])) {
                $widget = $options['widget'];
            } else {
                $valid = isset($options['valid']) ? $options['valid'] : null;
                $widget = self::getPrefWidget($valid);
            }

            if ($pref === 'oui_player_providers') {
                set_pref(
                    $pref,
                    $default,
                    $options['group'],
                    PREF_HIDDEN,
                    $widget,
                    $position
                );
            } elseif (!isset($existing[$pref])) {
                create_pref(
                    $pref,
                    $default,
                    $options['group'],
                    isset($options['type']) ? $options['type'] : PREF_PLUGIN,
                    $widget,
                    $position
                );
            } elseif ($existing[$pref] !== $widget) {
                update_pref($pref, $default, null, null, $widget, null);
            }

            $position += 10;
        }
    }

    /**
     * Delete outdated plugin preferences.
     */

    protected function deleteOldPrefs()
    {
        safe_delete(
            'txp_prefs',
            "event LIKE '" . self::getPlugin() . "%' AND " .
            "name NOT IN ( '" . implode(array_keys($this->getIniPrefs()), "', '") . "' )"
        );
    }

    /**
     * Remove plugin preferences.
     */

    public function uninstall()
    {
        safe_delete('txp_prefs', "event LIKE '" . self::getPlugin() . "%'");
    }

    /**
     * Redirect Options links to the general preferences tab.
     */

    public static function optionsLink()
    {
        global $event;

        header('Location: ?event=' . str_replace('plugin_prefs.', 'prefs#prefs_group_', $event));
    }

    /**
     * Define a plugin preference widget.
     *
     * @param  array  $options Current pref options.
     * @return string Function/method name.
     */

    protected static function getPrefWidget($valid = null)
    {
        if ($valid) {
            $validIsArray = is_array($valid);

            if ($validIsArray && !array_diff($valid, array('0', '1'))) {
                $widget = 'yesnoradio';
            } elseif ($validIsArray && !array_diff($valid, array('true', 'false'))) {
                $widget = 'Oui\Player\Admin::truefalseradio';
            } else {
                $widget = 'Oui\Player\Admin::prefFunction';
            }
        } else {
            $widget = 'text_input';
        }

        return $widget;
    }

    /**
     * Generate a Yes/No radio button toggle using 'true' and 'false' as values.
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
     * Plugin preference widget.
     *
     * @param  string $name The preference name
     * @param  string $val  The preference value
     * @return string HTML
     */

    public static function prefFunction($name, $val)
    {
        $prefs = self::getIniPrefs();
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
     * Generate a select list of article_image + custom fields.
     *
     * @param  string $name The name of the preference
     * @param  string $val  The value of the preference
     * @return string HTML
     */

    public static function customFields($name, $val)
    {
        $vals = array();
        $vals['article_image'] = gtxt('article_image');
        // $vals['excerpt'] = gtxt('excerpt');

        $customFields = safe_rows(
            "name, val",
            'txp_prefs',
            "name LIKE 'custom_%_set' AND val<>'' ORDER BY name"
        );

        foreach ($customFields as $row) {
            $vals[$row['val']] = $row['val'];
        }

        return selectInput($name, $vals, $val);
    }
}

txpinterface === 'admin' ? new Admin : '';
