<?php

# --- BEGIN PLUGIN CODE ---
if (class_exists('\Textpattern\Tag\Registry')) {
    // Register Textpattern tags for TXP 4.6+.
    Txp::get('\Textpattern\Tag\Registry')
        ->register('oui_video')
        ->register('oui_if_video');
}


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
 * Set prefs through:
 *
 * PREF_PLUGIN for 4.5
 * PREF_ADVANCED for 4.6+
 */
function oui_video_preflist()
{
    $prefList = array(
        'oui_video_custom_field' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_custom_fields',
            'position'   => '10',
            'is_private' => false,
        ),
        'oui_video_provider' => array(
            'value'      => '',
            'event'      => 'oui_video',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_provider',
            'position'   => '20',
            'is_private' => false,
        ),
        'oui_video_vimeo_width' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '10',
            'is_private' => false,
        ),
        'oui_video_vimeo_height' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '20',
            'is_private' => false,
        ),
        'oui_video_vimeo_ratio' => array(
            'value'      => '4:3',
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '30',
            'is_private' => false,
        ),
        'oui_video_vimeo_autopause' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '40',
            'is_private' => false,
        ),
        'oui_video_vimeo_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '40',
            'is_private' => false,
        ),
        'oui_video_vimeo_badge' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '50',
            'is_private' => false,
        ),
        'oui_video_vimeo_byline' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '60',
            'is_private' => false,
        ),
        'oui_video_vimeo_color' => array(
            'value'      => '00adef',
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '70',
            'is_private' => false,
        ),
        'oui_video_vimeo_loop' => array(
            'value'      => 0,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '80',
            'is_private' => false,
        ),
        'oui_video_vimeo_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '90',
            'is_private' => false,
        ),
        'oui_video_vimeo_portrait' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '100',
            'is_private' => false,
        ),
        'oui_video_vimeo_title' => array(
            'value'      => 1,
            'event'      => 'oui_video_vimeo',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '110',
            'is_private' => false,
        ),
        'oui_video_youtube_no_cookie' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '00',
            'is_private' => false,
        ),
        'oui_video_youtube_width' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '10',
            'is_private' => false,
        ),
        'oui_video_youtube_height' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '20',
            'is_private' => false,
        ),
        'oui_video_youtube_ratio' => array(
            'value'      => '4:3',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '30',
            'is_private' => false,
        ),
        'oui_video_youtube_autohide' => array(
            'value'      => 2,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_youtube_autohide',
            'position'   => '40',
            'is_private' => false,
        ),
        'oui_video_youtube_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '50',
            'is_private' => false,
        ),
        'oui_video_youtube_user_prefs' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '60',
            'is_private' => false,
        ),
        'oui_video_youtube_color' => array(
            'value'      => 'red',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_youtube_color',
            'position'   => '70',
            'is_private' => false,
        ),
        'oui_video_youtube_controls' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '80',
            'is_private' => false,
        ),
        'oui_video_youtube_api' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '100',
            'is_private' => false,
        ),
        'oui_video_youtube_end' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '110',
            'is_private' => false,
        ),
        'oui_video_youtube_full_screen' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '120',
            'is_private' => false,
        ),
        'oui_video_youtube_lang' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '130',
            'is_private' => false,
        ),
        'oui_video_youtube_annotations' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '140',
            'is_private' => false,
        ),
        'oui_video_youtube_loop' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '170',
            'is_private' => false,
        ),
        'oui_video_youtube_modest_branding' => array(
            'value'      => 0,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '180',
            'is_private' => false,
        ),
        'oui_video_youtube_origin' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '190',
            'is_private' => false,
        ),
        'oui_video_youtube_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '200',
            'is_private' => false,
        ),
        'oui_video_youtube_related' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '230',
            'is_private' => false,
        ),
        'oui_video_youtube_info' => array(
            'value'      => 1,
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '240',
            'is_private' => false,
        ),
        'oui_video_youtube_start' => array(
            'value'      => '',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '250',
            'is_private' => false,
        ),
        'oui_video_youtube_theme' => array(
            'value'      => 'dark',
            'event'      => 'oui_video_youtube',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_theme',
            'position'   => '260',
            'is_private' => false,
        ),
        'oui_video_dailymotion_width' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '10',
            'is_private' => false,
        ),
        'oui_video_dailymotion_height' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '20',
            'is_private' => false,
        ),
        'oui_video_dailymotion_ratio' => array(
            'value'      => '4:3',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '30',
            'is_private' => false,
        ),
        'oui_video_dailymotion_api' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_dailymotion_api',
            'position'   => '40',
            'is_private' => false,
        ),
        'oui_video_dailymotion_autoplay' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '50',
            'is_private' => false,
        ),
        'oui_video_dailymotion_controls' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '60',
            'is_private' => false,
        ),
        'oui_video_dailymotion_related' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '70',
            'is_private' => false,
        ),
        'oui_video_dailymotion_player_id' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '80',
            'is_private' => false,
        ),
        'oui_video_dailymotion_mute' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '90',
            'is_private' => false,
        ),
        'oui_video_dailymotion_origin' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '100',
            'is_private' => false,
        ),
        'oui_video_dailymotion_quality' => array(
            'value'      => 'auto',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_dailymotion_quality',
            'position'   => '110',
            'is_private' => false,
        ),
        'oui_video_dailymotion_sharing' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '120',
            'is_private' => false,
        ),
        'oui_video_dailymotion_start' => array(
            'value'      => 0,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '130',
            'is_private' => false,
        ),
        'oui_video_dailymotion_lang' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '140',
            'is_private' => false,
        ),
        'oui_video_dailymotion_syndication' => array(
            'value'      => '',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '150',
            'is_private' => false,
        ),
        'oui_video_dailymotion_color' => array(
            'value'      => 'ffcc33',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'text_input',
            'position'   => '160',
            'is_private' => false,
        ),
        'oui_video_dailymotion_logo' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '170',
            'is_private' => false,
        ),
        'oui_video_dailymotion_info' => array(
            'value'      => 1,
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'yesnoradio',
            'position'   => '180',
            'is_private' => false,
        ),
        'oui_video_dailymotion_theme' => array(
            'value'      => 'dark',
            'event'      => 'oui_video_dailymotion',
            'visibility' => defined('PREF_PLUGIN') ? PREF_PLUGIN : PREF_ADVANCED,
            'widget'     => 'oui_video_theme',
            'position'   => '190',
            'is_private' => false,
        ),
    );
    return $prefList;
}


