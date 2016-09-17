<?php

class Oui_Player_Youtube extends Oui_Player_Vimeo
{
    protected $plugin = 'oui_player';
    protected $provider = 'Youtube';
    protected $patterns = array('#^(http|https):\/\/(www.)?(youtube\.com\/((watch\?v=)|(embed\/)|(v\/))|youtu\.be\/)([^\&\?\/]+)$#i' => '8');
    protected $src = array('//www.youtube.com/embed/', '//www.youtube-nocookie.com/embed/');
    protected $tags = array(
        'oui_player' => array(
            'autohide' => array(
                'default' => '',
                'valid'   => array('0', '1', '2'),
            ),
            'autoplay' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'controls' => array(
                'default' => '',
            ),
            'cc_load_policy' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'color' => array(
                'default' => '',
            ),
            'disablekb' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'enablejsapi' => array(
                'default' => '',
            ),
            'end' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'fs' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'height' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'hl' => array(
                'default' => '',
            ),
            'iv_load_policy' => array(
                'default' => '',
                'valid'   => array('1', '3'),
            ),
            'loop' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'modestbranding' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'no_cookie' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'origin' => array(
                'default' => '',
            ),
            'playerapiid' => array(
                'default' => '',
            ),
            'playlist' => array(
                'default' => '',
            ),
            'playsinline' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'ratio' => array(
                'default' => '',
            ),
            'rel' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'showinfo' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'start' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'theme' => array(
                'default' => '',
                'valid'   => array('dark', 'light'),
            ),
            'width' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
        ),
    );
    protected $prefs = array(
        'no_cookie'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
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
        'disablekb'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'enablejsapi'    => array(
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
        'height' => array(
            'default' => '',
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
        'origin'         => array(
            'default' => '',
        ),
        'playerapiid'    => array(
            'default' => '',
        ),
        'playlist'    => array(
            'default' => '',
        ),
        'playsinline'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'ratio' => array(
            'default' => '16:9',
        ),
        'rel'            => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'start'          => array(
            'default' => '',
        ),
        'showinfo'       => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'theme'          => array(
            'default' => 'dark',
            'valid'   => array('dark', 'light'),
        ),
        'width' => array(
            'default' => '640',
        ),
    );

    /**
     * Get the provider player url and its parameters/attributes
     *
     * @param string $provider The video provider
     * @param string $no_cookie The no_cookie attribute or pref value (Youtube)
     */
    public function getParams($provider, $no_cookie)
    {
        $src = $no_cookie ? $this->src[1] : $this->src[0];

        $player_infos = array(
            'src'    => $src,
            'params' => $this->prefs,
        );

        return $player_infos;
    }
}
