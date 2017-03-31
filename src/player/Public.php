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

class Main extends Player
{
    public $play;
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
    public function checkUrl()
    {
        foreach ($this->providers as $provider) {
            $class = __NAMESPACE__ . '\\' . $provider;
            $obj = $class::getInstance();
            $obj->play = $this->play;
            $infos = $obj->getInfos();
            if ($infos) {
                return $infos;
            }
        }

        return false;
    }

    /**
     * Get the item URL, provider and ID from the play property.
     */
    public function getInfos()
    {
        $infos = $this->checkUrl();

        if (!$infos) {
            $infos = array(
                'url'      => '',
                'provider' => \get_pref($this->plugin . '_provider'),
                'id'       => $this->play,
                'type'     => '',
            );
        }

        return $infos;
    }

    /**
     * Get the player code
     */
    public function getPlayer()
    {
        $item = $this->getInfos();
        $class = __NAMESPACE__ . '\\' . $item['provider'];
        $obj = $class::getInstance();
        $obj->play = $item['id'];
        $obj->config = $this->config;
        $out = $obj->getPlayer();
        if ($out) {
            return $out;
        }

        return false;
    }
}
