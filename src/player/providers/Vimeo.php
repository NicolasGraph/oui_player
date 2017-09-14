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
 * Vimeo
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Vimeo extends Provider
    {
        protected static $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https)://((player\.vimeo\.com/video)|(vimeo\.com))/(\d+)$#i',
                'id'     => '5',
            ),
        );
        protected static $src = '//player.vimeo.com/';
        protected static $glue = array('video/', '?', '&amp;');
        protected static $params = array(
            'api' => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'autopause' => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'autoplay'  => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'byline'    => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'color'     => array(
                'default' => '#00adef',
                'valid'   => 'color',
            ),
            'loop'      => array(
                'default' => '0',
                'valid'   => array('0', '1'),
            ),
            'portrait'  => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
            'title'     => array(
                'default' => '1',
                'valid'   => array('0', '1'),
            ),
        );
    }

    if (txpinterface === 'admin') {
        Vimeo::getInstance();
    }
}
