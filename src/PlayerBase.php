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
  * Player
  *
  * Plugin base class.
  *
  * @package Oui\Player
  */

namespace Oui {

    abstract class PlayerBase
    {
        /**
         * The plugin name.
         *
         * @var string
         */

        protected static $plugin;

        /**
         * The plugged providers.
         *
         * @var array
         */

        protected static $providers = array();

        /**
         * Multidimensional associative array of plugin tags,
         * attributes and attibute values.
         *
         * @var array
         */

        protected static $tags = array(
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
                'responsive' => array(
                    'default' => '',
                ),
                'microdata' => array(
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

        /**
         * Plugin prefs related privilege levels.
         *
         * @var string
         */

        protected static $privs = '1, 2';

        /**
         * Multidimensional associative array
         * of plugin general prefs.
         *
         * @var array.
         */

        protected static $prefs = array(
            'custom_field' => array(
                'widget'  => 'Oui\PlayerAdmin::customFields',
                'default' => 'article_image',
            ),
            'provider' => array(
                'default' => '',
            ),
            'responsive' => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'microdata' => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'providers' => array(
                'default' => '',
            ),
        );

        /**
         * Plugin property setter.
         */

        public static function setPlugin()
        {
            static::$plugin = 'oui_player';
        }

        /**
         * $plugin property getter.
         *
         * @return string self::$plugin
         */

        public static function getPlugin()
        {
            self::$plugin or self::setPlugin();

            return self::$plugin;
        }

        /**
         * $providers property getter.
         *
         * @return array static::$providers
         */

        public static function getProviders()
        {
            static::$providers or static::setProviders();

            return static::$providers;
        }

        /**
         * $tags property getter.
         *
         * @return array static::$tags
         */

        public static function getTags()
        {
            return static::$tags;
        }

        /**
         * $privs property getter.
         *
         * @return array self::$privs
         */

        public static function getPrivs()
        {
            return self::$privs;
        }

        /**
         * $prefs property getter.
         *
         * @return array static::$prefs
         */

        public static function getPrefs()
        {
            return static::$prefs;
        }

        public static function getPref($name = null, $key = null)
        {
            return $key ? static::$prefs[$name][$key] : static::$prefs[$name];
        }

        public static function setPref($name, $key, $value)
        {
            static::$prefs[$name][$key] = $value;
        }
    }
}
