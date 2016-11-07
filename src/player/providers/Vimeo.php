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

    class Vimeo extends Provider
    {
        protected $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https):\/\/((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)$#i',
                'id'     => '5',
            ),
        );
        protected $src = '//player.vimeo.com/video/';
        protected $params = array(
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
                'default' => '#00adef',
                'valid'   => 'color',
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

    new Vimeo;
}
