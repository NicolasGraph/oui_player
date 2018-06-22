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

/*
 * Plugin tags
 */

namespace {

    /**
     * Generates a player.
     *
     * @param  array  $atts Tag attributes
     * @return string HTML
     */

    function oui_player($atts)
    {
        global $thisarticle, $oui_player_item;

        $namespace = 'Oui\Player'; // Plugin namespace.
        $main_class = $namespace . '\Main'; // Main plugin class.
        $lAtts = lAtts($main_class::getAtts(__FUNCTION__), $atts); // Gets used attributes.

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

        $class_in_use = $provider ? $namespace . '\\' . ucfirst($provider) : $main_class;

        $player = $class_in_use::getInstance()
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

    function oui_if_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        $namespace = 'Oui\Player'; // Plugin namespace.
        $main_class = $namespace . '\Main'; // Main plugin class.

        extract(lAtts($main_class::getAtts(__FUNCTION__), $atts)); // Extracts used attributes.

        $field = get_pref('oui_player_custom_field');

        if (!$play) {
            if (!$play && isset($thisarticle[$field])) {
                $play = $thisarticle[$field];
            } else {
                $play = false;
            }
        }

        if ($play) {
            $class_in_use = $provider ? $namespace . '\\' . ucfirst($provider) : $main_class;

            if ($is_valid = $class_in_use::getInstance()->setPlay($play)->isValid()) {
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
