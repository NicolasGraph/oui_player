<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Oui\Player {

    class Bandcamp extends Provider
    {
        protected $patterns = array(
            'album' => array(
                'scheme' => '#((http|https):\/\/bandcamp\.com\/(EmbeddedPlayer\/)?album=(\d+)\/?)#i',
                'id'     => 4,
                'prefix' => 'album=',
                'next' => true,
            ),
            'track' => array(
                'scheme' => '#((http|https):\/\/bandcamp\.com\/(EmbeddedPlayer\/)?[\S]+track=(\d+)\/?)#i',
                'id'     => 4,
                'prefix' => 'track=',
            ),
        );
        protected $src = '//bandcamp.com/';
        protected $glue = array('EmbeddedPlayer/', '/', '/');
        protected $dims = array(
            'width'     => array(
                'default' => '350px',
            ),
            'height'    => array(
                'default' => '350px',
            ),
            'ratio'     => array(
                'default' => '',
            ),
        );
        protected $params = array(
            'size'      => array(
                'default' => 'large',
                'force'   => true,
                'valid'   => array('large', 'small'),
            ),
            'artwork'   => array(
                'default' => '',
                'valid'   => array('', 'none', 'big', 'small'),
            ),
            'bgcol'     => array(
                'default' => '#ffffff',
                'valid'   => 'color',
            ),
            'linkcol'   => array(
                'default' => '#0687f5',
                'valid'   => 'color',
            ),
            'tracklist' => array(
                'default' => 'true',
                'valid'   => array('true', 'false'),
            ),
        );
    }

    if (txpinterface === 'admin') {
        Bandcamp::getInstance();
    }
}
