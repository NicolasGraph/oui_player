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
 * Mixcloud
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Mixcloud extends Provider
    {
        protected static $patterns = array(
            'audio' => array(
                'scheme' => '#^((http|https)://(www\.)?mixcloud.com/[\S]+)$#i',
                'id'     => '1',
            ),
        );
        protected static $src = '//www.mixcloud.com/';
        protected static $glue = array('widget/iframe/?feed=', '?', '&amp;');
        protected static $dims = array(
            'width'  => array(
                'default' => '100%',
            ),
            'height' => array(
                'default' => '400',
            ),
            'ratio'  => array(
                'default' => '',
            ),
        );
        protected static $params = array(
            'autoplay'     => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'light'        => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'hide_artwork' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'hide_cover'   => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'mini'         => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
        );
    }

    if (txpinterface === 'admin') {
        Mixcloud::getInstance();
    }
}
