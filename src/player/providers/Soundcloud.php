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
 * Soundcloud
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Soundcloud extends Provider
    {
        protected static $patterns = array(
            'audio' => array(
                'scheme' => '#((http|https)://(api\.)?soundcloud\.com/[\S]+)#i',
                'id'     => '1',
            ),
        );
        protected static $src = '//w.soundcloud.com/';
        protected static $glue = array('player/?url=', '?', '&amp;');
        protected static $dims = array(
            'width'          => array(
                'default' => '100%',
            ),
            'height'         => array(
                'default' => '166',
            ),
            'ratio'          => array(
                'default' => '',
            ),
        );
        protected static $params = array(
            'auto_play'      => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'buying'         => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'color'          => array(
                'default' => '#ff8800',
                'valid'   => 'color',
            ),
            'download'       => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'hide_related'   => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
            'sharing'        => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'show_artwork'   => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'show_comments'  => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'show_playcount' => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'show_reposts'   => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'show_user'      => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'single_active'  => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
            'start_track'    => array(
                'default' => '0',
                'valid'   => 'number',
            ),
            'theme_color'    => array(
                'default' => '#ff3300',
                'valid'   => 'color',
            ),
            'visual'         => array(
                'default' => 'false',
                'valid'   => array('true', 'false'),
            ),
        );
    }

    if (txpinterface === 'admin') {
        Soundcloud::getInstance();
    }
}
