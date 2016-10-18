<?php

/*
 * oui_player - An extendable plugin to easily embed iframe
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * This file is part of oui_player.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see https://www.gnu.org/licenses/.
 */

namespace Oui\Player {

    class Tags extends Player
    {
        public $provider;
        public $play;
        public $latts;

        public function __construct()
        {
            parent::__construct();
        }
        /**
         * Get a tag attribute list
         *
         * @param string $tag The plugin tag
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
                    $obj = new $class;
                    $get_atts = $obj->getAtts($tag, $get_atts);
                }
            }

            return $get_atts;
        }

        /**
         * Get the video provider and the video id from its url
         *
         * @param string $play The item url
         */
        public function getItemInfos()
        {
            if (isset($this->provider)) {
                $class = __NAMESPACE__ . '\\' . $this->provider;
                $obj = new $class;
                $obj->provider = $this->provider;
                $obj->play = $this->play;
                $match = $obj->getItemInfos();
                return $match;
            } else {
                foreach ($this->providers as $provider) {
                    $class = __NAMESPACE__ . '\\' . $provider;
                    $obj = new $class;
                    $obj->play = $this->play;
                    $match = $obj->getItemInfos();
                    if ($match) {
                        return $match;
                    }
                }
            }

            return false;
        }

        /**
         * Get the video provider and the video id from its url
         *
         * @param string $play The item url
         */
        public function getPlayer()
        {
            if (isset($this->provider)) {
                $class = __NAMESPACE__ . '\\' . $this->provider;
                $obj = new $class;
                $obj->provider = $this->provider;
                $obj->play = $this->play;
                $obj->latts = $this->latts;
                $out = $obj->getPlayer();
                return $out;
            } else {
                foreach ($this->providers as $provider) {
                    $class = __NAMESPACE__ . '\\' . $provider;
                    $obj = new $class;
                    $obj->play = $this->play;
                    $obj->latts = $this->latts;
                    $out = $obj->getPlayer();
                    if ($out) {
                        return $out;
                    }
                }
            }

            return false;
        }
    }
}
