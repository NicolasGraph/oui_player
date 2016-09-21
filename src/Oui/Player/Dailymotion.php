<?php

class Oui_Player_Dailymotion extends Oui_Player_Provider
{
    protected $provider = 'Dailymotion';
    protected $patterns = array('#^(http|https):\/\/(www.)?(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '8');
    protected $src = '//www.dailymotion.com/embed/video/';
    protected $params = array(
        'width'                => array(
            'default' => '640',
        ),
        'height'               => array(
            'default' => '',
        ),
        'ratio' => array(
            'default' => '16:9',
        ),
        'api'                  => array(
            'default' => '',
            'valid'   => array('', 'postMessage', 'location', '1'),
        ),
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
        'id'                   => array(
            'default' => '',
        ),
        'mute'                 => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
        'origin'               => array(
            'default' => '',
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
        ),
        'subtitles-default'    => array(
            'default' => '',
        ),
        'syndication'          => array(
            'default' => '',
        ),
        'ui-highlight'         => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#ffcc33',
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

new Oui_Player_Dailymotion;
