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

    class Bandcamp extends Provider
    {
        protected $patterns = array(
            'album' => array(
                'scheme' => '#((http|https):\/\/bandcamp\.com\/(EmbeddedPlayer\/)?(album=\d+)\/?)#i',
                'id'     => 4,
            ),
            'track' => array(
                'scheme' => '#((http|https):\/\/bandcamp\.com\/(EmbeddedPlayer\/)?[\S]+(track=\d+)\/?)#i',
                'id'     => 4,
            ),
        );
        protected $src = '//bandcamp.com/EmbeddedPlayer/';
        protected $glue = array('/', '/');
        protected $dims = array(
            'width'     => array(
                'default' => '350px',
            ),
            'height'    => array(
                'default' => '350px',
            ),
            'ratio'     => array(
                'default' => '',
            ),
        );
        protected $params = array(
            'size'      => array(
                'default' => 'large',
                'force'   => true,
                'valid'   => array('large', 'small'),
            ),
            'artwork'   => array(
                'default' => '',
                'valid'   => array('', 'none', 'big', 'small'),
            ),
            'bgcol'     => array(
                'default' => '#ffffff',
                'valid'   => 'color',
            ),
            'linkcol'   => array(
                'default' => '#0687f5',
                'valid'   => 'color',
            ),
            'tracklist' => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
        );

        /**
         * Get the item URL, provider and ID from the play property.
         */
        public function getInfos()
        {
            $infos = false;

            foreach ($this->patterns as $pattern => $options) {
                if (preg_match($options['scheme'], $this->play, $matches)) {
                    if (!is_array($infos)) {
                        $infos = array(
                            'url'      => $this->play,
                            'provider' => strtolower(substr(strrchr(get_class($this), '\\'), 1)),
                            'id'       => $matches[$options['id']],
                            'type'     => array($pattern),
                        );
                    } else {
                        $infos['id'] .= '/' . $matches[$options['id']];
                        $infos['type'][] = $pattern;
                    }
                }
            }

            return $infos;
        }

        /**
         * Get the player code
         */
        public function getPlayer()
        {
            $item = preg_match('/([.][a-z]+\/)/', $this->play) ? $this->getInfos() : $this->play;
            $id = isset($item['id']) ? $item['id'] : $this->play;

            if ($id) {
                $src = $this->src . $id;
                $params = $this->getParams();

                if (!empty($params)) {
                    $glue[0] = strpos($src, $this->glue[0]) ? $this->glue[1] : $this->glue[0];
                    $src .= $glue[0] . implode($this->glue[1], $params);
                }

                $dims = $this->getSize();
                extract($dims);

                return '<iframe style="border: 0; width:' . $width . '; height:' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe>';
            }
        }
    }

    if (txpinterface === 'admin') {
        Bandcamp::getInstance();
    }
}
