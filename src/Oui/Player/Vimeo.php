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

class Oui_Player_Vimeo extends Oui_Player_Provider
{
    protected $provider = 'Vimeo';
    protected $patterns = array('#^(http|https):\/\/((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)$#i' => '5');
    protected $src = '//player.vimeo.com/video/';
    protected $params = array(
        'width' => array(
            'default' => '640',
        ),
        'height' => array(
            'default' => '',
        ),
        'ratio' => array(
            'default' => '16:9',
        ),
        'autopause' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'autoplay'  => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'badge'     => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'byline'    => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'color'     => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#00adef',
        ),
        'loop'      => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'portrait'  => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'title'     => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
    );
}

$instance = Oui_Player_Vimeo::getInstance();
$instance->plugProvider();
