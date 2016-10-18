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

    abstract class Provider
    {
        public $provider;
        public $play;
        public $latts;

        protected $patterns = array();
        protected $src;
        protected $size = array();
        protected $params = array();
        protected $glue = array('?', '&amp;');

        /**
         * Register callbacks.
         */
        public function __construct()
        {
            // Plug in Oui\Player class
            $this->plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            register_callback(array($this, 'getProvider'), $this->plugin, 'plug_providers', 0);
        }

        /**
         * Return the class name as the provider name.
         */
        public function getProvider()
        {
            return array(substr(strrchr(get_class($this), '\\'), 1));
        }

        public function getPrefs($prefs)
        {
            $merge_prefs = array_merge($this->dims, $this->params);

            foreach ($merge_prefs as $pref => $options) {
                $options['group'] = strtolower(str_replace('\\', '_', get_class($this)));
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            return $prefs;
        }

        /**
         * Get a tag attribute list
         *
         * @param string $tag      The plugin tag
         * @param array  $get_atts The array where attributes are stored provider after provider.
         */
        public function getAtts($tag, $get_atts)
        {
            $atts = array_merge($this->dims, $this->params);

            foreach ($atts as $att => $options) {
                $att = str_replace('-', '_', $att);
                $get_atts[$att] = '';
            }

            return $get_atts;
        }

        /**
         * Get the video provider and the video id from its url
         *
         * @param string $play The item url to play
         */
        public function getItemInfos()
        {
            foreach ($this->patterns as $pattern => $id) {
                if (preg_match($pattern, $this->play, $matches)) {
                    $match = array(
                        'provider' => strtolower(substr(strrchr(get_class($this), '\\'), 1)),
                        'id'       => $matches[$id],
                    );
                } elseif (isset($this->provider)) {
                    // Use the related pref as a default attribute value.
                    $match = array(
                        'provider' => $this->provider,
                        'id'       => $this->play,
                    );
                } else {
                    $match = false;
                }

                return $match;
            }

            return false;
        }

        /**
         * Get the provider player url and its parameters/attributes
         */
        public function getParams()
        {
            $params = array();

            foreach ($this->params as $param => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $att = str_replace('-', '_', $param);
                $value = isset($this->latts[$att]) ? $this->latts[$att] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    // Remove # from the color pref as a color type is used for the pref input.
                    $params[] = $param . '=' . str_replace('#', '', $pref);
                } elseif ($value !== '') {
                    // Remove the # in the color attribute just in case…
                    $params[] = $param . '=' . str_replace('#', '', $value);
                }
            }

            return $params;
        }

        /**
         * Get the provider player url and its parameters/attributes
         */
        public function getSize()
        {
            $dims = array();

            foreach ($this->dims as $dim => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $dim);
                $default = $infos['default'];
                $value = isset($this->latts[$dim]) ? $this->latts[$dim] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    $dims[$dim] = $pref;
                } elseif ($value !== '') {
                    $dims[$dim] = $value;
                } else {
                    $dims[$dim] = $default;
                }
            }

            return $dims;
        }

        /**
         * Build the code to embed.
         *
         * @param string $src         The iframe source.
         * @param array  $used_params The player parameters in use.
         * @param array  $dims        The player dimensions
         */
        public function getPlayer()
        {
            $item = $this->getItemInfos();

            if ($item) {
                $src = $this->src . $item['id'];
                $dims = $this->getSize();
                $params = $this->getParams();

                if (!empty($params)) {
                    $glue = strpos($src, $this->glue[0]) ? $this->glue[1] : $this->glue[0];
                    $src .= $glue . implode('&amp;', $params);
                }

                $width = $dims['width'];
                $height = $dims['height'];
                $ratio = $dims['ratio'];

                if ((!$dims || !$height)) {
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

                $out = '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe>';

                return $out;
            }
        }
    }

}
