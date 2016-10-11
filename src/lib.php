<?php

/*
 * oui_player - Easily embed customized players..
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Plugin pref functions
 */

function oui_player_pref($name, $val)
{
    $class = Oui_Player::getInstance();
    $widget = $class->prefSelect($name, $val);
    return $widget;
}

function oui_player_custom_fields($name, $val)
{
    $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

    if ($custom_fields) {
        $vals = array();
        foreach ($custom_fields as $row) {
            $vals[$row['val']] = $row['val'];
        }
        $vals['article_image'] = gtxt('article_image');
        $vals['excerpt'] = gtxt('excerpt');
        return selectInput($name, $vals, $val);
    }
    return gtxt('no_custom_fields_recorded');
}

function oui_player_pref_color($name, $val)
{
    return fInput('color', $name, $val);
}

function oui_player_truefalseradio($field, $checked = '', $tabindex = 0, $id = '') {
    $vals = array(
        'false' => gTxt('no'),
        'true' => gTxt('yes'),
    );

    return radioSet($vals, $field, $checked, $tabindex, $id);
}
