<?php

class Oui_Video_Vimeo
{
    protected $plugin = 'oui_video';
    protected $provider = 'Vimeo';
    protected $api = 'https://vimeo.com/api/oembed.json?url=';
    protected $patterns = array('#((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)#i' => '4');
    protected $base = 'https://vimeo.com/';
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
                    'url'      => $this->prefixId($matches[$id]),
                );

                return $match;
            }
        }

        return false;
    }

    public function prefixId($id)
    {
        return $this->base . $id;
    }

    /**
     * Get the provider player url and its parameters/attributes
     *
     * @param string $provider The video provider
     * @param string $no_cookie The no_cookie attribute or pref value (Youtube)
     */
    public function getParams($no_cookie)
    {
        return $this->prefs;
    }

    public function getJson($url)
    {
        $json = json_decode(file_get_contents($this->api . $url), true);

        return $json;
    }

    public function getOutput($url, $used_params, $dims)
    {
        $json = $this->getJson($url);
        $code = $json['html'];

        if (!empty($used_params)) {
            $src = preg_match('/src="[\S][^"]+/', $code, $match);
            $glue = strpos($match[0], '?') ? '&amp;' : '?';
            $src = $match[0] . $glue . implode('&amp;', $used_params);
            $output = str_replace($match[0], $src, $code);
        } else {
            $output = $code;
        }

        $width = $dims['width'];
        $height = $dims['height'];
        $ratio = $dims['ratio'];

        if ((!$width || !$height)) {
            $ratio = $dims['ratio'] ? $dims['ratio'] : $this->prefs['ratio'];

            // Work out the aspect ratio.
            preg_match("/(\d+):(\d+)/", $ratio, $matches);
            if ($matches[0] && $matches[1]!=0 && $matches[2]!=0) {
                $aspect = $matches[1] / $matches[2];
            } else {
                $aspect = 1.333;
            }

            // Calcuate the new width/height.
            if ($width) {
                $height = $width / $aspect;
            } elseif ($height) {
                $width = $height * $aspect;
            }
        }

        if ($width) {
            $output = preg_replace('/width="[^"]+"/', 'width="' . $width . '"', $output);
        }

        if ($height) {
            $output = preg_replace('/height="[^"]+"/', 'height="' . $height . '"', $output);
        }

        return $output;
    }
}