function oui_video_install()
{
    $prefList = oui_video_preflist();

    foreach ($prefList as $pref => $options) {
        if (get_pref($pref, null) === null) {
            set_pref(
                $pref,
                $options['value'],
                $options['event'],
                $options['visibility'],
                $options['widget'],
                $options['position'],
                $options['is_private']
            );
        }
    }
}


/**
 * Provider list
 */
function oui_video_provider($name, $val)
{
    $vals = array(
        'dailymotion' => gTxt('oui_video_provider_dailymotion'),
        'vimeo' => gTxt('oui_video_provider_vimeo'),
        'youtube' => gTxt('oui_video_provider_youtube'),
    );
    return selectInput($name, $vals, $val, '1');
}


/**
 * Theme parameter values
 */
function oui_video_theme($name, $val)
{
    $vals = array(
        'dark' => gTxt('oui_video_dark'),
        'light' => gTxt('oui_video_light'),
    );
    return selectInput($name, $vals, $val);
}


/**
 * Theme parameter values
 */
function oui_video_youtube_autohide($name, $val)
{
    $vals = array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
    );
    return selectInput($name, $vals, $val);
}


/**
 * Theme parameter values
 */
function oui_video_youtube_color($name, $val)
{
    $vals = array(
        'red' => gTxt('oui_video_red'),
        'white' => gTxt('oui_video_white'),
    );
    return selectInput($name, $vals, $val);
}


/**
 * Theme parameter values
 */
function oui_video_dailymotion_quality($name, $val)
{
    $vals = array(
        'auto' => 'auto',
        '240' => '240',
        '380' => '380',
        '480' => '480',
        '720' => '720',
        '1080' => '1080',
        '1440' => '1440',
        '2160' => '2160',
    );
    return selectInput($name, $vals, $val);
}


