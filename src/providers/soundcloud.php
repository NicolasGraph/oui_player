<?php

class Oui_Player_Soundcloud extends Oui_Player_Vimeo
{
    protected $plugin = 'oui_player';
    protected $provider = 'Soundcloud';
    protected $patterns = array('#((http|https):\/\/api.soundcloud\.com\/[\S]+)#i' => '1');
    protected $src = '//w.soundcloud.com/player/?url=';
    protected $tags = array(
        'oui_player' => array(
            'color' => array(
                'default' => '',
            ),
            'theme_color' => array(
                'default' => '',
            ),
            'text_buy_track' => array(
                'default' => '',
            ),
            'text_buy_set' => array(
                'default' => '',
            ),
            'text_download_track' => array(
                'default' => '',
            ),
            'buying' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'sharing' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'download' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'show_bpm' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'show_playcount' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'start_track' => array(
                'default' => '',
            ),
            'default_width' => array(
                'default' => '',
            ),
            'default_height' => array(
                'default' => '',
            ),
            'font' => array(
                'default' => '',
            ),
            'enable_api' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'single_active' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'show_user' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'auto_play' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'show_artwork' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'show_comments' => array(
                'default' => '',
                'valid' => array('true', 'false'),
            ),
            'height' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'ratio' => array(
                'default' => '',
            ),
            'width' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
        ),
    );
    protected $prefs = array(
        'color'       => array(
            'widget' => 'oui_player_pref_color',
            'default' => '',
        ),
        'theme_color' => array(
            'widget' => 'oui_player_pref_color',
            'default' => '',
        ),
        'text_buy_track' => array(
            'default' => '',
        ),
        'text_buy_set' => array(
            'default' => '',
        ),
        'text_download_track' => array(
            'default' => '',
        ),
        'buying' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'sharing' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'download' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_bpm' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_playcount' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'start_track' => array(
            'default' => '',
        ),
        'default_width' => array(
            'default' => '',
        ),
        'default_height' => array(
            'default' => '',
        ),
        'font' => array(
            'default' => '',
        ),
        'enable_api' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'single_active' => array(
            'default' => '',
            'valid' => array('true', 'false'),
        ),
        'show_user' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'auto_play' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'show_artwork' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'show_comments' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'width' => array(
            'default' => '100%',
        ),
        'height' => array(
            'default' => '166',
        ),
        'ratio' => array(
            'default' => '',
        ),
    );
}
