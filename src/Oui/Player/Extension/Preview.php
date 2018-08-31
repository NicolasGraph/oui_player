<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * Preview
 *
 * Player preview extension for the plugin related article field.
 *
 * @package Oui\Player
 */

class Preview
{
    /**
     * Preview related input name, id and pluggabke_ui() step.
     *
     * @var array
     */

    protected $input;

    /**
     * Player class
     *
     * @var string
     */

    protected $class;

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->setInput();

        register_callback(array($this, 'render'), 'article_ui', $this->getInput('step'));
    }

    /**
     * Input setter
     */

    public function setInput()
    {
        $fieldCol = Player::getPref('custom_field');

        // Find the $mediaField related ID.
        if ($fieldCol === 'article_image') {
            $this->input['name'] = 'Image';
            $this->input['step'] = $fieldCol;
        } else {
            $this->input['name'] = str_replace('_set', '', safe_field('name', 'txp_prefs', 'val LIKE "' . $fieldCol . '"'));
            $this->input['step'] = 'custom_fields';
        }

        $pluginName = Player::getPlugin();
        $this->input['id'] = str_replace('_', '-', $this->getInput('name'));
        $this->class = $this->getInput('id') . '-' . str_replace('_', '-', $pluginName);
    }

    /**
     * $input item getter
     *
     * @param string $info 'name', 'id' or 'step';
     * @return string
     */

    protected function getInput($info)
    {
        return $this->input[$info];
    }

    /**
     * $class getter
     *
     * @return string
     */

    protected function getClass()
    {
        return $this->class;
    }

    /**
     * Add a responsive player preview near to the media related field.
     *
     * @param  string $evt  Textpattern event (panel)
     * @param  string $stp  Textpattern step (action)
     * @param  string $data Original markup
     * @param  array  $rs   Accompanyng record set
     * @return string       HTML
     */

    public function render($evt, $stp, $data, $rs)
    {
        $inputName = $this->getInput('name');

        if ($rs[$inputName]) { // Add the player preview.
            $data .= \Txp::get('\Oui\Player\Player')->renderPlayer(array(
                    'play'       => $rs[$inputName],
                    'wraptag'    => 'div',
                    'class'      => $this->getClass(),
                    'responsive' => true,
                ));
            $data .= '<script>$(function() { $(".' . $this->getClass() . '").css("margin-top", "1em").insertAfter("#' . $this->getInput('id') . '"); });</script>';
        }

        return $data;
    }
}

global $event;

txpinterface === 'admin' && $event === 'article' ? new Preview : '';
