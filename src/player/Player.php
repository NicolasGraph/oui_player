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
  * Player
  *
  * Plugin base class.
  *
  * @package Oui\Player
  */

namespace Oui\Player {

    class Player
    {
        /**
         * The plugin name.
         *
         * @var string
         */

        protected static $plugin;

        /**
         * The plugged providers.
         *
         * @var array
         */

        protected static $providers;

        /**
         * Multidimensional associative array of plugin tags,
         * attributes and attibute values.
         *
         * @var array
         */

        protected static $tags = array(
            'oui_player' => array(
                'class' => array(
                    'default' => '',
                ),
                'label' => array(
                    'default' => '',
                ),
                'labeltag' => array(
                    'default' => '',
                ),
                'provider' => array(
                    'default' => '',
                ),
                'play' => array(
                    'default' => '',
                ),
                'wraptag' => array(
                    'default' => '',
                ),
            ),
            'oui_if_player' => array(
                'play' => array(
                    'default' => '',
                ),
                'provider' => array(
                    'default' => '',
                ),
            ),
        );

        /**
         * Plugin prefs related privilege levels.
         *
         * @var string
         */

        protected static $privs = '1, 2';

        /**
         * Multidimensional associative array
         * of plugin general prefs.
         *
         * @var array.
         */

        protected static $prefs = array(
            'custom_field' => array(
                'widget'  => 'Oui\Player\Admin::customFields',
                'default' => 'article_image',
            ),
            'provider' => array(),
            'providers' => array(),
        );
    }
}
