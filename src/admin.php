<?php

/**
 * Register callbacks.
 */
if (txpinterface === 'admin') {
    add_privs('prefs.oui_video', '1');
    add_privs('plugin_prefs.oui_video', '1');
    add_privs('prefs.oui_video_youtube', '1');
    add_privs('prefs.oui_video_vimeo', '1');
    add_privs('prefs.oui_video_dailymotion', '1');

    register_callback('oui_video_welcome', 'plugin_lifecycle.oui_video');
    register_callback('oui_video_install', 'prefs', null, 1);
    register_callback('oui_video_options', 'plugin_prefs.oui_video', null, 1);

    $prefList = oui_video_preflist();
    foreach ($prefList as $pref => $options) {
        register_callback('oui_video_pophelp', 'admin_help', $pref);
    }
}

/**
 * Get external popHelp contents
 *
 * @param string $evt Textpattern action event
 * @param string $stp Textpattern action step
 * @param string $ui Textpattern user interface element
 */
function oui_video_pophelp($evt, $stp, $ui, $vars)
{
    return str_replace(HELP_URL, 'http://help.ouisource.com/', $ui);
}

/**
 * Handler for plugin lifecycle events.
 *
 * @param string $evt Textpattern action event
 * @param string $stp Textpattern action step
 */
function oui_video_welcome($evt, $stp)
{
    switch ($stp) {
        case 'enabled':
            oui_video_install();
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
function oui_video_options()
{
    $url = defined('PREF_PLUGIN')
           ? '?event=prefs#prefs_group_oui_video'
           : '?event=prefs&step=advanced_prefs';
    header('Location: ' . $url);
}

/**
 * Plugin prefs
 */
function oui_video_preflist()
{
    $prefList = array(
        'oui_video_custom_field' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'widget'     => 'oui_video_custom_fields',
            'position'   => '10',
        ),
        'oui_video_provider' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'widget'     => 'oui_video_provider',
            'position'   => '20',
        ),
        'oui_video_width' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'widget'     => 'text_input',
            'position'   => '30',
        ),
        'oui_video_height' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'widget'     => 'text_input',
            'position'   => '40',
        ),
        'oui_video_ratio' => array(
            'value'      => '4:3',
            'event'      => 'oui_video',
            'widget'     => 'text_input',
            'position'   => '50',
        ),
        'oui_video_vimeo_autopause' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '40',
        ),
        'oui_video_vimeo_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '40',
        ),
        'oui_video_vimeo_badge' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '50',
        ),
        'oui_video_vimeo_byline' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '60',
        ),
        'oui_video_vimeo_color' => array(
            'value'      => '00adef',
            'event'      => 'oui_video_vimeo',
            'widget'     => 'text_input',
            'position'   => '70',
        ),
        'oui_video_vimeo_loop' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '80',
        ),
        'oui_video_vimeo_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_vimeo',
            'widget'     => 'text_input',
            'position'   => '90',
        ),
        'oui_video_vimeo_portrait' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '100',
        ),
        'oui_video_vimeo_title' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'widget'     => 'yesnoradio',
            'position'   => '110',
        ),
        'oui_video_youtube_no_cookie' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '00',
        ),
        'oui_video_youtube_autohide' => array(
            'value'      => 2,
            'event'      => 'oui_video_youtube',
            'widget'     => 'oui_video_youtube_012',
            'position'   => '40',
        ),
        'oui_video_youtube_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '50',
        ),
        'oui_video_youtube_user_prefs' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '60',
        ),
        'oui_video_youtube_color' => array(
            'value'      => 'red',
            'event'      => 'oui_video_youtube',
            'widget'     => 'oui_video_youtube_color',
            'position'   => '70',
        ),
        'oui_video_youtube_controls' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'oui_video_youtube_012',
            'position'   => '80',
        ),
        'oui_video_youtube_api' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '100',
        ),
        'oui_video_youtube_full_screen' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '120',
        ),
        'oui_video_youtube_lang' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'widget'     => 'text_input',
            'position'   => '130',
        ),
        'oui_video_youtube_annotations' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'text_input',
            'position'   => '140',
        ),
        'oui_video_youtube_loop' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '170',
        ),
        'oui_video_youtube_modest_branding' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '180',
        ),
        'oui_video_youtube_origin' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'widget'     => 'text_input',
            'position'   => '190',
        ),
        'oui_video_youtube_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'widget'     => 'text_input',
            'position'   => '200',
        ),
        'oui_video_youtube_related' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '230',
        ),
        'oui_video_youtube_info' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'widget'     => 'yesnoradio',
            'position'   => '240',
        ),
        'oui_video_youtube_theme' => array(
            'value'      => 'dark',
            'event'      => 'oui_video_youtube',
            'widget'     => 'oui_video_theme',
            'position'   => '260',
        ),
        'oui_video_dailymotion_api' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'oui_video_dailymotion_api',
            'position'   => '40',
        ),
        'oui_video_dailymotion_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '50',
        ),
        'oui_video_dailymotion_controls' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '60',
        ),
        'oui_video_dailymotion_related' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '70',
        ),
        'oui_video_dailymotion_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'text_input',
            'position'   => '80',
        ),
        'oui_video_dailymotion_mute' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '90',
        ),
        'oui_video_dailymotion_origin' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'text_input',
            'position'   => '100',
        ),
        'oui_video_dailymotion_quality' => array(
            'value'      => 'auto',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'oui_video_dailymotion_quality',
            'position'   => '110',
        ),
        'oui_video_dailymotion_sharing' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '120',
        ),
        'oui_video_dailymotion_lang' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'text_input',
            'position'   => '140',
        ),
        'oui_video_dailymotion_syndication' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'text_input',
            'position'   => '150',
        ),
        'oui_video_dailymotion_color' => array(
            'value'      => 'ffcc33',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'text_input',
            'position'   => '160',
        ),
        'oui_video_dailymotion_logo' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '170',
        ),
        'oui_video_dailymotion_info' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'yesnoradio',
            'position'   => '180',
        ),
        'oui_video_dailymotion_theme' => array(
            'value'      => 'dark',
            'event'      => 'oui_video_dailymotion',
            'widget'     => 'oui_video_theme',
            'position'   => '190',
        ),
    );
    return $prefList;
}

