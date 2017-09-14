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
 * Myspace
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    class Myspace extends Provider
    {
        protected static $patterns = array(
            'video' => array(
                'scheme' => '#^(http|https)://myspace\.com/[\S]+/video/[\S]+/(\d+)$#i',
                'id'     => '2',
            ),
        );
        protected static $src = '//media.myspace.com/';
        protected static $glue = array('play/video/', '?', '&amp;');
    }

    if (txpinterface === 'admin') {
        Myspace::getInstance();
    }
}
