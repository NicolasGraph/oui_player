<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Spotify customizable video players in Textpattern CMS.
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
 * Spotify
 *
 * @package Oui\Player
 */

class Spotify extends Oembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//open.spotify.com/embed';
    protected static $srcGlue = array('/', '/', '/');
    protected static $iniDims = array(
        'width'      => '300',
        'height'     => '380',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $mediaPatterns = array(
        'album' => array(
            'scheme' => '#^https?://(open|play)\.spotify\.com/album/(.+)$#i',
            'id'     => '2',
            'prefix' => 'album/'
        ),
        'track' => array(
            'scheme' => '#^https?://(open|play)\.spotify\.com/track/(.+)$#i',
            'id'     => '2',
            'prefix' => 'track/'
        ),
        'artist' => array(
            'scheme' => '#^https?://(open|play)\.spotify\.com/artist/(.+)$#i',
            'id'     => '2',
            'prefix' => 'artist/'
        ),
        'playlist' => array(
            'scheme' => '#^https?://(open|play)\.spotify\.com/playlist/(.+)$#i',
            'id'     => '2',
            'prefix' => 'playlist/'
        ),
    );

    protected static $endPoint = 'https://embed.spotify.com/oembed/?url=';
    protected static $URLBase = 'https://open.spotify.com/';
}

\Txp::get('\Oui\Player\Spotify');
