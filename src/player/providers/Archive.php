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

    class Archive extends Provider
    {
        protected $patterns = array('#^(http|https):\/\/(www.)?archive\.org\/(details|embed)\/([^\&\?\/]+)$#i' => '4');
        protected $src = '//archive.org/embed/';
        protected $glue = array('&amp;', '&amp;');
        protected $dims = array(
            'width'    => array(
                'default' => '640',
            ),
            'height'   => array(
                'default' => '480',
            ),
            'ratio'    => array(
                'default' => '',
            ),
        );
        protected $params = array(
            'autoplay' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'playlist' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'poster'   => array(
                'default' => '',
                'valid'   => 'url',
            ),
        );
    }

    new Archive;
}
