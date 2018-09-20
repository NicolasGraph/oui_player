<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * Vine customizable video players in Textpattern CMS.
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
 * Vine
 *
 * @package Oui\Player
 */

class Vine extends AbstractEmbed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//vine.co/v';
    protected static $srcGlue = array('/', '/embed/', '?');
    protected static $script = 'https://platform.vine.co/static/scripts/embed.js';
    protected static $iniDims = array(
        'width'      => '600',
        'height'     => '600',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'type' => array(
            'default'    => 'simple',
            'valid'      => array('simple', 'postcard'),
            'force'      => true,
        ),
        'audio' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
    );
    protected static $mediaPatterns = array(
        'scheme' => '#^https?://(www\.)?vine.co/(v/)?([^&?/]+)#i',
        'id'     => '3'
    );

    /**
     * {@inheritdoc}
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
        $src = self::getSrcBase() . $srcGlue[0] . $media . $srcGlue[1]; // Stick player URL and ID.

        // Stick defined player parameters.
        $params = $this->getParams();

        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $src .= $param === 'type' ? $value : $srcGlue[2] . $param . '=' . $value; // Stick.
            }
        }

        return $src;
    }
}

\Txp::get('\Oui\Player\Vine');
