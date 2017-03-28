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

    class Video extends Provider
    {
        protected $patterns = array(
            'file' => array(
                'scheme' => '#^((?!(http|https):\/\/(www.)?)\S+\.(mp4|ogv|webm))$#i',
                'id'     => '1',
            ),
            'url' => array(
                'scheme' => '#^(((http|https):\/\/(www.)?)\S+\.(mp4|ogv|webm))$#i',
                'id'     => '1',
            ),
        );
        protected $src = '';
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
         * Get player parameters in in use.
         */
        public function getParams()
        {
            $params = array();

            foreach ($this->params as $param => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $value = isset($this->config[$param]) ? $this->config[$param] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    // Remove # from the color pref as a color type is used for the pref input.
                    if ($infos['valid'] === array('0', '1')) {
                        $params[] = $param;
                    } else {
                        $params[] = $param . '="' . $pref . '"';
                    }
                } elseif ($value !== '') {
                    // Remove the # in the color attribute just in case…
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
        public function getPlayer()
        {
            if (!empty($this->play)) {
                $item = $this->getInfos();
                $item ?: $item = array(
                    'id'   => $this->play,
                    'type' => $this->type,
                );
            }

            if ($item) {
                if ($item['type'] === 'file') {
                    $src = substr($GLOBALS['file_base_path'], strlen($_SERVER['DOCUMENT_ROOT'])) . '/' . $item['id'];
                } else {
                    $src = $item['id'];
                }

                $params = $this->getParams();

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

                return '<video width="' . $width . '" height="' . $height . '" src="' . $src . '"' . (empty($params) ? '' : ' ' . implode(' ', $params)) . '>' . \gtxt('oui_player_video_unavailable') . '</video>' . $this->append;
            }
        }
    }

    if (txpinterface === 'admin') {
        Video::getInstance();
    }
}
