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

class Oui_Player_Bandcamp extends Oui_Player_Provider
{
    protected $provider = 'Bandcamp';
    protected $patterns = array('url' => '#^((http|https):\/\/[\S]+\/album\/([\S]+))#i' => '1');
    protected $src = '//bandcamp.com/EmbeddedPlayer/album=';
    protected $glue = array('/', '/');
    protected $params = array(
        'artwork' => array(
            'default' => '',
        ),
        'bgcol'       => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#ffffff',
        ),
        'height' => array(
            'default' => '470',
        ),
        'linkcol'       => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#0687f5',
        ),
        'minimal' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'track' => array(
            'default' => '',
        ),
        'tracklist' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'ratio' => array(
            'default' => '',
        ),
        'size' => array(
            'default' => 'large',
            'valid' => array('small', 'medium', 'large'),
        ),
        'width' => array(
            'default' => '350',
        ),
    );
}

$instance = Oui_Player_Bandcamp::getInstance();
$instance->plugProvider();
