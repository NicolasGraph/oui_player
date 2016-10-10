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

class Oui_Player_Soundcloud extends Oui_Player_Provider
{
    protected $provider = 'Soundcloud';
    protected $patterns = array('#((http|https):\/\/(api.)?soundcloud\.com\/[\S]+)#i' => '1');
    protected $src = '//w.soundcloud.com/player/?url=';
    protected $params = array(
        'width' => array(
            'default' => '100%',
        ),
        'height' => array(
            'default' => '166',
        ),
        'ratio' => array(
            'default' => '',
        ),
        'auto_play' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'buying' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'color'       => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#ff8800',
        ),
        'download' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'hide_related' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'sharing' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_artwork' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_comments' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_playcount' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_reposts' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_user' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'single_active' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'start_track' => array(
            'default' => '0',
        ),
        'theme_color' => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#ff3300',
        ),
        'visual' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
    );
}

$instance = Oui_Player_Soundcloud::getInstance();
$instance->plugProvider();
