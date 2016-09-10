<?php

class Oui_Video
{
    protected $plugin = 'oui_video';
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
                    'default' => '',
                ),
                'height' => array(
                    'default' => '',
                ),
                'ratio' => array(
                    'default' => '4:3',
                ),
                'vimeo_prefs' => array(
                    'default' => 1,
                ),
                'dailymotion_prefs' => array(
                    'default' => 1,
                ),
                'youtube_prefs' => array(
                    'default' => 1,
                ),
            ),
        ),
        'vimeo'       => array(
            'patterns' => array('#((player\.vimeo\.com\/video)|(vimeo\.com))\/(\d+)#i' => '4'),
            'src'      => '//player.vimeo.com/video/',
            'params'   => array(
                'autopause' => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'autopause',
                ),
                'autoplay'  => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'autoplay',
                ),
                'badge'     => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'badge',
                ),
                'byline'    => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'byline',
                ),
                'color'     => array(
                    'default' => '00adef',
                    'att'     => 'color',
                ),
                'loop'      => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'loop',
                ),
                'player_id' => array(
                    'default' => '',
                    'att'     => 'player_id',
                ),
                'portrait'  => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'portrait',
                ),
                'title'     => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'title',
                ),
            ),
        ),
        'youtube'     => array(
            'patterns' => array('#(youtube\.com\/((watch\?v=)|(embed\/)|(v\/))|youtu\.be\/)([^\&\?\/]+)#i' => '6'),
            'src'      => '//www.youtube.com/embed/',
            'params'   => array(
                'autohide'       => array(
                    'default' => '2',
                    'valid'   => array('0', '1', '2'),
                    'att'     => 'autohide',
                ),
                'autoplay'       => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'autoplay',
                ),
                'cc_load_policy' => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'user_prefs',
                ),
                'color'          => array(
                    'default' => 'red',
                    'valid'   => array('red', 'white'),
                    'att'     => 'color',
                ),
                'controls'       => array(
                    'default' => '1',
                    'valid'   => array('0', '1', '2'),
                    'att'     => 'controls',
                ),
                'enablejsapi'    => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'api',
                ),
                'end'            => array(
                    'default' => '',
                    'att'     => 'end',
                ),
                'fs'             => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'full_screen',
                ),
                'hl'             => array(
                    'default' => '',
                    'att'     => 'lang',
                ),
                'iv_load_policy' => array(
                    'default' => '1',
                    'valid'   => array('1', '3'),
                    'att'     => 'annotations',
                ),
                'loop'           => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'loop',
                ),
                'modestbranding' => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'modest_branding',
                ),
                'origin'         => array(
                    'default' => '',
                    'att'     => 'origin',
                ),
                'playerapiid'    => array(
                    'default' => '',
                    'att'     => 'player_id',
                ),
                'rel'            => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'related',
                ),
                'start'          => array(
                    'default' => '',
                    'att'     => 'start',
                ),
                'showinfo'       => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'info',
                ),
                'theme'          => array(
                    'default' => 'dark',
                    'valid'   => array('dark', 'light'),
                    'att'     => 'theme',
                ),
            ),
        ),
        'dailymotion' => array(
            'patterns' => array('#(dailymotion\.com\/((embed\/video)|(video))|(dai\.ly?))\/([A-Za-z0-9]+)#i' => '6'),
            'src'      => '//www.dailymotion.com/embed/video/',
            'params'   => array(
                'api'                  => array(
                    'default' => '',
                    'valid'   => array('', 'postMessage', 'location', '1'),
                    'att'     => 'api',
                ),
                'autoplay'             => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'autoplay',
                ),
                'controls'             => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'controls',
                ),
                'endscreen-enable'     => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'related',
                ),
                'id'                   => array(
                    'default' => '',
                    'att'     => 'player_id',
                ),
                'mute'                 => array(
                    'default' => '0',
                    'valid'   => array('0', '1'),
                    'att'     => 'mute',
                ),
                'origin'               => array(
                    'default' => '',
                    'att'     => 'origin',
                ),
                'quality'              => array(
                    'default' => 'auto',
                    'valid'   => array('auto', '240', '380', '480', '720', '1080', '1440', '2160'),
                    'att'     => 'quality',
                ),
                'sharing-enable'       => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'sharing',
                ),
                'start'                => array(
                    'default' => '0',
                    'att'     => 'start',
                ),
                'subtitles-default'    => array(
                    'default' => '',
                    'att'     => 'lang',
                ),
                'syndication'          => array(
                    'default' => '',
                    'att'     => 'syndication',
                ),
                'ui-highlight'         => array(
                    'default' => 'ffcc33',
                    'att'     => 'color',
                ),
                'ui-logo'              => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'logo',
                ),
                'ui-theme'             => array(
                    'default' => 'dark',
                    'valid'   => array('dark', 'light'),
                    'att'     => 'theme',
                ),
                'ui-start-screen-info' => array(
                    'default' => '1',
                    'valid'   => array('0', '1'),
                    'att'     => 'info',
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

            register_callback(array($this, 'welcome'), 'plugin_lifecycle.' . $this->plugin);
            register_callback(array($this, 'install'), 'prefs', null, 1);
            register_callback(array($this, 'options'), 'plugin_prefs.' . $this->plugin, null, 1);
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
        return str_replace(HELP_URL, 'http://help.ouisource.com/', $ui);
    }

    /**
     * Handler for plugin lifecycle events.
     *
     * @param string $evt Textpattern action event
     * @param string $stp Textpattern action step
     */
    public function welcome($evt, $stp)
    {
        switch ($stp) {
            case 'enabled':
                $this->install();
                break;
            case 'deleted':
                safe_delete('txp_prefs', "event LIKE 'oui\_video%'");
                safe_delete('txp_lang', "name LIKE 'oui\_video%'");
                break;
        }
    }

    /**
     * Jump to the prefs panel.
     */
    public function options()
    {
        $url = defined('PREF_PLUGIN')
               ? '?event=prefs#prefs_group_' . $this->plugin
               : '?event=prefs&step=advanced_prefs';
        header('Location: ' . $url);
    }

    /**
     * Install plugin prefs
     */
    public function install()
    {
        $position = 250;

        foreach ($this->providers as $provider => $infos) {
            $group = $provider === 'all' ? $this->plugin : $this->plugin . '_' . $provider;
            foreach ($infos['params'] as $pref => $options) {
                // Check what is needed as the html value of the pref
                $valid = isset($options['valid']) ? $options['valid'] : false;
                if ($valid && is_array($valid)) {
                    $widget = $valid === array('0', '1') ? 'yesnoradio' : 'oui_video_pref';
                } else {
                    $widget = 'text_input';
                }
                if (get_pref($group . '_' . $pref, null) === null) {
                    set_pref(
                        $group . '_' . $pref,
                        $options['default'],
                        $group,
                        defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
                        isset($options['widget']) ? $options['widget'] : $widget,
                        $position
                    );
                }
                $position = $position + 10;
            }
        }
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
                        $match = array($provider => $matches[$id]);
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
        $player_infos = array(
            'src'    => $this->providers[$provider]['src'],
            'params' => $this->providers[$provider]['params'],
        );
        return $player_infos;
    }
}

new Oui_Video();