/**
 * Theme parameter values
 */
function oui_video_dailymotion_api($name, $val)
{
    $vals = array(
        '0' => '0',
        'postMessage' => 'postMessage',
        'location' => 'location',
        '1' => '1',
    );
    return selectInput($name, $vals, $val);
}



/**
 * Custom field list
 */
function oui_video_custom_fields($name, $val)
{
    $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

    if ($custom_fields) {
        $vals = array();
        foreach ($custom_fields as $row) {
            $vals[$row['val']] = $row['val'];
        }
        return selectInput($name, $vals, $val, 'true');
    }
    return gtxt('no_custom_fields_recorded');
}

/**
 * Main tag
 */
function oui_video($atts, $thing)
{
    global $thisarticle;

    extract(lAtts(array(
        'video'        => '',
        'provider'     => '',
        'width'        => '',
        'height'       => '',
        'ratio'        => '',
        'annotations'  => '', // Youtube (1)
        'api'          => '', // Youtube (0), Dailymotion (false).
        'autohide'     => '', // Youtube (2).
        'autoplay'     => '', // Vimeo (1), Youtube (0), Dailymotion (false).
        'autopause'    => '', // Vimeo (1).
        'badge'        => '', // Vimeo (1).
        'byline'       => '', // Vimeo (1).
        'controls'     => '', // Youtube (1), Dailymotion (true).
        'color'        => '', // Youtube (red), Dailymotion (ffcc33).
        'end'          => '', // Youtube.
        'info'         => '', // Youtube (1), Dailymotion (true).
        'full_screen'  => '', // Youtube (1).
        'lang'         => '', // Youtube, Dailymotion.
        'logo'         => '', // Youtube (1), Dailymotion (true).
        'loop'         => '', // Vimeo (0), Youtube (0).
        'mute'         => '', // Dailymotion (false).
        'no_cookie'    => '', // Youtube.
        'origin'       => '', // Youtube, Dailymotion.
        'player_id'    => '', // Vimeo, Youtube, Dailymotion.
        'portrait'     => '', // Vimeo (1).
        'quality'      => '', // Dailymotion.
        'related'      => '', // Youtube (1), Dailymotion (true).
        'sharing'      => '', // Dailymotion (true).
        'start'        => '', // Youtube, Dailymotion.
        'user_prefs'   => '', // Youtube (1).
        'syndication'  => '', // Dailymotion.
        'theme'        => '', // Youtube.
        'label'        => '',
        'labeltag'     => '',
        'wraptag'      => '',
        'class'        => __FUNCTION__
    ), $atts));

    $modest_branding = $logo === '0' ? '1' : '';

    if (!$info) {
        $portrait = '0';
        $title = '0';
        $byline = '0';
    }

    /*
     * Check for video URL to extract provider and ID from
     */
    $match = _oui_video($video);
    if (!$match) {
        $match_provider = $provider ? strtolower($provider) : get_pref('oui_video_provider');
        $custom = $video ? strtolower($video) : strtolower(get_pref('oui_video_custom_field'));
        isset($thisarticle[$custom]) ? $video = $thisarticle[$custom] : '';
        $video_id = $video;
    } else {
        $match_provider = key($match);
        $video_id = $match[$match_provider];
    }

    // Provider is ok, here are the related variable…
    switch ($match_provider) {
        case 'vimeo':
            $src = '//player.vimeo.com/video/' . $video_id;
            $qAtts = array(
                'autopause' => array('autopause' => '1'),
                'autoplay'  => array('autoplay' => '0'),
                'badge'     => array('badge' => '1'),
                'byline'    => array('byline' => '1'),
                'color'     => array('color' => '00adef'),
                'loop'      => array('loop' => '0'),
                'player_id' => array('color' => ''),
                'portrait'  => array('portrait' => '1'),
                'title'     => array('info' => '1')
            );
            break;
        case 'youtube':
            $cookie = ($no_cookie || (!$no_cookie && get_pref('oui_video_youtube_no_cookie') === '1')) ? '-nocookie' : '';
            $src = '//www.youtube' . $cookie . '.com/embed/' . $video_id;
            $qAtts = array(
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
            );
            break;
        case 'dailymotion':
            $src = '//www.dailymotion.com/embed/video/' . $video_id;
            $qAtts = array(
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
            );
            break;
        default:
            trigger_error('Unknown video provider.');
            return;
    }

    /*
     * Check variable values and store player parameters
     */
    $qString = array();
    foreach ($qAtts as $parameter => $infos) {
        $attribute = key($infos);
        $value = $$attribute;
        $default = $infos[$attribute];
        if ($match_provider === 'dailymotion') {
            // Bloody Dailymotion…
            $value === 0 ? $value = 'false' : '';
            $value === 1 ? $value = 'true' : '';
        }
        if ($value === '' && get_pref('oui_video_' . $match_provider . '_' . $attribute) !== $default) {
            $qString[] = $parameter . '=' . get_pref('oui_video_' . $match_provider . '_' . $attribute);
        } elseif ($value !== '') {
            $qString[] = $parameter . '=' . $value;
        }
    }


    /*
     * Check if we need to append some parameters.
     */
    if (!empty($qString)) {
        $src .= '?' . implode('&amp;', $qString);
    }

    /*
     * If the width and/or height has not been set
     * we want to calculate new ones using the aspect ratio.
     */
    $pref_width = get_pref('oui_video_' . $match_provider . '_width');
    $pref_height = get_pref('oui_video_' . $match_provider . '_height');
    $width ?: !$pref_width ?: $width = $pref_width;
    $height ?: !$pref_height ?: $height = $pref_height;

    if (!$width || !$height) {
        $toolbarHeight = 25;

        // Work out the aspect ratio.
        $pref_ratio = get_pref('oui_video_' . $match_provider . '_ratio');
        $ratio ?: !$pref_ratio ?: $ratio = $pref_ratio;
        preg_match("/(\d+):(\d+)/", $ratio, $matches);
        if ($matches[0] && $matches[1]!=0 && $matches[2]!=0) {
            $aspect = $matches[1]/$matches[2];
        } else {
            $aspect = 1.333;
        }

        // Calcuate the new width/height.
        if ($width) {
            $height = $width/$aspect + $toolbarHeight;
        } elseif ($height) {
            $width = ($height-$toolbarHeight)*$aspect;
        } else {
            $width = 425;
            $height = 344;
        }
    }

    $out = '<iframe width="'.$width.'" height="'.$height
      .'" src="'.$src.'" frameborder="0" allowfullscreen></iframe>';

    return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
}

