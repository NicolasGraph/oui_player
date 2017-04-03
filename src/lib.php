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

/**
 * Plugin pref functions
 */

function oui_player_pref_widget($name, $val)
{
    $class = 'Oui\Player\Admin';
    $obj = $class::getInstance();
    $widget = $obj->prefFunction($name, $val);
    return $widget;
}

function oui_player_custom_fields($name, $val)
{
    $vals = array();
    $vals['article_image'] = gtxt('article_image');
    $vals['excerpt'] = gtxt('excerpt');

    $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

    if ($custom_fields) {
        foreach ($custom_fields as $row) {
            $vals[$row['val']] = $row['val'];
        }
    }

    return selectInput($name, $vals, $val);
}

function oui_player_truefalseradio($field, $checked = '', $tabindex = 0, $id = '')
{
    $vals = array(
        'false' => gTxt('no'),
        'true' => gTxt('yes'),
    );

    return radioSet($vals, $field, $checked, $tabindex, $id);
}
