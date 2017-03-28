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

    class Vine extends Provider
    {
        protected $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https):\/\/(www.)?vine.co\/v\/([^\&\?\/]+)#i',
                'id'     => '3'
            ),
        );
        protected $src = '//vine.co/v/';
        protected $append = '<script src="https://platform.vine.co/static/scripts/embed.js"></script>';
        protected $glue = array('/embed/', '?');
        protected $dims = array(
            'width'    => array(
                'default' => '600',
            ),
            'height'   => array(
                'default' => '600',
            ),
            'ratio'    => array(
                'default' => '',
            ),
        );
        protected $params = array(
            'type' => array(
                'default' => 'simple',
                'valid'   => array('simple', 'postcard'),
            ),
            'audio' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
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
                if ($param === 'type') {
                    $params[] = $value ?: $pref;
                } elseif ($value === '' && $pref !== $default) {
                    // Remove # from the color pref as a color type is used for the pref input.
                    $params[] = $param . '=' . str_replace('#', '', $pref);
                } elseif ($value !== '') {
                    // Remove the # in the color attribute just in case…
                    $params[] = $param . '=' . str_replace('#', '', $value);
                }
            }

            return $params;
        }
    }

    if (txpinterface === 'admin') {
        Vine::getInstance();
    }
}
