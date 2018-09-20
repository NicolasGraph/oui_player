<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Vimeo customizable video players in Textpattern CMS.
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
 * Vimeo
 *
 * @package Oui\Player
 */

class Vimeo extends AbstractOembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//player.vimeo.com/video';
    protected static $iniDims = array(
        'width'      => '640',
        'height'     => '360',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'api'         => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'autopause'   => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'autoplay'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        // 'background'  => array(
        //     'default' => '0',
        //     'valid'   => array('0', '1'),
        // ),
        'byline'      => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'color'       => array(
            'default' => '#00adef',
            'valid'   => 'color',
        ),
        'dnt'         => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        // 'fun'         => array(
        //     'default' => '1',
        //     'valid'   => array('0', '1'),
        // ),
        'loop'        => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'muted'       => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'playsinline' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'portrait'    => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        // 'quality'     => array(
        //     'default' => 'auto',
        //     'valid'   => array('auto', '360p', '540p', '720p', '1080p', '2k', '4k'),
        // ),
        // 'speed'       => array(
        //     'default' => '0',
        //     'valid'   => array('0', '1'),
        // ),
        'title'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'transparent' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        // '#t'          => '0m' // TODO Do not uncomment for Plus account or higher.
    );
    protected static $mediaPatterns = array(
        'scheme' => '#^https?://((player\.vimeo\.com/video)|(vimeo\.com))/(\d+)$#i',
        'id'     => '4',
    );

    protected static $endPoint = 'https://vimeo.com/api/oembed.json?url=';
    protected static $URLBase = 'http://vimeo.com/';
}

\Txp::get('\Oui\Player\Vimeo');
