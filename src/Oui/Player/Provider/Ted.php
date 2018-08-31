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

class Ted extends Oembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//embed.ted.com/';
    protected static $srcGlue = array('talks', '/', '/');
    protected static $iniDims = array(
        'width'      => '854',
        'height'     => '',
        'ratio'      => '16:9',
        'responsive' => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'lang' => '',
    );
    protected static $mediaPatterns = array(
        'scheme' => '#^https?://(www.|embed.)?ted\.com/talks/([^\?]+)#i',
        'id'     => '2',
    );

    protected static $endPoint = 'http://www.ted.com/talks/oembed.json?url=';
    protected static $URLBase = 'http://www.ted.com/talks/';

    /**
     * Build the player src value.
     *
     * @return string
     */

    protected function getSrc()
    {
        $media = $this->getMedia();

        if (!$media) {
            trigger_error('Nothing to play');
            return;
        }

        $media = $this->getMediaInfos(true)[$media]['uri'];
        $srcGlue = self::getSrcGlue();
        $src = self::getSrcBase() . $srcGlue[0]; // Stick player URL and ID.

        // Stick defined player parameters.
        $params = $this->getParams();

        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $src .= $srcGlue[1] . $param . $srcGlue[1] . $value; // Stick.
            }
        }

        return $src . $srcGlue[1] . $media;;
    }
}

\Txp::get('\Oui\Player\Ted');
