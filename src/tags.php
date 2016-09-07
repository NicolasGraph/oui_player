<?php

# --- BEGIN PLUGIN CODE ---
if (class_exists('\Textpattern\Tag\Registry')) {
    // Register Textpattern tags for TXP 4.6+.
    Txp::get('\Textpattern\Tag\Registry')
        ->register('oui_video')
        ->register('oui_if_video');
}

/**
 * Main plugin tag
 * Display a video
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
        'title'        => '', // Vimeo (1).
        'label'        => '',
        'labeltag'     => '',
        'wraptag'      => '',
        'class'        => __FUNCTION__
    ), $atts));

    // Use the logo attribute to alterate the modest_branding Youtube parameter.
    $modest_branding = $logo === '0' ? '1' : '';

    /*
     * Define the video provider and the video id.
     */
    $oui_video = new Oui_Video;
    $match = $oui_video->videoInfos($video);
    if (!$match) {
        $provider = $provider ? strtolower($provider) : get_pref('oui_video_provider');
        $custom = $video ? strtolower($video) : strtolower(get_pref('oui_video_custom_field'));
        isset($thisarticle[$custom]) ? $video = $thisarticle[$custom] : '';
        $video_id = $video;
    } else {
        $provider = key($match);
        $video_id = $match[$provider];
    }

    /*
     * Define the player src and parameters.
     */
    $player_infos = $oui_video->playerInfos($provider, $no_cookie);
    $src = key($player_infos);
    $params = $player_infos[$src];
    $src .= $video_id;

    /*
     * Create a list of needed parameters
     */
    $qString = array();
    foreach ($params as $param => $infos) {
        $att = key($infos);
        $value = $$att;
        $default = $infos[$att];
        if ($provider === 'dailymotion') {
            // Bloody Dailymotion…
            $value === 0 ? $value = 'false' : '';
            $value === 1 ? $value = 'true' : '';
        }
        if ($value === '' && get_pref('oui_video_' . $provider . '_' . $att) !== $default) {
            // Attribute is empty but the related plugin pref was changed, use it.
            $qString[] = $param . '=' . get_pref('oui_video_' . $provider . '_' . $att);
        } elseif ($value !== '') {
            // Attribute is not empty, use it.
            $qString[] = $param . '=' . $value;
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
    $pref_width = get_pref('oui_video_width');
    $pref_height = get_pref('oui_video_height');
    $width ?: !$pref_width ?: $width = $pref_width;
    $height ?: !$pref_height ?: $height = $pref_height;

    if (!$width || !$height) {
        $toolbarHeight = 25;

        // Work out the aspect ratio.
        $pref_ratio = get_pref('oui_video_ratio');
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
 * Check a video url and its provider if provided.
 */
function oui_if_video($atts, $thing)
{
    global $thisarticle;

    extract(lAtts(array(
        'video' => null,
        'provider' => ''
    ), $atts));

    $oui_video = new Oui_Video;
    $video_infos = $oui_video->videoInfos($video);
    $result = $video_infos ? $video_infos : videoInfos($thisarticle[strtolower($video)]);

    if ($provider) {
        if (strtolower($provider) === key($result)) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    return defined('PREF_PLUGIN') ? parse($thing, $result) : parse(EvalElse($thing, $result));
}
