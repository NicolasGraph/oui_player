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

class Video extends Audio
{
    protected static $provider;
    protected static $prefsEvent;
    protected static $prefs;

    protected static $iniDims = array(
        'width'      => '640',
        'height'     => '',
        'ratio'      => '16:9',
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
        'muted'     => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'poster'  => array(
            'default' => '',
            'valid'   => 'url',
        ),
        'preload'  => array(
            'default' => 'auto',
            'valid'   => array('none', 'metadata', 'auto'),
        ),
    );
    protected static $mediaPatterns = array(
        'filename' => array(
            'scheme' => '#^((?!(http|https)://(www\.)?)\S+\.(mp4|ogv|webm))$#i',
            'id'     => '1',
        ),
        'url' => array(
            'scheme' => '#^((https?://(www\.)?)\S+\.(mp4|ogv|webm))$#i',
            'id'     => '1',
        ),
    );
    protected static $mediaMimeTypes = array(
        'mp4'  => 'video/mp4',
        'ogv'  => 'video/ogg',
        'webm' => 'video/webm',
    );

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
            $wrapStyle = '';
            $style = '';

            extract($dims);

            if ($responsive) {
                $wrapStyle .= ' style="position: relative; padding-bottom:' . $height . '; height: 0; overflow: hidden"';
                $style .= ' style="position: absolute; top: 0; left: 0; width: 100%; height: 100% ';
                $width = $height = false;
            } else {
                foreach (array('width', 'height') as $dim) {
                    if (is_string($$dim)) {
                        $style = ' style="' . $dim . ':' . $$dim . '';
                        $$dim = false;
                    }
                }
            }

            $style ? $style .= '"' : '';

            $player = sprintf(
                '<video src="%s"%s%s%s%s>%s%s</video>',
                $src,
                !$width ? '' : ' width="' . $width . '"',
                !$height ? '' : ' height="' . $height . '"',
                $style,
                $paramsStr,
                ($sourcesStr ? n . implode(n, $sourcesStr) : ''),
                n . gtxt(
                    'oui_player_html_player_not_supported',
                    array(
                        '{player}' => '<video>',
                        '{src}'    => $src,
                        '{file}'   => basename($src),
                    )
                ) . n
            );

            list($wraptag, $class) = $this->getWrap();
            list($label, $labeltag) = $this->getLabel();

            $wrapStyle && !$wraptag ? $wraptag = 'div' : '';
            $wraptag ? $player = n . $player . n : '';

            return doLabel($label, $labeltag) . n . doTag($player, $wraptag, $class, $wrapStyle);
        }
    }
}

\Txp::get('\Oui\Player\Video');
