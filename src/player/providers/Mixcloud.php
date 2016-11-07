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

    class Mixcloud extends Provider
    {
        protected $patterns = array(
            'audio' => array(
                'scheme' => '#^((http|https):\/\/(www.)?mixcloud.com\/[\S]+)$#i',
                'id'     => '1',
            ),
        );
        protected $src = '//www.mixcloud.com/widget/iframe/?feed=';
        protected $dims = array(
            'width'        => array(
                'default' => '100%',
            ),
            'height'       => array(
                'default' => '400',
            ),
            'ratio'        => array(
                'default' => '',
            ),
        );
        protected $params = array(
            'autoplay'     => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'light'        => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'hide_artwork' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'hide_cover'   => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'mini'         => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
        );
    }

    new Mixcloud;
}
