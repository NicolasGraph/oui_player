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

    class Player
    {
        protected $plugin;
        protected $providers;
        protected $tags = array(
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
        protected $privs = '1, 2';
        protected $prefs = array(
            'custom_field' => array(
                'widget'  => 'oui_player_custom_fields',
                'default' => 'article_image',
            ),
            'provider' => array(
            ),
        );

        public function __construct()
        {
            $this->plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            $this->providers = callback_event($this->plugin, 'plug_providers', 0, 'Provider');
        }
    }
}
