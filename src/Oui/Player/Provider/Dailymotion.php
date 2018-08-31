<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Dailymotion customizable video players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * Dailymotion
 *
 * @package Oui\Player
 */

class Dailymotion extends Oembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//www.dailymotion.com/embed/video';
    protected static $iniDims = array(
        'width'      => '480',
        'height'     => '270',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'api'                  => array(
            'default' => 'false',
            'valid'   => array('false', 'postMessage', 'location', '1'),
        ),
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
        'origin'               => '',
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
    protected static $mediaPatterns = array(
        'scheme' => '#^https?://(www\.)?(dailymotion\.com/((embed/video)|(video))|(dai\.ly?))/([A-Za-z0-9]+)#i',
        'id'     => '7',
    );

    protected static $endPoint = 'http://www.dailymotion.com/services/oembed?url=';
    protected static $URLBase = 'https://www.dailymotion.com/video/';

}

\Txp::get('\Oui\Player\Dailymotion');
