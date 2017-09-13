<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Oui\Player {

    class Main extends Player
    {
        public $play;
        public $provider;
        public $infos = array();
        public $config;

        public function __construct()
        {
            static::$plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            static::$providers = explode(', ', \get_pref(static::$plugin . '_providers'));
        }

        /**
         * Get tag attributes.
         *
         * @param string $tag The plugin tag.
         */
        public function getAtts($tag)
        {
            $get_atts = array();

            foreach (static::$tags[$tag] as $att => $options) {
                $get_atts[$att] = '';
            }

            if ($tag === static::$plugin) {
                foreach (static::$providers as $provider) {
                    $class = __NAMESPACE__ . '\\' . $provider;
                    $obj = $class::getInstance();
                    $get_atts = $obj->getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Check if the play property is a recognised URL scheme.
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

            $this->infos = array(
                $this->getPlay() => array(
                    'play' => $this->getPlay(),
                    'type' => 'id',
                )
            );

            return $this->provider = \get_pref(static::$plugin . '_provider');
        }

        /**
         * Check if the play property is a recognised URL scheme.
         */
        public function getProvider()
        {
            return $this->provider ? $this->provider : $this->setInfos();
        }

        /**
         * Check if the play property is a recognised URL scheme.
         */
        public function getInfos()
        {
            if ($this->infos || $this->setInfos()) {
                return $this->infos;
            }

            throw new \Exception(gtxt('undefined_infos'));
        }

        /**
         * Check if the play property is a recognised URL scheme.
         */
        public function getPlay()
        {
            if ($this->play) {
                return $this->play;
            }

            throw new \Exception(gtxt('undefined_property'));
        }

        /**
         * Get the player code
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
