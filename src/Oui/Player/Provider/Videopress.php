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
 * Videopress
 *
 * @package Oui\Player
 */

class Videopress extends AbstractOembed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = 'https://videopress.com/embed';
    protected static $iniDims = array(
        'width'      => '560',
        'height'     => '315',
        'ratio'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );

    protected static $mediaPatterns = array(
        'scheme' => '#^https?://videopress.com/(v|embed)/([^\?]+)#i',
        'id'     => '2',
    );
    protected static $iniParams = array(
        'at'       => array(
            'default' => '0',
            'valid'   => 'number',
        ),
        'autoplay' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'loop'     => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
    );

    protected static $endPoint = 'https://public-api.wordpress.com/oembed/?url=';
    protected static $URLBase = 'https://videopress.com/v/';

    /**
     * $data setter
     */

    final protected function setData()
    {
        $url = self::getEndPoint() . $this->getMediaURL() . '&for=' . get_pref('siteurl');

        if (extension_loaded('curl')) {
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

            $json = curl_exec($curl);

            curl_close($curl);
        } else {
            $json = file_get_contents($url);
        }

        if ($json && $this->data = @json_decode($json)) {
            return;
        }

        throw new \Exception('Invalid JSON file.');
    }
}

\Txp::get('\Oui\Player\Videopress');
