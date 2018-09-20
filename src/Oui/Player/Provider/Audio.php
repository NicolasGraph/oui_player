<?php

/*
 * This file is part of oui_player,
 * a oui_player v2+ extension to easily create
 * HTML5 customizable video and audio players in Textpattern CMS.
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
 * Video
 *
 * Manages HTML5 <video> player.
 *
 * @package Oui\Player
 */

class Audio extends AbstractEmbed
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $srcBase = '';
    protected static $srcGlue = ' ';
    protected static $iniDims = array(
        'width'      => '',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );
    protected static $iniParams = array(
        'autoplay' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'controls' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'loop'     => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'muted'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'preload'  => array(
            'default' => 'auto',
            'valid'   => array('none', 'metadata', 'auto'),
        ),
        'volume'   => array(
            'default' => '',
            'valid'   => 'number',
        ),
    );
    protected static $mediaPatterns = array(
        'filename' => array(
            'scheme' => '#^((?!(http|https)://(www\.)?)\S+\.(mp3|ogg|oga|wav|aac|flac))$#i',
            'id'     => '1',
        ),
        'url' => array(
            'scheme' => '#^((https?:\/\/(www.)?)\S+\.(mp3|ogg|oga|wav|aac|flac))$#i',
            'id'     => '1',
        ),
    );
    protected static $mediaMimeTypes = array(
        'mp3'  => 'audio/mp3',
        'ogg'  => 'video/ogg',
        'oga'  => 'video/ogg',
        'wav'  => 'video/wave',
        'aac'  => 'audio/aac',
        'flac' => 'audio/flac',
    );

    /**
     * {@inheritdoc}
     */

    public static function getMediaMimeType($extension)
    {
        return static::$mediaMimeTypes[$extension];
    }

    /**
     * {@inheritdoc}
     */

    public function getParams()
    {
        $params = array();

        foreach (self::getIniParams() as $param => $infos) {
            $pref = $this->getPref($param);
            $default = $infos['default'];
            $value = isset($this->config[$param]) ? $this->config[$param] : '';

            // Add attributes values in use or modified prefs values as player parameters.
            if ($value === '' && $pref !== $default) {
                if ($infos['valid'] === array('0', '1')) {
                    $params[$param] = true;
                } else {
                    $params[$param] = $pref;
                }
            } elseif ($value !== '') {
                $validArray = is_array($infos['valid']) ? $infos['valid'] : '';

                if ($validArray && !in_array($value, $validArray)) {
                    trigger_error('Unknown attribute value for "' . $param . '". Valid values are: "' . implode('", "', $validArray) . '".');
                } if ($infos['valid'] === array('0', '1')) {
                    $params[$param] = true;
                } else {
                    $params[$param] =  $value;
                }
            }
        }

        return $params;
    }

    /**
     * Get the player code
     */

    protected function resetSrcGlue($media)
    {
        extract($this->mediaInfos[$media]);
        isset($pattern) ?: $pattern = 'id';

        if ($pattern === 'url') {
            $sources[] = $media;
        } else {
            if ($pattern === 'id') {
                $where = 'id = '.intval($media).' and created <= '.now('created');
            } elseif ($pattern === 'filename') {
                $where = "filename = '".doSlash($media)."' and created <= ".now('created');
            }

            $file = fileDownloadFetchInfo($where);

            if ($file) {
                $this->mediaInfos[$media] = array(
                    'id'  => $file['id'],
                    'uri' => filedownloadurl($file['id'], $file['filename'])
                );
            } else {
                trigger_error('Unknown file to play:"' . $media . '"');
            }
        }
    }

    /**
     * {@inheritdoc}
     */

    public function getHTML()
    {
        if ($sources = $this->getMediaInfos()) {
            $src = array_shift($sources)['uri'];
            $sourcesStr = array();

            foreach ($sources as $source) {
                $sourcesStr[] = '<source src="' . $source['uri'] . '" type="' . self::getMediaMimeType(pathinfo($source['uri'], PATHINFO_EXTENSION)). '">';
            }

            $paramsStr = '';

            foreach ($this->getParams() as $param => $value) {
                $paramsStr .= self::getSrcGlue() . ($value === true ? $param : $param . '=' . $value);
            }

            $dims = $this->getDims();

            extract($dims);

            $style = '';

            if (!empty($width)) {
                is_string($width) ?: $width .= 'px';
                $style = ' style="width:' . $width . '"';
            }

            $player = sprintf(
                '<audio src="%s"%s%s>%s%s</audio>',
                $src,
                $style,
                $paramsStr,
                ($sourcesStr ? n . implode(n, $sourcesStr) : ''),
                n . gtxt(
                    'oui_player_html_player_not_supported',
                    array(
                        '{player}' => '<audio>',
                        '{src}'    => $src,
                        '{file}'   => basename($src),
                    )
                ) . n
            );

            list($wraptag, $class) = $this->getWrap();
            list($label, $labeltag) = $this->getLabel();

            $wraptag ? $player = n . $player . n : '';

            return doLabel($label, $labeltag) . n . doTag($player, $wraptag, $class);
        }
    }
}

\Txp::get('\Oui\Player\Audio');
