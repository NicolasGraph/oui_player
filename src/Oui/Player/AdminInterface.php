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
 * AdminInterface
 *
 * Manages admin side plugin features.
 *
 * @package Oui\Player
 */

interface AdminInterface
{
    /**
     * $plugin getter.
     *
     * @return string
     */

    public static function getPlugin();

    /**
     * $privs getter.
     *
     * @return array
     */

    public static function getPrivs();

    /**
     * $iniPrefs getter
     *
     * @return array
     */

    public static function getIniPrefs();

    /**
     * $prefs getter.
     *
     * @return array
     */

    public static function getPrefs();

    /**
     * $prefs item getter.
     *
     * @param  array $name Preference name (without the event related prefix).
     * @return string The preference value.
     * @throws \Exception
     */

    public static function getPref($name);

    /**
     * Install/update preferences.
     */

    public function install();

    /**
     * Redirect Options links to the general preferences tab.
     */

    public function redirectPrefs();

    /**
     * Add plugin preferences visibility related privilege levels to the Preferences panel.
     */

    public function addPrivs();

    /**
     * Remove $prefsEvent related preferences.
     */

    public function uninstall();

    /**
     * Generate a Yes/No radio button toggle using 'true' and 'false' as values.
     *
     * @param  string $field    The field name
     * @param  string $checked  The checked button, either 'true', 'false'
     * @param  int    $tabindex The HTML tabindex
     * @param  string $id       The HTML id
     * @return string HTML
     */

    public static function truefalseradio($field, $checked = '', $tabindex = 0, $id = '');

    /**
     * Plugin preference widget.
     *
     * @param  string $name The preference name
     * @param  string $val  The preference value
     * @return string HTML
     */

    public static function prefFunction($name, $val);

    /**
     * Generate a select list of article_image + custom fields.
     *
     * @param  string $name The name of the preference
     * @param  string $val  The value of the preference
     * @return string HTML
     */

    public static function getFieldsWidget($name, $val);
}
