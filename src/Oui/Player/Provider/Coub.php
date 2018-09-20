<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Ted customizable video players in Textpattern CMS.
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
 * Coub
 *
 * @package Oui\Player
 */

class Coub extends AbstractOembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//coub.com/embed';
    protected static $iniDims = array(
        'width'      => '480',
        'height'     => '480',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );

    protected static $mediaPatterns = array(
        'scheme' => '#^https?://coub.com/(view|embed)/([^\?]+)#i',
        'id'     => '2',
    );
    protected static $iniParams = array(
        'autostart'   => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'muted'       => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'startWithHD' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );

    protected static $endPoint = 'http://coub.com/api/oembed.json?url=';
    protected static $URLBase = 'https://coub.com/view/';
}

\Txp::get('\Oui\Player\Coub');
