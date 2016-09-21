<?php

class Oui_Player_Dailymotion extends Oui_Player_Provider
{
    protected $provider = 'Dailymotion';
    protected $patterns = array('#^(http|https):\/\/(www.)?(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '8');
    protected $src = '//www.dailymotion.com/embed/video/';
    protected $tags = array(
        'oui_player' => array(
            'api' => array(
                'default' => '',
            ),
            'autoplay' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'controls' => array(
                'default' => '',
            ),
            'endscreen_enable' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'id' => array(
                'default' => '',
            ),
            'height' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'mute' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'origin' => array(
                'default' => '',
            ),
            'quality' => array(
                'default' => '',
                'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
            ),
            'ratio' => array(
                'default' => '',
            ),
            'sharing_enable' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'start' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'subtitles_default' => array(
                'default' => '',
            ),
            'syndication' => array(
                'default' => '',
            ),
            'ui_highlight' => array(
                'default' => '',
                'valid'   => '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ),
            'ui_logo' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'ui_theme' => array(
                'default' => '',
                'valid'   => array('dark', 'light'),
            ),
            'ui_start_screen_info' => array(
                'default' => '',
                'valid'   => array('true', 'false'),
            ),
            'width' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
        ),
    );
    protected $prefs = array(
        'width' => array(
            'default' => '640',
        ),
        'height' => array(
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
