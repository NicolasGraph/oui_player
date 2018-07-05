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
  * Main
  *
  * Manages public side plugin features.
  *
  * @package Oui\Player
  */

namespace Oui {

    class Player extends PlayerBase
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

        public function __construct() {
            foreach (Player::getTags() as $tag => $attributes) {
                $tagMethod = str_replace(array('oui', '_'), array('render', ''), $tag);
                $tagMethods[$tag] = $tagMethod;

                \Txp::get('\Textpattern\Tag\Registry')->register('Oui\Player::' . $tagMethod, $tag);
            }

            foreach (Player::getProviders() as $provider => $author) {
                foreach ($tagMethods as $tag => $method) {
                    \Txp::get('\Textpattern\Tag\Registry')->register($author . '\\' . $provider . '::' . $method, str_replace('player', $provider, $tag));
                }
            }
        }

        /**
         * $providers property setter.
         */

        public static function setProviders()
        {
            foreach (explode('&', get_pref(self::getPlugin() . '_providers')) as $providerAuthor) {
                $providerAuthor = explode('=', $providerAuthor);
                $provider = $providerAuthor[0];
                $author = $providerAuthor[1];

                static::$providers[$provider] = $author;
            }
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
            $providers = self::getProviders();

            foreach ($providers as $provider => $author) {
                $class = $author . '\\' . $provider;
                $this->infos = \Txp::get($class)
                    ->setPlay($this->getPlay())
                    ->getInfos();

                if ($this->infos) {
                    $this->provider = $class;

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

                $providerName = get_pref(self::getPlugin() . '_provider');
                $this->provider = $providers[$providerName] . '\\' . $providerName;
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
            $allAtts = array();
            $tags = self::getTags();

            // Collects main attributes.
            foreach ($tags[$tag] as $att => $options) {
                $allAtts[$att] = '';
            }

            if ($tag === self::getPlugin()) {
                // Collects provider attributes.
                foreach (self::getProviders() as $provider => $author) {
                    $class = $author . '\\' . $provider;
                    $allAtts = $class::getAtts($tag, $allAtts);
                }
            }

            return $allAtts;
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
                return \Txp::get($provider)
                    ->setPlay($this->getPlay())
                    ->setConfig($this->getConfig())
                    ->getPlayer();
            }

            trigger_error('Undefined oui_player provider.');
        }

        /**
         * Generates a player.
         *
         * @param  array  $atts Tag attributes
         * @return string HTML
         */

        public static function renderPlayer($atts)
        {
            global $thisarticle, $oui_player_item;

            $lAtts = lAtts(self::getAtts('oui_player'), $atts); // Gets used attributes.

            extract($lAtts); // Extracts used attributes.

            if (!$play) {
                if (isset($oui_player_item['play'])) {
                    $play = $oui_player_item['play'];
                } else {
                    $play = $thisarticle[get_pref('oui_player_custom_field')];
                }
            }

            if (!$provider && isset($oui_player_item['provider'])) {
                $provider = $oui_player_item['provider'];
            }

            if ($provider) {
                $providers = self::getProviders();
                $class_in_use = $providers[$provider] . '\\' . $provider;
            } else {
                $class_in_use = __CLASS__;
            }

            $player = \Txp::get($class_in_use)
                ->setPlay($play, true)
                ->setConfig($lAtts)
                ->getPlayer($wraptag, $class);

            return doLabel($label, $labeltag) . $player;
        }

        /**
         * Generates tag contents or alternative contents.
         *
         * Generated contents depends on whether the 'play' attribute value
         * matches a provider URL scheme.
         *
         * @param  array  $atts  Tag attributes
         * @param  string $thing Tag contents
         * @return mixed  Tag contents or alternative contents
         */

        public static function renderIfPlayer($atts, $thing)
        {
            global $thisarticle, $oui_player_item;

            extract(lAtts(self::getAtts('oui_if_player'), $atts)); // Extracts used attributes.

            $field = get_pref('oui_player_custom_field');

            if (!$play) {
                if (!$play && isset($thisarticle[$field])) {
                    $play = $thisarticle[$field];
                } else {
                    $play = false;
                }
            }

            if ($play) {
                if ($provider) {
                    $providers = self::getProviders();
                    $class_in_use = $providers[$provider] . '\\' . $provider;
                } else {
                    $class_in_use = __CLASS__;
                }

                if ($is_valid = \Txp::get($class_in_use)->setPlay($play)->isValid()) {
                    $oui_player_item = array('play' => $play);
                    $provider ? $oui_player_item['provider'] = $provider : '';
                }

                $out = parse($thing, $is_valid);

                unset($GLOBALS['oui_player_item']);

                return $out;
            }

            return parse($thing, false);
        }
    }
}

namespace {
    if (txpinterface === 'public') {
        \Txp::get('Oui\Player');
    }
}
