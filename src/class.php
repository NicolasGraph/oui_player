<?php

class Oui_Video
{
    protected $plugin = 'oui_video';
    protected $pophelp = 'http://help.ouisource.com/';
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
            'class' => array(
                'default' => '',
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
            'endscreen_enable' => array(
                'default' => '',
                'valid'   => array('0', '1'),
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
            'id' => array(
                'default' => '',
            ),
            'iv_load_policy' => array(
                'default' => '',
                'valid'   => array('1', '3'),
            ),
            'label' => array(
                'default' => '',
            ),
            'labeltag' => array(
                'default' => '',
            ),
            'logo' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'loop' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'modestbranding' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'mute' => array(
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
            'player_id' => array(
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
            'portrait' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'provider' => array(
                'default' => '',
                'valid'   => array('vimeo', 'youtube', 'dailymotion'),
            ),
            'quality' => array(
                'default' => '',
                'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
            ),
            'ratio' => array(
                'default' => '',
                'valid'   => '/^(\d+):(\d+)$/'
            ),
            'rel' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'sharing_enable' => array(
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
            'subtitles_default' => array(
                'default' => '',
            ),
            'syndication' => array(
                'default' => '',
            ),
            'theme' => array(
                'default' => '',
                'valid'   => array('dark', 'light'),
            ),
            'title' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'ui_highlight' => array(
                'default' => '',
                'valid'   => '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ),
            'ui_logo' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'ui_theme' => array(
                'default' => '',
                'valid'   => array('dark', 'light'),
            ),
            'ui_start_screen_info' => array(
                'default' => '',
                'valid'   => array('0', '1'),
            ),
            'video' => array(
                'default' => '',
            ),
            'width' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'wraptag' => array(
                'default' => '',
            ),
        ),
        'oui_if_video' => array(
            'video' => array(
                'default' => '',
            ),
            'provider' => array(
                'default' => '',
                'valid'   => array('vimeo', 'youtube', 'dailymotion'),
            ),
        ),
    );
    protected $privs = '1, 2';
    protected $providers = array(
        'all'         => array(
            'params' => array(
                'custom_field' => array(
                    'widget'  => 'oui_video_custom_fields',
                    'default' => 'article_image',
                ),
                'provider' => array(
                    'default' => 'youtube',
                    'valid'   => array('dailymotion', 'vimeo', 'Youtube'),
                ),
                'width' => array(
                    'default' => '640',
                ),
                'height' => array(
                    'default' => '',
                ),
                'ratio' => array(
                    'default' => '4:3',
                ),
                'vimeo_prefs' => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
                'dailymotion_prefs' => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
                'youtube_prefs' => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
            ),
        ),
        'vimeo'       => array(
            'patterns' => array('#((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)#i' => '4'),
            'src'      => array('//player.vimeo.com/video/'),
            'params'   => array(
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
                    'default' => '00adef',
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
            ),
        ),
        'youtube'     => array(
            'patterns' => array('#(youtube\.com\/((watch\?v=)|(embed\/)|(v\/))|youtu\.be\/)([^\&\?\/]+)#i' => '6'),
            'src'      => array('//www.youtube.com/embed/', '//www.youtube-nocookie.com/embed/'),
            'params'   => array(
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
            ),
        ),
        'dailymotion' => array(
            'patterns' => array('#(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '6'),
            'src'      => array('//www.dailymotion.com/embed/video/'),
            'params'   => array(
                'api'                  => array(
                    'default' => '',
                    'valid'   => array('', 'postMessage', 'location', '1'),
                ),
                'autoplay'             => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
                'controls'             => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                ),
                'endscreen-enable'     => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                ),
                'id'                   => array(
                    'default' => '',
                ),
                'mute'                 => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                ),
                'origin'               => array(
                    'default' => '',
                ),
                'quality'              => array(
                    'default' => 'auto',
                    'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
                ),
                'sharing-enable'       => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                ),
                'start'                => array(
                    'default' => '0',
                ),
                'subtitles-default'    => array(
                    'default' => '',
                ),
                'syndication'          => array(
                    'default' => '',
                ),
                'ui-highlight'         => array(
                    'default' => 'ffcc33',
                ),
                'ui-logo'              => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                ),
                'ui-theme'             => array(
                    'default' => 'dark',
                    'valid'   => array('dark', 'light'),
                ),
                'ui-start-screen-info' => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                ),
            ),
        ),
    );

    /**
     * Register callbacks.
     */
    public function __construct()
    {
        if (txpinterface === 'admin') {
            add_privs('plugin_prefs.' . $this->plugin, $this->privs);
            add_privs('prefs.' . $this->plugin, $this->privs);

            // Add privs to provider prefs only if they are enabled.
            foreach ($this->providers as $provider => $infos) {
                if ($provider !== 'all') {
                    if (!empty($_POST[$this->plugin . '_' . $provider . '_prefs']) || (!isset($_POST[$this->plugin . '_' . $provider . '_prefs']) && get_pref($this->plugin . '_' . $provider . '_prefs'))) {
                        add_privs('prefs.' . $this->plugin . '_' . $provider, $this->privs);
                    }
                }
                foreach ($infos['params'] as $pref => $options) {
                    register_callback(array($this, 'pophelp'), 'admin_help', $pref);
                }
            }

            register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . $this->plugin);
            register_callback(array($this, 'setPrefs'), 'prefs', null, 1);
            register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . $this->plugin, null, 1);
        } else {
            if (class_exists('\Textpattern\Tag\Registry')) {
                // Register Textpattern tags for TXP 4.6+.
                foreach ($this->tags as $tag => $attributes) {
                    Txp::get('\Textpattern\Tag\Registry')->register($tag);
                }
            }
        }
    }

    /**
     * Handler for plugin lifecycle events.
     *
     * @param string $evt Textpattern action event
     * @param string $stp Textpattern action step
     */
    public function lifeCycle($evt, $stp)
    {
        switch ($stp) {
            case 'enabled':
                $this->setPrefs();
                break;
            case 'deleted':
                safe_delete('txp_prefs', "event LIKE '" . $this->plugin . "%'");
                safe_delete('txp_lang', "name LIKE '" . $this->plugin . "%'");
                break;
        }
    }

    /**
     * Jump to the prefs panel.
     */
    public function optionsLink()
    {
        $url = defined('PREF_PLUGIN')
               ? '?event=prefs#prefs_group_' . $this->plugin
               : '?event=prefs&step=advanced_prefs';
        header('Location: ' . $url);
    }

    /**
     * Define the pref widget
     *
     * @param array $options Current pref options
     */
    public function prefWidget($options)
    {
        // Check what is needed as the html value of the pref
        $valid = isset($options['valid']) ? $options['valid'] : false;

        if ($valid && is_array($valid)) {
            $widget = $valid === array('0', '1') ? 'yesnoradio' : $this->plugin . '_pref';
        } else {
            $widget = 'text_input';
        }

        return $widget;
    }

    /**
     * Build select inputs for plugin prefs
     *
     * @param string $name the name of the preference (Textpattern variable)
     * @param string $val The value of the preference (Textpattern variable)
     */
    public function prefSelect($name, $val)
    {
        foreach ($this->providers as $provider => $infos) {
            $group = $provider === 'all' ? $this->plugin : $this->plugin . '_' . $provider;

            foreach ($infos['params'] as $pref => $options) {
                if ($name === $group . '_' . $pref) {
                    $valid = $options['valid'];
                    $vals = array();

                    foreach ($valid as $value) {
                        $value === '' ?: $vals[$value] = gtxt($group  . '_' . $pref . '_' . $value);
                    }

                    return selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
                }
            }
        }
    }

    /**
     * Install plugin prefs
     */
    public function setPrefs()
    {
        $position = 250;

        foreach ($this->providers as $provider => $infos) {
            $group = $provider === 'all' ? $this->plugin : $this->plugin . '_' . $provider;

            foreach ($infos['params'] as $pref => $options) {
                if (get_pref($group . '_' . $pref, null) === null) {
                    set_pref(
                        $group . '_' . $pref,
                        $options['default'],
                        $group,
                        defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
                        isset($options['widget']) ? $options['widget'] : $this->prefWidget($options),
                        $position
                    );
                }
                $position = $position + 10;
            }
        }
    }

    /**
     * Get external popHelp contents
     *
     * @param string $evt Textpattern action event
     * @param string $stp Textpattern action step
     * @param string $ui Textpattern user interface element
     */
    public function pophelp($evt, $stp, $ui, $vars)
    {
        return str_replace(HELP_URL, $this->pophelp, $ui);
    }

    /**
     * Get a tag attribute list
     *
     * @param string $tag The plugin tag
     */
    public function getAtts($tag)
    {
        $init_atts = array();
        foreach ($this->tags[$tag] as $att => $options) {
            $init_atts[$att] = $options['default'];
        }

        return $init_atts;
    }

    /**
     * Look for wrong attribute values
     *
     * @param string $tag The plugin tag
     * @param array $atts The Txp variable containing attribute values in use
     */
    public function checkAtts($tag, $atts)
    {
        foreach ($atts as $att => $val) {
            $valid = isset($this->tags[$tag][$att]['valid']) ? $this->tags[$tag][$att]['valid'] : false;

            if ($valid) {
                if (is_array($valid) && !in_array($val, $valid)) {
                    $valid = implode(', ', $valid);
                    trigger_error(
                        'Unknown attribute value for ' . $att .
                        '. Exact valid values are: ' . $valid . '.'
                    );
                } elseif (!is_array($valid) && !preg_match($valid, $val)) {
                    trigger_error(
                        'Unknown attribute value for ' . $att .
                        '. A valid value must respect the following pattern ' . $valid . '.'
                    );
                }
            }
        }

        return;
    }

    /**
     * Get the video provider and the video id from its url
     *
     * @param string $video The video url
     */
    public function videoInfos($video)
    {
        foreach ($this->providers as $provider => $provider_infos) {
            if ($provider !== 'all') {
                foreach ($provider_infos['patterns'] as $pattern => $id) {
                    if (preg_match($pattern, $video, $matches)) {
                        $match = array(
                            'provider' => $provider,
                            'id'       => $matches[$id],
                        );
                        return $match;
                    }
                }
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
        if ($provider === 'youtube') {
            $src = $no_cookie ? $this->providers[$provider]['src'][1] : $this->providers[$provider]['src'][0];
        } else {
            $src = $this->providers[$provider]['src'][0];
        }

        $player_infos = array(
            'src'    => $src,
            'params' => $this->providers[$provider]['params'],
        );

        return $player_infos;
    }

    /**
     * Calculate the player size
     *
     * @param array $dims An associative array containing provided attribute values for width, height and ratio
     */
    public function playerSize($dims)
    {
        $width = $dims['width'];
        $height = $dims['height'];

        if (!$width || !$height) {
            $ratio = $dims['ratio'] ? $dims['ratio'] : $this->providers['all']['params']['ratio']['default'];

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
            } else {
                $width = $this->providers['all']['params']['width']['default'];
                $height = $width / $aspect;
            }
        }
        return array(
            'width' => $width,
            'height' => $height,
        );
    }
}

new Oui_Video();
