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

    class Dailymotion extends Provider
    {
        protected $patterns = array('#^(http|https):\/\/(www.)?(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '8');
        protected $src = '//www.dailymotion.com/embed/video/';
        protected $params = array(
            'autoplay'             => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'controls'             => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'endscreen-enable'     => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'mute'                 => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'quality'              => array(
                'default' => 'auto',
                'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
            ),
            'sharing-enable'       => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'start'                => array(
                'default' => '0',
                'valid'   => 'number'
            ),
            'subtitles-default'    => array(
                'default' => '',
            ),
            'ui-highlight'         => array(
                'default' => '#ffcc33',
                'valid'   => 'color',
            ),
            'ui-logo'              => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'ui-theme'             => array(
                'default' => 'dark',
                'valid'   => array('dark', 'light'),
            ),
            'ui-start-screen-info' => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
        );
    }

    new Dailymotion;
}
