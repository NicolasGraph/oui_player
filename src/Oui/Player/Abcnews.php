<?php

class Oui_Player_Abcnews extends Oui_Player_Provider
{
    protected $provider = 'Abcnews';
    protected $patterns = array('#^(http|https):\/\/(abcnews\.go\.com\/([A-Z]+\/)?video)\/[^0-9]+([0-9]+)$#i' => '4');
    protected $src = '//abcnews.go.com/video/embed?id=';
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
        'ts'     => array(
            'default' => '0',
        ),
    );
}

new Oui_Player_Abcnews;
