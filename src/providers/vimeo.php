<?php

class Oui_Player_Vimeo
{
    protected $plugin = 'oui_player';
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
        'height' => array(
            'default' => '',
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
        'ratio' => array(
            'default' => '16:9',
        ),
        'title'     => array(
            'default' => '1',
            'valid'   => array('0', '1'),
        ),
        'width' => array(
            'default' => '640',
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
    public function getItemInfos($video)
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
    public function getParams($provider, $no_cookie)
    {
        $player_infos = array(
            'src'    => $this->src,
            'params' => $this->prefs,
        );

        return $player_infos;
    }

    public function getOutput($src, $used_params, $dims)
    {
        if (!empty($used_params)) {
            $glue = strpos($src, '?') ? '&amp;' : '?';
            $src .= $glue . implode('&amp;', $used_params);
        }

        $width = $dims['width'];
        $height = $dims['height'];

        if ((!$width || !$height)) {
            $ratio = !empty($dims['ratio']) ? $dims['ratio'] : '16:9';

            // Work out the aspect ratio.
            preg_match("/(\d+):(\d+)/", $ratio, $matches);
            if ($matches[0] && $matches[1]!=0 && $matches[2]!=0) {
                $aspect = $matches[1] / $matches[2];
            } else {
                $aspect = 1.778;
            }

            // Calcuate the new width/height.
            if ($width) {
                $height = $width / $aspect;
            } elseif ($height) {
                $width = $height * $aspect;
            }
        }

        $output = '<iframe width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe>';

        return $output;
    }
}
