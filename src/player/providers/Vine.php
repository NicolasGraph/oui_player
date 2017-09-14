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
 * Vine
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Vine extends Provider
    {
        protected static $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https)://(www\.)?vine.co/v/([^&?/]+)#i',
                'id'     => '3'
            ),
        );
        protected static $src = '//vine.co/';
        protected static $script = 'https://platform.vine.co/static/scripts/embed.js';
        protected static $glue = array('v/', '/embed/', '?');
        protected static $dims = array(
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
        protected static $params = array(
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
                if ($param === 'type') {
                    $params[] = $value ?: $pref;
                } elseif ($value === '' && $pref !== $default) {
                    $params[] = $param . '=' . $pref;
                } elseif ($value !== '') {
                    $params[] = $param . '=' . $value;
                }
            }

            return $params;
        }
    }

    if (txpinterface === 'admin') {
        Vine::getInstance();
    }
}