/**
 * Install plugin prefs
 */
function oui_video_install()
{
    $prefList = oui_video_preflist();

    foreach ($prefList as $pref => $options) {
        if (get_pref($pref, null) === null) {
            set_pref(
                $pref,
                $options['value'],
                $options['event'],
                defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
                $options['widget'],
                $options['position']
            );
        }
    }
}

/**
 * Plugin prefs function
 * Provider list
 */
function oui_video_provider($name, $val)
{
    $vals = array(
        'dailymotion' => gTxt('oui_video_provider_dailymotion'),
        'vimeo'       => gTxt('oui_video_provider_vimeo'),
        'youtube'     => gTxt('oui_video_provider_youtube'),
    );
    return selectInput($name, $vals, $val, '1');
}

/**
 * Plugin prefs function
 * Theme parameter values
 */
function oui_video_theme($name, $val)
{
    $vals = array(
        'dark'  => gTxt('oui_video_dark'),
        'light' => gTxt('oui_video_light'),
    );
    return selectInput($name, $vals, $val);
}

/**
 * Plugin prefs function
 * Theme parameter values
 */
function oui_video_youtube_012($name, $val)
{
    $vals = array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
    );
    return selectInput($name, $vals, $val);
}

/**
 * Plugin prefs function
 * Youtube color parameter values
 */
function oui_video_youtube_color($name, $val)
{
    $vals = array(
        'red'   => gTxt('oui_video_red'),
        'white' => gTxt('oui_video_white'),
    );
    return selectInput($name, $vals, $val);
}

/**
 * Plugin prefs function
 * Dailymotion quality parameter values
 */
function oui_video_dailymotion_quality($name, $val)
{
    $vals = array(
        'auto' => 'auto',
        '240'  => '240',
        '380'  => '380',
        '480'  => '480',
        '720'  => '720',
        '1080' => '1080',
        '1440' => '1440',
        '2160' => '2160',
    );
    return selectInput($name, $vals, $val);
}

/**
 * Plugin prefs function
 * Dailymotion api parameter values
 */
function oui_video_dailymotion_api($name, $val)
{
    $vals = array(
        '0'           => '0',
        'postMessage' => 'postMessage',
        'location'    => 'location',
        '1'           => '1',
    );
    return selectInput($name, $vals, $val);
}

/**
 * Plugin prefs function
 * Custom fields
 */
function oui_video_custom_fields($name, $val)
{
    $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

    if ($custom_fields) {
        $vals = array();
        foreach ($custom_fields as $row) {
            $vals[$row['val']] = $row['val'];
        }
        $vals['article_image'] = gtxt('article_image');
        $vals['excerpt'] = gtxt('excerpt');
        return selectInput($name, $vals, $val, 'true');
    }
    return gtxt('no_custom_fields_recorded');
}
