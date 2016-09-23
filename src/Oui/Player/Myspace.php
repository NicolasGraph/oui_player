<?php

class Oui_Player_Myspace extends Oui_Player_Provider
{
    protected $provider = 'Myspace';
    protected $patterns = array('#^(http|https):\/\/myspace\.com\/[\S]+\/video\/[\S]+\/(\d+)$#i' => '2');
    protected $src = '//media.myspace.com/play/video/';
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
    );
}

new Oui_Player_Myspace;
