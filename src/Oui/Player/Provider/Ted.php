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

class Ted extends AbstractOembed
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
        'lang' => array(
            'default' => '',
            'valid'   => array('', 'aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av', 'ay', 'az', 'ba', 'be', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo', 'br', 'bs', 'ca', 'ce', 'ch', 'co', 'cr', 'cs', 'cu', 'cv', 'cy', 'da', 'de', 'dv', 'dz', 'ee', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'ff', 'fi', 'fj', 'fo', 'fr', 'fy', 'ga', 'gd', 'gl', 'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr', 'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik', 'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj', 'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ku', 'kv', 'kw', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv', 'mg', 'mh', 'mi', 'mk', 'ml', 'mn', 'mo', 'mr', 'ms', 'mt', 'my', 'na', 'nb', 'nd', 'ne', 'ng', 'nl', 'nn', 'no', 'nr', 'nv', 'ny', 'oc', 'oj', 'om', 'or', 'os', 'pa', 'pi', 'pl', 'ps', 'pt', 'qu', 'rc', 'rm', 'rn', 'ro', 'ru', 'rw', 'sa', 'sc', 'sd', 'se', 'sg', 'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr', 'ss', 'st', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'ti', 'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'wo', 'xh', 'yi', 'yo', 'za', 'zh', 'zu'),
        ),
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
