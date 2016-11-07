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

    class Youtube extends Provider
    {
        protected $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https):\/\/(www.)?(youtube\.com\/((watch\?v=)|(embed\/)|(v\/))|youtu\.be\/)([^\&\?\/]+)$#i',
                'id'     => '8'
            ),
        );
        protected $src = '//www.youtube-nocookie.com/embed/';
        protected $params = array(
            'autohide'       => array(
                'default' => '2',
                'valid'   => array('0', '1', '2'),
            ),
            'autoplay'       => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'cc_load_policy' => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'color'          => array(
                'default' => 'red',
                'valid'   => array('red', 'white'),
            ),
            'controls'       => array(
                'default' => '1',
                'valid'   => array('0', '1', '2'),
            ),
            'disablekb'      => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'end'            => array(
                'default' => '',
                'valid'   => 'number',
            ),
            'fs'             => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'hl'             => array(
                'default' => '',
            ),
            'iv_load_policy' => array(
                'default' => '1',
                'valid'   => array('1', '3'),
            ),
            'loop'           => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'modestbranding' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'playlist'    => array(
                'default' => '',
            ),
            'playsinline'    => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'rel'            => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'start'          => array(
                'default' => '0',
                'valid'   => 'number',
            ),
            'showinfo'       => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'theme'          => array(
                'default' => 'dark',
                'valid'   => array('dark', 'light'),
            ),
        );
    }

    new Youtube;
}
