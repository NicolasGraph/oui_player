<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily embed
 * YouTube customizable video players in Textpattern CMS.
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
 * Youtube
 *
 * @package Oui\Player
 */

class Youtube extends Oembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '//www.youtube-nocookie.com/embed';
    protected static $iniDims = array(
        'width'      => '560',
        'height'     => '315',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'autohide'       => array(
            'default' => '2',
            'valid'   => array('0', '1', '2'),
        ),
        'autoplay'       => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'cc_load_policy' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'color'          => array(
            'default' => 'red',
            'valid'   => array('red', 'white'),
        ),
        'controls'       => array(
            'default' => '1',
            'valid'   => array('0', '1', '2'),
        ),
        'disablekb'      => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'enablejsapi'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'end'            => array(
            'default' => '',
            'valid'   => 'number',
        ),
        'fs'             => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'hl'             => '',
        'iv_load_policy' => array(
            'default' => '1',
            'valid'   => array('1', '3'),
        ),
        'listType'       => array(
            'default' => '',
            'valid'   => array('playlist', 'search', 'user_uploads'),
        ),
        'loop'           => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'modestbranding' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'origin'         => array(
            'default' => '',
            'valid'   => 'url',
        ),
        'playlist'    => '',
        'playsinline'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'rel'            => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'start'          => array(
            'default' => '0',
            'valid'   => 'number',
        ),
        'showinfo'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'theme'          => array(
            'default' => 'dark',
            'valid'   => array('dark', 'light'),
        ),
    );
    protected static $mediaPatterns = array(
        'video' => array(
            'scheme' => '#^https?://(www\.)?(youtube\.com/(watch\?v=|embed/|v/)|youtu\.be/)(([^&?/]+)?)#i',
            'id'     => '4',
            'glue'   => '&amp;',
        ),
        'list'  => array(
            'scheme' => '#^https?://(www\.)?(youtube\.com/(watch\?v=|embed/|v/)|youtu\.be/)[\S]+list=([^&?/]+)?#i',
            'id'     => '4',
            'prefix' => 'list=',
        ),
    );

    protected static $endPoint = 'https://www.youtube.com/oembed?format=json&amp;url=';
    protected static $URLBase = 'https://www.youtube.com/watch?v=';

    protected function resetSrcGlue($media)
    {
        if (isset($this->mediaInfos[$media]['pattern'])) {
            self::setSrcGlue(0, $this->mediaInfos[$media]['pattern'] === 'list' ? '?' : '/');
        }
    }
}

\Txp::get('\Oui\Player\Youtube');
