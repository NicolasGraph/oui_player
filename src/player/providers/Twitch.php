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

    class Twitch extends Provider
    {
        protected $patterns = array(
            'video' => array(
                'scheme' => '#^((http|https):\/\/(www.)?twitch\.tv\/[\S]+\/v\/([0-9]+))$#i',
                'id'     => '4',
            ),
            'channel' => array(
                'scheme' => '#^((http|https):\/\/(www.)?twitch\.tv\/([^\&\?\/]+))$#i',
                'id'     => '4',
            ),
        );
        protected $src = '//player.twitch.tv/';
        protected $params = array(
            'autoplay' => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'muted'    => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'time'     => array(
                'default' => '',
                'valid'   => 'number',
            ),
        );

        /**
         * Get the player code
         */
        public function getPlayer()
        {
            if (preg_match('/([.][a-z]+\/)/', $this->play)) {
                $item = $this->getInfos();
                $id = $item['id'];
                $type = $item['type'];
            } else {
                $id = $this->play;
                $type = preg_match('/[0-9]+/', $this->play) ? 'video' : 'channel';
            }

            if ($item) {
                $item['type'] === 'channel' ?: $item['id'] = 'v' . $item['id'];
                $src = $this->src . '?' . $item['type'] . '=' . $item['id'];
                $params = $this->getParams();

                if (!empty($params)) {
                    $glue[0] = strpos($src, $this->glue[0]) ? $this->glue[1] : $this->glue[0];
                    $src .= $glue[0] . implode($this->glue[1], $params);
                }

                $dims = $this->getSize();
                extract($dims);

                return '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe>';
            }
        }
    }

    if (txpinterface === 'admin') {
        Twitch::getInstance();
    }
}
