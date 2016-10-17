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
        protected $patterns = array('#^((http|https):\/\/(www.)?twitch\.tv\/[\S]+\/(v\/[0-9]+))$#i' => '4');
        protected $src = '//player.twitch.tv/?video=';
        protected $dims = array(
            'width'    => array(
                'default' => '640',
            ),
            'height'   => array(
                'default' => '',
            ),
            'ratio'    => array(
                'default' => '16:9',
            ),
        );
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
         * Get the video provider and the video id from its url
         *
         * @param string $video The video url
         */
        public function getItemInfos()
        {

            foreach ($this->patterns as $pattern => $id) {
                if (preg_match($pattern, $this->play, $matches)) {
                    $match = array(
                        'provider' => strtolower(substr(strrchr(get_class($this), '\\'), 1)),
                        'id'       => str_replace('/', '', $matches[$id]),
                    );

                    return $match;
                }
            }

            return false;
        }
    }

    new Twitch;
}
