<?php

class Oui_Video_Vimeo
{
    protected $plugin = 'oui_video';
    protected $provider = 'Vimeo';
    protected $tags = array(
        'oui_video' => array(
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
            'player_id' => array(
                'default' => '',
            ),
            'portrait' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'title' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
        ),
    );
    protected $patterns = array('#((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)#i' => '4');
    protected $src = '//player.vimeo.com/video/';
    protected $prefs = array(
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
            'widget' => 'oui_video_pref_color',
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

    public function getPrefs($prefs)
    {
        foreach ($this->prefs as $pref => $options) {
            $options['group'] = $this->plugin . '_' . strtolower($this->provider);
            $pref = $options['group'] . '_' . $pref;
            $prefs[$pref] = $options;
        }

        return $prefs;
    }

    /**
     * Get a tag attribute list
     *
     * @param string $tag The plugin tag
     */
    public function getAtts($tag, $get_atts)
    {
        if (isset($this->tags[$tag])) {
            foreach ($this->tags[$tag] as $att => $options) {
                $get_atts[$att] = $options;
            }
        }

        return $get_atts;
    }

    /**
     * Get the video provider and the video id from its url
     *
     * @param string $video The video url
     */
    public function videoInfos($video)
    {

        foreach ($this->patterns as $pattern => $id) {
            if (preg_match($pattern, $video, $matches)) {
                $match = array(
                    'provider' => strtolower($this->provider),
                    'id'       => $matches[$id],
                );
                return $match;
            }
        }

        return false;
    }

    /**
     * Get the provider player url and its parameters/attributes
     *
     * @param string $provider The video provider
     * @param string $no_cookie The no_cookie attribute or pref value (Youtube)
     */
    public function playerInfos($provider, $no_cookie)
    {
        $player_infos = array(
            'src'    => $this->src,
            'params' => $this->prefs,
        );

        return $player_infos;
    }
}
