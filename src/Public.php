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
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
  * Main
  *
  * Manages public side plugin features.
  *
  * @package Oui\Player
  */

namespace Oui\Player {

    class Main extends Player
    {
        /**
         * The value provided through the play
         * attribute value of the plugin tag.
         *
         * @var string
         */

        protected $play;

        /**
         * The $play related provider.
         *
         * @var string
         */

        protected $provider;

        /**
         * Associative array of play value(s) and their.
         *
         * @var array
         */

        protected $infos = array();

        /**
         * Associative array of player parameters
         * provided via attributes.
         *
         * @var array
         */

        protected $config;

        /**
         * Caches the class instance.
         *
         * @var object
         */

        private static $instance = null;

        /**
         * Singleton.
         */

        final public static function getInstance()
        {
            $class = get_called_class();

            if (!isset(static::$instance[$class])) {
                static::$instance[$class] = new static();
            }

            return static::$instance[$class];
        }

        /**
         * $providers property setter.
         */

        public static function setProviders()
        {
            static::$providers = explode(', ', get_pref(self::getPlugin() . '_providers'));
        }

        public function setPlay($value, $fallback = false)
        {
            $this->play = $value;
            $infos = $this->getInfos();

            if (!$infos || array_diff(explode(', ', $value), array_keys($infos))) {
                $this->setInfos($fallback);
            }

            return $this;
        }

        /**
         * Gets the play property.
         */

        public function getPlay()
        {
            return $this->play;
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @param  bool  $fallback Whether to set fallback infos or not.
         * @return array An associative array of
         */

        public function getProvider($fallback = false)
        {
            $this->infos or $this->setInfos($fallback);

            if ($this->provider && !array_diff(explode(', ', $this->getPlay()), array_keys($this->infos))) {
                return $this->provider;
            }

            return false;
        }

        /**
         * Finds the right provider to use and set the current media(s) infos.
         *
         * @return bool false if no provider is found.
         */

        public function setInfos($fallback = false)
        {
            foreach (self::getProviders() as $provider) {
                $class = __NAMESPACE__ . '\\' . $provider;
                $this->infos = $class::getInstance()
                    ->setPlay($this->getPlay())
                    ->getInfos();

                if ($this->infos) {
                    $this->provider = $provider;

                    return $this->infos;
                }
            }

            if (!$this->infos && $fallback) {
                // No matched provider, set default infos.
                $this->infos = array(
                    $this->getPlay() => array(
                        'play' => $this->getPlay(),
                        'type' => 'id',
                    )
                );

                $this->provider = get_pref(self::getPlugin() . '_provider');
            }

            return $this->infos;
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @param  bool  $fallback Whether to set fallback infos or not.
         * @return array An associative array of
         */

        public function getInfos()
        {
            return $this->infos;
        }

        public function setConfig($value)
        {
            $this->config = $value;

            return $this;
        }

        public function getConfig()
        {
            return $this->config;
        }

        /**
         * Get tag attributes.
         *
         * @param  string $tag The plugin tag
         * @return array  An associative array using attributes as keys.
         */

        public static function getAtts($tag)
        {
            $get_atts = array();
            $tags = self::getTags();

            // Collects main attributes.
            foreach ($tags[$tag] as $att => $options) {
                $get_atts[$att] = '';
            }

            if ($tag === self::getPlugin()) {
                // Collects provider attributes.
                foreach (self::getProviders() as $provider) {
                    $class = __NAMESPACE__ . '\\' . $provider;
                    $get_atts = $class::getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Whether a provided URL to play matches a provider URL scheme or not.
         *
         * @return bool
         */

        public function isValid()
        {
            return $this->getInfos();
        }

        /**
         * Gets the player code
         */

        public function getPlayer()
        {
            if ($provider = $this->getProvider(true)) {
                $class = __NAMESPACE__ . '\\' . $provider;

                return $class::getInstance()
                    ->setPlay($this->getPlay())
                    ->setConfig($this->getConfig())
                    ->getPlayer();
            }

            trigger_error('Undefined oui_player provider.');
        }
    }

    if (txpinterface === 'public') {
        foreach (Main::getTags() as $tag => $attributes) {
            \Txp::get('\Textpattern\Tag\Registry')->register($tag);

            foreach (Main::getProviders() as $provider) {
                \Txp::get('\Textpattern\Tag\Registry')->register(str_replace('player', strtolower($provider), $tag));
            }
        }
    }
}
