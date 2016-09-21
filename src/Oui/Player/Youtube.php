<?php

class Oui_Player_Youtube extends Oui_Player_Provider
{
    protected $provider = 'Youtube';
    protected $patterns = array('#^(http|https):\/\/(www.)?(youtube\.com\/((watch\?v=)|(embed\/)|(v\/))|youtu\.be\/)([^\&\?\/]+)$#i' => '8');
    protected $src = '//www.youtube-nocookie.com/embed/';
    protected $params = array(
        'width' => array(
            'default' => '640',
        ),
        'height' => array(
            'default' => '',
        ),
        'ratio' => array(
            'default' => '16:9',
        ),
        'autohide'       => array(
            'default' => '2',
            'valid'   => array('0', '1', '2'),
        ),
        'autoplay'       => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'cc_load_policy' => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'color'          => array(
            'default' => 'red',
            'valid'   => array('red', 'white'),
        ),
        'controls'       => array(
            'default' => '1',
            'valid'   => array('0', '1', '2'),
        ),
        'disablekb'      => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'end'            => array(
            'default' => '',
        ),
        'fs'             => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'hl'             => array(
            'default' => '',
        ),
        'iv_load_policy' => array(
            'default' => '1',
            'valid'   => array('1', '3'),
        ),
        'loop'           => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'modestbranding' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'playlist'    => array(
            'default' => '',
        ),
        'playsinline'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'rel'            => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'start'          => array(
            'default' => '0',
        ),
        'showinfo'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'theme'          => array(
            'default' => 'dark',
            'valid'   => array('dark', 'light'),
        ),
    );
}

new Oui_Player_Youtube;
