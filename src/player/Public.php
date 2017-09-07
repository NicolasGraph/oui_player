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
            $this->plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            $this->providers = explode(', ', \get_pref($this->plugin . '_providers'));
        }

        /**
         * Get tag attributes.
         *
         * @param string $tag The plugin tag.
         */
        public function getAtts($tag)
        {
            $get_atts = array();

            foreach ($this->tags[$tag] as $att => $options) {
                $get_atts[$att] = '';
            }

            if ($tag === $this->plugin) {
                foreach ($this->providers as $provider) {
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
        public function getInfos()
        {
            foreach ($this->providers as $provider) {
                $class = __NAMESPACE__ . '\\' . $provider;
                $obj = $class::getInstance();
                $obj->play = $this->play;
                $infos = $obj->getInfos();

                if ($infos) {
                    $this->provider = $provider;
                    $this->infos = $infos;
                    return;
                }
            }

            return false;
        }

        /**
         * Get the player code
         */
        public function getPlayer()
        {
            $item = $this->getInfos();

            if ($this->provider) {
                $class = __NAMESPACE__ . '\\' . $this->provider;
            } else {
                $class = __NAMESPACE__ . '\\' . \get_pref($this->plugin . '_provider');
            }

            $obj = $class::getInstance();
            $obj->play = $this->play;
            $obj->infos = $this->infos;
            $obj->config = $this->config;
            $out = $obj->getPlayer();

            if ($out) {
                return $out;
            }

            return false;
        }
    }
}
