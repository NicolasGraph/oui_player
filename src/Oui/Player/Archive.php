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

namespace Oui\Player {

    class Archive extends Provider
    {
        protected $patterns = array('#^(http|https):\/\/(www.)?archive\.org\/(details|embed)\/([^\&\?\/]+)$#i' => '4');
        protected $src = '//archive.org/embed/';
        protected $params = array(
            'width'       => array(
                'default' => '640',
            ),
            'height'      => array(
                'default' => '',
            ),
            'ratio'       => array(
                'default' => '16:9',
            ),
            'autoplay'    => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'list_height' => array(
                'default' => '',
            ),
            'playlist'    => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'poster'      => array(
                'default' => '',
            ),
        );
    }

    $instance = Archive::getInstance();
    $instance->plugProvider();
}
