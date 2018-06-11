<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2018 Nicolas Morand
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
  * Player
  *
  * Plugin base class.
  *
  * @package Oui\Player
  */

namespace Oui\Player {

    class Player
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

        protected static $providers;

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
                'widget'  => 'Oui\Player\Admin::customFields',
                'default' => 'article_image',
            ),
            'provider' => array(
                'default' => '',
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
            static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
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
