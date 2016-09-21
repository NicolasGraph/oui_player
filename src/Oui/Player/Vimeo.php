<?php

class Oui_Player_Vimeo extends Oui_Player_Provider
{
    protected $provider = 'Vimeo';
    protected $patterns = array('#^(http|https):\/\/((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)$#i' => '5');
    protected $src = '//player.vimeo.com/video/';
    protected $tags = array(
        'oui_player' => array(
            'api' => array(
                'default' => '',
            ),
            'autohide' => array(
                'default' => '',
                'valid'   => array('0', '1', '2'),
            ),
            'autopause' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'autoplay' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'badge' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'byline' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'color' => array(
                'default' => '',
            ),
            'height' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'loop'      => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'player_id' => array(
                'default' => '',
            ),
            'portrait' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'ratio' => array(
                'default' => '',
            ),
            'title' => array(
                'default' => '',
                'valid'   => array('0', '1'),
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
        'autopause' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'autoplay'  => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'badge'     => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'byline'    => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'color'     => array(
            'widget' => 'oui_player_pref_color',
            'default' => '#00adef',
        ),
        'loop'      => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'player_id' => array(
            'default' => '',
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

new Oui_Player_Vimeo;
