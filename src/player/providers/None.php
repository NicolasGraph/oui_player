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

    class None extends Provider
    {
        protected $patterns = array(
            'audio' => array(
                'scheme' => '#^(\S+(.mp3)$)$#i',
                'id'     => '1',
            ),
            'video' => array(
                'scheme' => '#^(\S+(.mp4)$)$#i',
                'id'     => '1',
            ),
        );
        protected $src = '';
        protected $params = array(
            'autoplay'  => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'buffered'     => array(
                'default' => '',
            ),
            'controls'    => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'crossorigns'     => array(
                'default' => '',
                'valid'   => array('', 'anonymous', 'use-credentials'),
            ),
            'loop'      => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'muted'  => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'played'     => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'preload'     => array(
                'default' => '',
                'valid'   => array('', 'none', 'metadata', 'auto'),
            ),
            'poster'     => array(
                'default' => '',
                'valid'   => 'url',
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
                $att = str_replace('-', '_', $param);
                $value = isset($this->config[$att]) ? $this->config[$att] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    // Remove # from the color pref as a color type is used for the pref input.
                    if ($infos['valid'] === array('0', '1')) {
                        $params[] = $param;
                    } else {
                        $params[] = $param . '=' . str_replace('#', '', $pref);
                    }
                } elseif ($value !== '') {
                    // Remove the # in the color attribute just in case…
                    if ($infos['valid'] === array('0', '1')) {
                        $params[] = $param;
                    } else {
                        $params[] = $param . '=' . str_replace('#', '', $value);
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
                $src = $this->src . $item['id'];
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

                return '<' . $item['type'] . ' width="' . $width . '" height="' . $height . '" src="' . $src . '"' . (empty($params) ?: ' ' . implode(' ', $params)) . '></' . $item['type'] . '>' . $this->append;
            }
        }
    }

    new None;
}
