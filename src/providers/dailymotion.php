<?php

class Oui_Video_Dailymotion extends Oui_Video_Vimeo
{
    protected $plugin = 'oui_video';
    protected $provider = 'Dailymotion';
    protected $patterns = array('#(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '6');
    protected $api = 'http://www.dailymotion.com/services/oembed?url=';
    protected $base = 'https://dailymotion.com/video/';
    protected $tags = array(
        'oui_video' => array(
            'api' => array(
                'default' => '',
            ),
            'autoplay' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'controls' => array(
                'default' => '',
            ),
            'endscreen_enable' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'id' => array(
                'default' => '',
            ),
            'mute' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'origin' => array(
                'default' => '',
            ),
            'quality' => array(
                'default' => '',
                'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
            ),
            'sharing_enable' => array(
                'default' => '',
                'valid'   => array('0', '1'),
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
                'valid'   => array('0', '1'),
            ),
            'ui_theme' => array(
                'default' => '',
                'valid'   => array('dark', 'light'),
            ),
            'ui_start_screen_info' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
        ),
    );
    protected $prefs = array(
        'api'                  => array(
            'default' => '',
            'valid'   => array('', 'postMessage', 'location', '1'),
        ),
        'autoplay'             => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'controls'             => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'endscreen-enable'     => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'id'                   => array(
            'default' => '',
        ),
        'mute'                 => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'origin'               => array(
            'default' => '',
        ),
        'quality'              => array(
            'default' => 'auto',
            'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
        ),
        'sharing-enable'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
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
            'widget' => 'oui_video_pref_color',
            'default' => '#ffcc33',
        ),
        'ui-logo'              => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'ui-theme'             => array(
            'default' => 'dark',
            'valid'   => array('dark', 'light'),
        ),
        'ui-start-screen-info' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
    );
}
