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

class Dailymotion extends Provider
{
    protected $patterns = array(
        'video' => array(
            'scheme' => '#^(http|https):\/\/(www.)?(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i',
            'id'     => '8',
        ),
    );
    protected $src = '//www.dailymotion.com/embed/video/';
    protected $params = array(
        'autoplay'             => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'controls'             => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
        'endscreen-enable'     => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
        'mute'                 => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'quality'              => array(
            'default' => 'auto',
            'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
        ),
        'sharing-enable'       => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
        'start'                => array(
            'default' => '0',
            'valid'   => 'number'
        ),
        'subtitles-default'    => array(
            'default' => '',
        ),
        'ui-highlight'         => array(
            'default' => '#ffcc33',
            'valid'   => 'color',
        ),
        'ui-logo'              => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
        'ui-theme'             => array(
            'default' => 'dark',
            'valid'   => array('dark', 'light'),
        ),
        'ui-start-screen-info' => array(
            'default' => 'true',
            'valid'   => array('true', 'false'),
        ),
    );
}

if (txpinterface === 'admin') {
    Dailymotion::getInstance();
}
