<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2017 Nicolas Morand
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
         * The value provided through the 'play'
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
         * Associative array of 'play' value(s) and their.
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
         * Constructor.
         *
         * @see \get_pref()
         */

        private function __construct()
        {
            static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            static::$providers = explode(', ', \get_pref(static::$plugin . '_providers'));
        }

        /**
         * Singleton.
         */

        final public static function getInstance($play, $config = null)
        {
            $class = get_called_class();

            if (!isset(static::$instance[$class])) {
                static::$instance[$class] = new static();
            }

            static::$instance[$class]->play = $play;
            $config ? static::$instance[$class]->config = $config : '';

            return static::$instance[$class];
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

            // Collects main attributes.
            foreach (static::$tags[$tag] as $att => $options) {
                $get_atts[$att] = '';
            }

            if ($tag === static::$plugin) {
                // Collects provider attributes.
                foreach (static::$providers as $provider) {
                    $class = __NAMESPACE__ . '\\' . $provider;
                    $get_atts = $class::getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Finds the right provider to use and set the current media(s) infos.
         *
         * @return bool false if no provider is found.
         * @see    getPlay()
         *         \get_pref()
         */

        public function setInfos()
        {
            foreach (static::$providers as $provider) {
                $class = __NAMESPACE__ . '\\' . $provider;
                $obj = $class::getInstance($this->getPlay());

                if ($this->infos = $obj->setInfos()) {
                    $this->provider = $provider;

                    return true;
                }
            }

            return false;
        }

        /**
         * Set the current media(s) infos fallback.
         *
         * @see getPlay()
         *      \get_pref()
         */

        public function setFallbackInfos()
        {
            // No matched provider, set default infos.
            $this->infos = array(
                $this->getPlay() => array(
                    'play' => $this->getPlay(),
                    'type' => 'id',
                )
            );

            $this->provider = \get_pref(static::$plugin . '_provider');
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @param  bool  $fallback Whether to set fallback infos or not.
         * @return array An associative array of
         * @see    setInfos()
         *         \gtxt()
         */

        public function getInfos($fallback = true)
        {
            if ($this->infos && array_key_exists($this->getPlay(), $this->infos) || $this->setInfos()) {
                return $this->infos;
            } elseif ($fallback) {
                return $this->setFallbackInfos();
            }

            return false;
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @param  bool  $fallback Whether to set fallback infos or not.
         * @return array An associative array of
         * @see    setInfos()
         *         \gtxt()
         */

        public function getProvider($fallback = true)
        {
            if ($this->provider && array_key_exists($this->getPlay(), $this->infos) || $this->setInfos()) {
                return $this->provider;
            } elseif ($fallback) {
                return $this->setFallbackInfos();
            }

            return false;
        }

        /**
         * Gets the play property.
         *
         * @throws \Exception
         * @see    \gtxt()
         */

        public function getPlay()
        {
            if ($this->play) {
                return $this->play;
            }

            throw new \Exception(gtxt('undefined_property'));
        }

        /**
         * Gets the player code
         *
         * @throws \Exception
         * @see    setInfos()
         *         getPlay()
         *         getInfos()
         *         \gtxt()
         */

        public function getPlayer()
        {
            if ($provider = $this->getProvider()) {
                $class = __NAMESPACE__ . '\\' . $provider;

                return $class::getInstance(
                    $this->getPlay(),
                    $this->config,
                    $this->getInfos()
                )->getPlayer();
            }

            throw new \Exception(gtxt('undefined_player'));
        }
    }
}
