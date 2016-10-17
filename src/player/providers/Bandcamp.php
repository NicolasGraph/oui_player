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

class Oui_Player_Bandcamp extends Oui_Player_Provider
{
    protected $patterns = array('url' => '#^((http|https):\/\/[\S]+\/album\/([\S]+))#i' => '1');
    protected $src = '//bandcamp.com/EmbeddedPlayer/album=';
    protected $glue = array('/', '/');
    protected $size = array(
        'width'     => array(
            'default' => '350',
        ),
        'height'    => array(
            'default' => '470',
        ),
        'ratio'     => array(
            'default' => '',
        ),
    );
    protected $params = array(
        'artwork'   => array(
            'default' => '',
        ),
        'bgcol'     => array(
            'default' => '#ffffff',
            'valid'   => 'color',
        ),
        'linkcol'   => array(
            'default' => '#0687f5',
            'valid'   => 'color',
        ),
        'minimal'   => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'track'     => array(
            'default' => '',
        ),
        'tracklist' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'size'      => array(
            'default' => 'large',
            'valid'   => array('small', 'medium', 'large'),
        ),
    );
}

$instance = Oui_Player_Bandcamp::getInstance();
$instance->plugProvider();
