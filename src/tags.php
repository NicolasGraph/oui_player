<?php

/**
 * Main plugin tag
 * Display a video
 */
function oui_video($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_video.
    $oui_video = new Oui_Video;

    // Set tag attributes.
    extract(lAtts($oui_video->getAtts(__FUNCTION__), $atts));

    // Look for wrong attribute values.
    $oui_video->checkAtts(__FUNCTION__, $atts);

    /*
     * Define the video provider and the video id.
     */
    $match = $oui_video->videoInfos($video);

    if (!$match) {
        $provider = $provider ? $provider : get_pref('oui_video_provider');
        $custom = $video ? $video : strtolower(get_pref('oui_video_custom_field'));
        isset($thisarticle[$custom]) ? $video = $thisarticle[$custom] : '';
        $video_id = $video;
    } else {
        $provider = $match['provider'];
        $video_id = $match['id'];
    }

    /*
     * Define player src and parameters.
     */
    $player_infos = $oui_video->playerInfos($provider, $no_cookie);
    $src = $player_infos['src'] . $video_id;
    $params = $player_infos['params'];

    // Create a list of needed parameters
    $used_params = array();

    foreach ($params as $param => $infos) {
        $pref = get_pref('oui_video_' . $provider . '_' . $param);
        $default = $infos['default'];
        $att_name = str_replace('-', '_', $param);
        $att = $$att_name;

        if ($att === '' && $pref !== $default) {
            // if the attribute is empty, get the related pref value.
            $used_params[] = $param . '=' . $pref;
        } elseif ($att !== '') {
            $used_params[] = $param . '=' . $att;
        }
    }

    /*
     * Check if we need to append some parameters.
     */
    if (!empty($used_params)) {
        $src .= '?' . implode('&amp;', $used_params);
    }

    /*
     * If the width and/or height has not been set
     * we want to calculate new ones using the aspect ratio.
     */
    $dims = array(
        'width' => $width,
        'height' => $width,
        'ratio' => $width,
    );
    foreach ($dims as $dim => $value) {
        empty($value) ? $dims[$dim] = get_pref('oui_video_' .$dim) : '';
    }

    // Get the video size.
    $video_size = $oui_video->videoSize($dims);
    $width = $video_size['width'];
    $height = $video_size['height'];

    $out = '<iframe
        width="' . $width . '"
        height="' . $height . '"
        src="' . $src . '"
        frameborder="0"
        allowfullscreen>
    </iframe>';

    return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
}

/**
 * Conditional tag
 * Check a video url and its provider if provided.
 */
function oui_if_video($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_video.
    $oui_video = new Oui_Video;

    // Set tag attributes.
    extract(lAtts($oui_video->getAtts(__FUNCTION__), $atts));

    // Look for wrong attribute values.
    $oui_video->checkAtts(__FUNCTION__, $atts);

    // Check if the plugin is able to catch any info from the provided video.
    $video_infos = $oui_video->videoInfos($video);
    $result = $video_infos ? $video_infos : $oui_video->videoInfos($thisarticle[strtolower($video)]);

    if ($provider) {
        // Is it the right provider?
        if ($provider === $result['provider']) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    // Txp 4.6+ don't need EvalElse() anymore.
    return parse($thing, $result);
}
