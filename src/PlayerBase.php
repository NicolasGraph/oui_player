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
  * PlayerBase
  *
  * Plugin base class.
  *
  * @package Oui\Player
  */

namespace Oui;

abstract class PlayerBase
{
    /**
     * Master plugin name.
     *
     * @var string
     * @see getPlugin().
     */

    protected static $plugin = 'oui_player';

    /**
     * Installed providers as an array of provider names
     * associated with their extensions related plugin author prefixes.
     *
     * @var array
     * @see setProviders(), getProviders().
     */

    protected static $providers = array();

    /**
     * Media related field name.
     *
     * @var string
     * @see setMediaField(), getMediaField().
     */

    protected static $mediaField;

    /**
     * Constructor.
     */

    public function __construct()
    {
        self::setMediaField();
        static::setProviders();
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
     * $providers getter.
     *
     * @return array
     */

    protected static function getProviders()
    {
        return static::$providers;
    }

    /**
     * $mediaField setter.
     */

    protected static function setMediaField()
    {
        return self::$mediaField = get_pref('oui_player_custom_field');
    }

    /**
     * $mediaField getter.
     */

    protected static function getMediaField()
    {
        return self::$mediaField;
    }
}
