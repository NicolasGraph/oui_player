<?php

class Oui_Player_Mixcloud extends Oui_Player_Provider
{
    protected $provider = 'Mixcloud';
    protected $patterns = array('#^((http|https):\/\/(www.)?mixcloud.com\/[\S]+)$#i' => '1');
    protected $src = '//www.mixcloud.com/widget/iframe/?feed=';
    protected $params = array(
        'width' => array(
            'default' => '100%',
        ),
        'height' => array(
            'default' => '400',
        ),
        'ratio' => array(
            'default' => '',
        ),
        'autoplay' => array(
            'default' => '0',
            'valid' => array('0', '1'),
        ),
        'light' => array(
            'default' => '0',
            'valid' => array('0', '1'),
        ),
        'hide_artwork' => array(
            'default' => '0',
            'valid' => array('0', '1'),
        ),
        'hide_cover' => array(
            'default' => '0',
            'valid' => array('0', '1'),
        ),
        'mini' => array(
            'default' => '0',
            'valid' => array('0', '1'),
        ),
    );
}

$instance = Oui_Player_Mixcloud::getInstance();
$instance->plugProvider();
