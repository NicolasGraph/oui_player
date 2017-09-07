<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Oui\Player {

    class Audio extends Video
    {
        protected $patterns = array(
            'filename' => array(
                'scheme' => '#^((?!(http|https)://(www\.)?)\S+\.(mp3|m4a|ogg|oga|webma|wav))$#i',
                'id'     => '1',
            ),
            'url' => array(
                'scheme' => '#^(((http|https):\/\/(www.)?)\S+\.(mp3|m4a|ogg|oga|webma|wav))$#i',
                'id'     => '1',
            ),
        );
        protected $dims = array();
        protected $params = array(
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
         * Get the player code
         */
        public function getPlayer()
        {
            if (!$this->infos) {
                if (preg_match('/([.][a-z]+\/)/', $this->play)) {
                    $this->infos = $this->getInfos();
                } else {
                    $this->infos = array();

                    foreach (explode(', ', $this->play) as $play) {
                        $this->infos[$play] = array(
                            'play' => $play,
                            'type' => 'id',
                        );
                    }
                }
            }

            $sources = array();

            foreach ($this->infos as $play => $infos) {
                extract($infos);
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

            if ($sources) {
                $src = $sources[0];
                unset($sources[0]);

                $params = $this->getParams();

                return sprintf(
                    '<video src="%s"%s>%s%s</video>',
                    $src,
                    (empty($params) ? '' : ' ' . implode($this->glue, $params)),
                    ($sources ? n . '<source src="' . implode('">' . n . '<source src="', $sources) . '">' : ''),
                    n . \gtxt('html_player_not_supported', array('{type}' => '<audio>')) . n
                );
            }
        }
    }

    if (txpinterface === 'admin') {
        Audio::getInstance();
    }
}
