<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2017 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Audio
 *
 * Manages HTML5 <audio> player.
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Audio extends Video
    {
        protected static $patterns = array(
            'filename' => array(
                'scheme' => '#^((?!(http|https)://(www\.)?)\S+\.(mp3|ogg|oga|wav))$#i',
                'id'     => '1',
            ),
            'url' => array(
                'scheme' => '#^(((http|https):\/\/(www.)?)\S+\.(mp3|ogg|oga|wav))$#i',
                'id'     => '1',
            ),
        );
        protected static $dims = array();
        protected static $params = array(
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

        /**
         * {@inheritdoc}
         */

        public function getPlayer()
        {
            if ($sources = $this->getSources()) {
                $src = $sources[0];

                unset($sources[0]);

                $params = $this->getParams();

                return sprintf(
                    '<audio src="%s"%s>%s%s</audio>',
                    $src,
                    (empty($params) ? '' : ' ' . implode(static::$glue, $params)),
                    ($sources ? n . '<source src="' . implode('">' . n . '<source src="', $sources) . '">' : ''),
                    n . \gtxt(
                        'oui_player_html_player_not_supported',
                        array(
                            '{player}' => '<audio>',
                            '{src}'    => $src,
                            '{file}'   => basename($src),
                        )
                    ) . n
                );
            }
        }
    }

    if (txpinterface === 'admin') {
        Audio::getInstance();
    }
}
