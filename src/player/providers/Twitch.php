<?php

/*
 * oui_player - An extendable plugin to easily embed iframe
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * This file is part of oui_player.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see https://www.gnu.org/licenses/.
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
            if (!empty($this->play)) {
                $item = $this->getInfos();
                $item ?: $item = array(
                    'id'   => $this->play,
                    'type' => preg_match('/[0-9]+/', $this->play) ? 'video' : 'channel',
                );
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

                if (!$dims || !$height) {
                    // Work out the aspect ratio.
                    preg_match("/(\d+):(\d+)/", $ratio, $matches);
                    if ($matches[0] && $matches[1]!=0 && $matches[2]!=0) {
                        $aspect = $matches[1] / $matches[2];
                    } else {
                        $aspect = 1.778;
                    }

                    // Calcuate the new width/height.
                    if ($width) {
                        $height = $width / $aspect;
                    } elseif ($height) {
                        $width = $height * $aspect;
                    }
                }

                return '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe>' . $this->append;
            }
        }
    }

    if (txpinterface === 'admin') {
        Twitch::getInstance();
    }
}
