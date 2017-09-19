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
 * Video
 *
 * Manages HTML5 <video> player.
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Video extends Provider
    {
        protected static $patterns = array(
            'filename' => array(
                'scheme' => '#^((?!(http|https)://(www\.)?)\S+\.(mp4|ogv|webm))$#i',
                'id'     => '1',
            ),
            'url' => array(
                'scheme' => '#^(((http|https)://(www\.)?)\S+\.(mp4|ogv|webm))$#i',
                'id'     => '1',
            ),
        );
        protected static $src = '';
        protected static $glue = ' ';
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

        /**
         * {@inheritdoc}
         */

        public function getParams()
        {
            $params = array();

            foreach (static::$params as $param => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $value = isset($this->config[$param]) ? $this->config[$param] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    if ($infos['valid'] === array('0', '1')) {
                        $params[] = $param;
                    } else {
                        $params[] = $param . '="' . $pref . '"';
                    }
                } elseif ($value !== '') {
                    if ($infos['valid'] === array('0', '1')) {
                        $params[] = $param;
                    } else {
                        $params[] = $param . '="' . $value . '"';
                    }
                }
            }

            return $params;
        }

        /**
         * Get the player code
         */

        public function getSources()
        {
            $infos = $this->getInfos();

            $sources = array();

            foreach ($infos as $play => $info) {
                extract($info);

                if ($type === 'url') {
                    $sources[] = $play;
                } else {
                    if ($type === 'id') {
                        $file = \fileDownloadFetchInfo(
                            'id = '.intval($play).' and created <= '.now('created')
                        );
                    } elseif ($type === 'filename') {
                        $file = \fileDownloadFetchInfo(
                            "filename = '".\doSlash($play)."' and created <= ".now('created')
                        );
                    }

                    $sources[] = \filedownloadurl($file['id'], $file['filename']);
                }
            }

            return $sources;
        }

        /**
         * {@inheritdoc}
         */

        public function getPlayer()
        {
            if ($sources = $this->getSources()) {
                $src = $sources[0];

                unset($sources[0]);

                $params = $this->getParams();
                $dims = $this->getSize();

                extract($dims);

                return sprintf(
                    '<video width="%s" height="%s" src="%s"%s>%s%s</video>',
                    $width,
                    $height,
                    $src,
                    (empty($params) ? '' : ' ' . implode(static::$glue, $params)),
                    ($sources ? n . '<source src="' . implode('">' . n . '<source src="', $sources) . '">' : ''),
                    n . \gtxt(
                        'oui_player_html_player_not_supported',
                        array(
                            '{player}' => '<video>',
                            '{src}'    => $src,
                            '{file}'   => basename($src),
                        )
                    ) . n
                );
            }
        }
    }

    if (txpinterface === 'admin') {
        Video::getInstance();
    }
}
