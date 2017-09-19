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

        public $play;

        /**
         * The $play related provider.
         *
         * @var string
         */

        public $provider;

        /**
         * Associative array of 'play' value(s) and their.
         *
         * @var array
         */

        public $infos = array();

        /**
         * Associative array of player parameters
         * provided via attributes.
         *
         * @var array
         */

        public $config;

        /**
         * Constructor.
         *
         * @see \get_pref()
         */

        protected function __construct()
        {
            static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            static::$providers = explode(', ', \get_pref(static::$plugin . '_providers'));
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
                    $obj = $class::getInstance();
                    $get_atts = $obj->getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Finds the right provider to use and set the current media(s) infos.
         *
         * @return string The matched provider.
         * @see    getPlay()
         *         \get_pref()
         */

        public function setInfos()
        {
            foreach (static::$providers as $provider) {
                $class = __NAMESPACE__ . '\\' . $provider;
                $obj = $class::getInstance();
                $obj->play = $this->getPlay();

                if ($this->infos = $obj->setInfos()) {
                    return $this->provider = $provider;
                }
            }

            // No matched provider, set default infos.
            $this->infos = array(
                $this->getPlay() => array(
                    'play' => $this->getPlay(),
                    'type' => 'id',
                )
            );

            return $this->provider = \get_pref(static::$plugin . '_provider');
        }

        /**
         * Gets the provider property; set it if necessary.
         *
         * @return string A provider
         * @see    setInfos()
         */

        public function getProvider()
        {
            return $this->provider ? $this->provider : $this->setInfos();
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @return array An associative array of
         * @see    setInfos()
         *         \gtxt()
         */

        public function getInfos()
        {
            if ($this->infos || $this->setInfos()) {
                return $this->infos;
            }

            throw new \Exception(gtxt('undefined_infos'));
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
            if ($provider = $this->setInfos()) {
                $class = __NAMESPACE__ . '\\' . $provider;

                $obj = $class::getInstance();
                $obj->play = $this->getPlay();
                $obj->infos = $this->getInfos();
                $obj->config = $this->config;

                return $obj->getPlayer();
            }

            throw new \Exception(gtxt('undefined_player'));
        }
    }
}
