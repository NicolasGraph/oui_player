<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Giphy customizable players in Textpattern CMS.
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

class Giphy extends AbstractOembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//giphy.com/embed';
    protected static $srcGlue = array('/', '', '');
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
        'scheme' => '#^https?://giphy\.com/(embed/|gifs/[\S]+[-])([0-9a-zA-Z]+)$#i',
        'id'     => '2',
    );

    protected static $endPoint = 'https://giphy.com/services/oembed?url=';
    protected static $URLBase = 'https://giphy.com/gifs/';
}

\Txp::get('\Oui\Player\Giphy');
