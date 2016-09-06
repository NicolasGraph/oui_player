<?php

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
    return selectInput($name, $vals, $val);
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
        return selectInput($name, $vals, $val);
    }
    return gtxt('no_custom_fields_recorded');
}
