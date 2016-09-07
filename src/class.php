<?php

class Oui_Video
{
    protected $providers = array(
        'all' => array(
            'prefs' => array(
                'oui_video_custom_field' => array(
                    'value'      => 'article_image',
                    'event'      => 'oui_video',
                    'widget'     => 'oui_video_custom_fields',
                ),
                'oui_video_provider' => array(
                    'value'      => '',
                    'event'      => 'oui_video',
                    'widget'     => 'oui_video_provider',
                ),
                'oui_video_width' => array(
                    'value'      => '',
                    'event'      => 'oui_video',
                    'widget'     => 'text_input',
                ),
                'oui_video_height' => array(
                    'value'      => '',
                    'event'      => 'oui_video',
                    'widget'     => 'text_input',
                ),
                'oui_video_ratio' => array(
                    'value'      => '4:3',
                    'event'      => 'oui_video',
                    'widget'     => 'text_input',
                ),
                'oui_video_vimeo_prefs' => array(
                    'value'      => 1,
                    'event'      => 'oui_video',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_prefs' => array(
                    'value'      => 1,
                    'event'      => 'oui_video',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_prefs' => array(
                    'value'      => 1,
                    'event'      => 'oui_video',
                    'widget'     => 'yesnoradio',
                ),
            ),
        ),
        'vimeo' => array(
            'patterns' => array(
                '#^https?://((player|www)\.)?vimeo\.com(/video)?/(\d+)#i' => '4',
            ),
            'src' => '//player.vimeo.com/video/',
            'params' => array(
                'autopause' => array('autopause' => '1'),
                'autoplay'  => array('autoplay' => '0'),
                'badge'     => array('badge' => '1'),
                'byline'    => array('byline' => '1'),
                'color'     => array('color' => '00adef'),
                'loop'      => array('loop' => '0'),
                'player_id' => array('player_id' => ''),
                'portrait'  => array('portrait' => '1'),
                'title'     => array('title' => '1')
            ),
            'prefs' => array(
                'oui_video_vimeo_autopause' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_autoplay' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_badge' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_byline' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_color' => array(
                    'value'      => '00adef',
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'text_input',
                ),
                'oui_video_vimeo_loop' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_player_id' => array(
                    'value'      => '',
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'text_input',
                ),
                'oui_video_vimeo_portrait' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_vimeo_title' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_vimeo',
                    'widget'     => 'yesnoradio',
                ),
            ),
        ),
        'youtube' => array(
            'patterns' => array(
                '#^(http|https)?:\/\/www\.youtube\.com(\/watch\?)?([^\&\?\/]+)#i' => '3',
                '#^(http|https)?[:\/\/]+youtu\.be\/([^\&\?\/]+)#i' => '2',
            ),
            'src' => '//www.youtube.com/embed/',
            'params' => array(
                'autohide'       => array('autohide' => '2'),
                'autoplay'       => array('autoplay' => '0'),
                'cc_load_policy' => array('user_prefs' => '1'),
                'color'          => array('color' => 'red'),
                'controls'       => array('controls' => '1'),
                'enablejsapi'    => array('api' => '0'),
                'end'            => array('end' => ''),
                'fs'             => array('full_screen' => '1'),
                'hl'             => array('lang' => ''),
                'iv_load_policy' => array('annotations' => '1'),
                'loop'           => array('loop' => '0'),
                'modestbranding' => array('modest_branding' => '0'),
                'origin'         => array('origin' => ''),
                'playerapiid'    => array('player_id' => ''),
                'rel'            => array('related' => '1'),
                'start'          => array('start' => ''),
                'showinfo'       => array('info' => '1'),
                'theme'          => array('theme' => 'dark')
            ),
            'prefs' => array(
                'oui_video_youtube_no_cookie' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_autohide' => array(
                    'value'      => 2,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'oui_video_youtube_012',
                ),
                'oui_video_youtube_autoplay' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_user_prefs' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_color' => array(
                    'value'      => 'red',
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'oui_video_youtube_color',
                ),
                'oui_video_youtube_controls' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'oui_video_youtube_012',
                ),
                'oui_video_youtube_api' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_full_screen' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_lang' => array(
                    'value'      => '',
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'text_input',
                ),
                'oui_video_youtube_annotations' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'text_input',
                ),
                'oui_video_youtube_loop' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_modest_branding' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_origin' => array(
                    'value'      => '',
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'text_input',
                ),
                'oui_video_youtube_player_id' => array(
                    'value'      => '',
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'text_input',
                ),
                'oui_video_youtube_related' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_info' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_youtube_theme' => array(
                    'value'      => 'dark',
                    'event'      => 'oui_video_youtube',
                    'widget'     => 'oui_video_theme',
                ),
            ),
        ),
        'dailymotion' => array(
            'patterns' => array(
                '#^(http|https)?://www\.dailymotion\.com(/video)?/([A-Za-z0-9]+)#i' => '3',
                '#^(http|https)?://dai\.ly(/video)?/([A-Za-z0-9]+)#i' => '3',
            ),
            'src' => '//www.dailymotion.com/embed/video/',
            'params' => array(
                'api'                  => array('api' => '0'),
                'autoplay'             => array('autoplay' => '0'),
                'controls'             => array('controls' => '1'),
                'endscreen-enable'     => array('related' => '1'),
                'id'                   => array('player_id' => ''),
                'mute'                 => array('mute' => '0'),
                'origin'               => array('origin' => ''),
                'quality'              => array('quality' => 'auto'),
                'sharing-enable'       => array('sharing' => '1'),
                'start'                => array('start' => '0'),
                'subtitles-default'    => array('lang' => ''),
                'syndication'          => array('syndication' => ''),
                'ui-highlight'         => array('color' => 'ffcc33'),
                'ui-logo'              => array('logo' => '1'),
                'ui-theme'             => array('theme' => 'dark'),
                'ui-start-screen-info' => array('info' => '1')
            ),
            'prefs' => array(
                'oui_video_dailymotion_api' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'oui_video_dailymotion_api',
                ),
                'oui_video_dailymotion_autoplay' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_controls' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_related' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_player_id' => array(
                    'value'      => '',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'text_input',
                ),
                'oui_video_dailymotion_mute' => array(
                    'value'      => 0,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_origin' => array(
                    'value'      => '',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'text_input',
                ),
                'oui_video_dailymotion_quality' => array(
                    'value'      => 'auto',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'oui_video_dailymotion_quality',
                ),
                'oui_video_dailymotion_sharing' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_lang' => array(
                    'value'      => '',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'text_input',
                ),
                'oui_video_dailymotion_syndication' => array(
                    'value'      => '',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'text_input',
                ),
                'oui_video_dailymotion_color' => array(
                    'value'      => 'ffcc33',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'text_input',
                ),
                'oui_video_dailymotion_logo' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_info' => array(
                    'value'      => 1,
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'yesnoradio',
                ),
                'oui_video_dailymotion_theme' => array(
                    'value'      => 'dark',
                    'event'      => 'oui_video_dailymotion',
                    'widget'     => 'oui_video_theme',
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
            add_privs('plugin_prefs.oui_video', '1');
            add_privs('prefs.oui_video', '1, 2');

            // Add privs to provider prefs only if they are enabled.
            foreach ($this->providers as $provider => $provider_infos) {
                if ($provider !== 'all') {
                    if (!empty($_POST['oui_video_' . $provider . '_prefs']) || (!isset($_POST['oui_video_' . $provider . '_prefs']) && get_pref('oui_video_' . $provider . '_prefs'))) {
                        add_privs('prefs.oui_video_' . $provider, '1, 2');
                    }
                }
                foreach ($provider_infos['prefs'] as $pref => $value) {
                    register_callback(array($this, 'pophelp'), 'admin_help', $pref);
                }
            }

            register_callback(array($this, 'welcome'), 'plugin_lifecycle.oui_video');
            register_callback(array($this, 'install'), 'prefs', null, 1);
            register_callback(array($this, 'options'), 'plugin_prefs.oui_video', null, 1);
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
               ? '?event=prefs#prefs_group_oui_video'
               : '?event=prefs&step=advanced_prefs';
        header('Location: ' . $url);
    }

    /**
     * Install plugin prefs
     */
    public function install()
    {
        $position = 250;

        foreach ($this->providers as $provider) {
            foreach ($provider['prefs'] as $pref => $options) {
                if (get_pref($pref, null) === null) {
                    set_pref(
                        $pref,
                        $options['value'],
                        $options['event'],
                        defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
                        $options['widget'],
                        $position
                    );
                }
                $position = $position + 10;
            }
        }
    }

    /**
     * Video url checking
     * Return the video provider and the video id.
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
     * Get provider related variables and associate attributes to player paramaters
     *
     * @param string $provider The video provider
     * @param string $video_id The video id
     * @param string $no_cookie The no_cookie attribute or pref value (Youtube)
     */
    public function playerInfos($provider, $no_cookie)
    {
        $player_infos = array($this->providers[$provider]['src'] => $this->providers[$provider]['params']);
        return $player_infos;
    }
}

new Oui_Video();
