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

abstract class Admin
{
    /**
     * Master plugin name.
     *
     * @var string
     * @see getPlugin();
     */

    protected static $plugin = 'oui_player';

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
     * Constructor
     */

    public function __construct()
    {
        self::setPrefsEvent();
        self::setPrefs();

        $plugin = self::getPlugin();

        register_callback(array($this, 'install'), 'plugin_lifecycle.' . $plugin, 'enabled');
        add_privs('plugin_prefs.' . self::getPrefsEvent(), self::getPrivs());
        register_callback(array($this, 'redirectPrefs'), 'plugin_prefs.' . $plugin, null, 1);
        register_callback(array($this, 'addPrivs'), 'prefs', null, 1);
        register_callback(array($this, 'uninstall'), 'plugin_lifecycle.' . $plugin, 'deleted');
    }

    /**
     * $prefsEvent setter.
     *
     * @return string
     */

    protected static function setPrefsEvent()
    {
        static::$prefsEvent = self::getPlugin() . (method_exists(get_called_class(), 'getProvider') ? '_' . strtolower(static::getProvider()) : '');
    }

    /**
     * $prefsEvent getter.
     *
     * @return string
     */

    protected static function getPrefsEvent()
    {
        return static::$prefsEvent;
    }

    /**
     * $plugin getter.
     *
     * @return string
     */

    public static function getPlugin()
    {
        return self::$plugin;
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
     * $iniPrefs getter
     *
     * @return array
     */

    public static function getIniPrefs()
    {
        return static::$iniPrefs;
    }

    /**
     * $prefs setter.
     */

    final protected static function setPrefs()
    {
        $event = self::getPrefsEvent();

        static::$prefs = array();

        $prefRows = safe_rows_start(
            "name, val",
            'txp_prefs',
            "type = 1 AND event = '" . doSlash($event) . "'"
        );

        while ($prefRow = nextRow($prefRows)) {
            static::$prefs[str_replace($event . '_', '', $prefRow['name'])] = $prefRow['val'];
        }
    }

    /**
     * $prefs getter.
     *
     * @return array
     */

    public static function getPrefs()
    {
        return static::$prefs;
    }

    /**
     * $prefs item getter.
     *
     * @param  array $name Preference name (without the event related prefix).
     * @return string The preference value.
     * @throws \Exception
     */

    final public static function getPref($name)
    {
        static::$prefs ?: self::setPrefs();

        if (isset(static::$prefs[$name])) {
            return static::$prefs[$name];
        }

        throw new \Exception("Unknown preference: " . $name);
    }

    /**
     * Install/update preferences.
     */

    public function install()
    {
        self::getPrefsEvent() === self::getPlugin() ?: $this->plugProvider();
        $this->upsertPrefs();
        $this->deleteOldPrefs();
    }

    /**
     * Plug a provider by setting/updating some master plugin preferences.
     */

    protected function plugProvider()
    {
        $plugin = self::getPlugin();
        $providersPref = $plugin . '_providers';
        $providers = do_list_unique(get_pref($providersPref, null, true));
        $newProviders = array(static::getProvider());
        $providers ? $newProviders = array_merge($providers, $newProviders) : '';
        $position = safe_field('MAX(position) AS position', 'txp_prefs', 'name = "' . doSlash($providersPref) . '"');

        update_pref($providersPref, implode(', ', array_unique($newProviders)), null, null, 'text_input', null);
        set_pref(self::getPrefsEvent() . '_prefs', '1', $plugin, PREF_PLUGIN, 'yesnoradio', $position + 10);
    }

    /**
     * Upsert plugin preferences.
     */

    protected function upsertPrefs()
    {
        $existing = self::getPrefs();
        $group = self::getPrefsEvent();
        $position = safe_field('MAX(position) AS position', 'txp_prefs', '1 = 1');

        foreach (static::getIniPrefs() as $id => $options) {
            is_array($options) ?: $options = array('default' => $options);
            $default = isset($options['default']) ? $options['default'] : '';

            if (isset($options['widget'])) {
                $widget = $options['widget'];
            } else {
                $valid = isset($options['valid']) ? $options['valid'] : null;
                $widget = self::getPrefWidget($valid);
            }

            $name = $group . '_' . $id;

            if (isset($existing[$id])) {
                update_pref($name, $default, null, null, $widget, null);
            } else {
                $type = isset($options['type']) ? $options['type'] : PREF_PLUGIN;

                create_pref($name, $default, $group, $type, $widget, $position);
            }

            $position += 10;
        }
    }

    /**
     * Delete outdated plugin preferences.
     */

    protected function deleteOldPrefs()
    {
        $event = self::getPrefsEvent();

        safe_delete(
            'txp_prefs',
            "event = '" . doSlash($event) . "' AND " .
            "REPLACE(`name`, '" . $event . "_', '') NOT IN ( '" . implode(array_keys(static::getIniPrefs()), "', '") . "' )"
        );
    }

    /**
     * Redirect Options links to the general preferences tab.
     */

    public function redirectPrefs()
    {
        header('Location: ?event=prefs#prefs_group_', self::getPrefsEvent());
    }

    /**
     * Add plugin preferences visibility related privilege levels to the Preferences panel.
     */

    public function addPrivs()
    {
        $group = self::getPrefsEvent();
        $privs = self::getPrivs();

        if (method_exists(get_called_class(), 'getProvider')) {
            $pref = $group . '_prefs';

            if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                add_privs('prefs.' . $group, $privs);
            }
        } else {
            add_privs('prefs.' . $group, $privs);
        }
    }

    /**
     * Remove $prefsEvent related preferences.
     */

    public function uninstall()
    {
        safe_delete('txp_prefs', "event = '" . self::getPrefsEvent() . "'");
    }

    /**
     * Define a plugin preference widget.
     *
     * @param  string|array $valid Current preference valid value(s).
     * @return string Function/method name.
     */

    protected static function getPrefWidget($valid = null)
    {
        if ($valid) {
            $calledClass = get_called_class();
            $validIsArray = is_array($valid);

            if ($validIsArray && !array_diff($valid, array('0', '1'))) {
                $widget = 'yesnoradio';
            } elseif ($validIsArray && !array_diff($valid, array('true', 'false'))) {
                $widget = $calledClass . '::truefalseradio';
            } else {
                $widget = $calledClass . '::prefFunction';
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
        self::setPrefsEvent();

        $prefs = static::getIniPrefs();
        $prefix = self::getPrefsEvent() . '_';
        $valid = $prefs[str_replace($prefix, '', $name)]['valid'];

        if (is_array($valid)) {
            $vals = array();

            foreach ($valid as $value) {
                $value === '' ?: $vals[$value] = gTxt($name . '_' . strtolower($value));
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

    public static function getFieldsWidget($name, $val)
    {
        $vals = array();
        $vals['article_image'] = gTxt('article_image');
        // $vals['excerpt'] = gTxt('excerpt');

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