/**
 * Conditional tag
 */
function oui_if_video($atts, $thing)
{
    global $thisarticle;

    extract(lAtts(array(
        'video' => null,
        'provider' => ''
    ), $atts));

    $result = _oui_video($video) ? _oui_video($video) : _oui_video($thisarticle[strtolower($video)]);

    if ($provider) {
        if (strtolower($provider) === key($result)) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    return defined('PREF_PLUGIN') ? parse($thing, $result) : parse(EvalElse($thing, $result));
}

/**
 * Url analyze
 */
function _oui_video($video)
{
    if (preg_match('#^https?://((player|www)\.)?vimeo\.com(/video)?/(\d+)#i', $video, $matches)) {
        $match = array('vimeo' => $matches[4]);
        return $match;
    } elseif (preg_match('#^(http|https)?:\/\/www\.youtube\.com(\/watch\?)?([^\&\?\/]+)#i', $video, $matches)) {
        $match = array('youtube' => $matches[3]);
        return $match;
    } elseif (preg_match('#^(http|https)?[:\/\/]+youtu\.be\/([^\&\?\/]+)#i', $video, $matches)) {
        $match = array('youtube' => $matches[2]);
        return $match;
    } elseif (preg_match('#^(http|https)?://www\.dailymotion\.com(/video)?/([A-Za-z0-9]+)#i', $video, $matches)) {
        $match = array('dailymotion' => $matches[3]);
        return $match;
    } elseif (preg_match('#^(http|https)?://dai\.ly(/video)?/([A-Za-z0-9]+)#i', $video, $matches)) {
        $match = array('dailymotion' => $matches[3]);
        return $match;
    }
    return false;
}
