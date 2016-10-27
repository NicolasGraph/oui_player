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

namespace {

    /*
     * Display a video
     */
    function oui_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        $class = 'Oui\Player\Main';
        $obj = new $class;

        // Set tag attributes
        $get_atts = $obj->getAtts(__FUNCTION__);
        $latts = lAtts($get_atts, $atts);
        extract($latts);

        if (!$play) {
            if ($oui_player_item) {
                $provider = $oui_player_item['provider'];
                $play = $oui_player_item['url'];
            } else {
                $play = strtolower(get_pref('oui_player_custom_field'));
            }
        }

        if ($provider) {
            $class = 'Oui\Player\\' . $provider;
            if (class_exists($class)) {
                $obj = new $class;
            } else {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        }

        $obj->play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;
        $obj->config = $latts;
        $out = $obj->getPlayer($labeltag, $label, $wraptag, $class);

        return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
    }

    /*
     * Check a video url and its provider if provided.
     */
    function oui_if_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        $class = 'Oui\Player\Main';
        $obj = new $class;

        // Set tag attributes
        $get_atts = $obj->getAtts(__FUNCTION__);
        $latts = lAtts($get_atts, $atts);
        extract($latts);

        // Check if the play attribute value is recognised.
        if ($provider) {
            $class = 'Oui\Player\\' . $provider;
            if (class_exists($class)) {
                $obj = new $class;
            } else {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        }

        $play ?: $play = strtolower(get_pref('oui_player_custom_field'));
        $obj->play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;
        $oui_player_item = $obj->getInfos();

        $out = parse($thing, $oui_player_item);
        unset($GLOBALS['oui_player_item']);

        return $out;
    }
}
