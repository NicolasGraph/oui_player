<?php

/*
 * Display a video
 */
function oui_video($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_video.
    $oui_video = new Oui_Video;

    /*
     * Set and check Tag attributes
     */

    // Get tag attributes.
    $get_atts = $oui_video->getAtts(__FUNCTION__);

    // Set the array to be used by latts()
    foreach ($get_atts as $att => $options) {
        $get_atts[$att] = $options['default'];
    }

    // Set tag attributes.
    extract(lAtts($get_atts, $atts));

    // Look for wrong attribute values.
    $oui_video->checkAtts(__FUNCTION__, $atts);

    /*
     * Get video infos
     */

    // Check if the video is recognize as a video url.
    $match = $oui_video->videoInfos($video);

    // Check if the video was recognize as a video url.
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
     * Get player Infos
     */

    // Define which src to use for Youtube.
    $no_cookie ?: $no_cookie = get_pref('oui_video_youtube_no_cookie');

    // Returns player infos
    $oui_video_provider = 'Oui_Video_' . $provider;
    $player_infos = (new $oui_video_provider)->playerInfos($provider, $no_cookie);

    /*
     * Prepare player parameters for the output
     */

    $src = $player_infos['src'] . $video_id;
    $params = $player_infos['params'];

    // Create a list of needed parameters
    $used_params = array();

    foreach ($params as $param => $infos) {
        if ($param !== 'no_cookie') {
            $pref = get_pref('oui_video_' . $provider . '_' . $param);
            $default = $infos['default'];
            $att_name = str_replace('-', '_', $param);
            $att = $$att_name;

            // Add modified attributes or prefs values as player parameters.
            if ($att === '' && $pref !== $default) {
                // Remove # from the color pref as a color type is used for the pref input.
                $param === 'color' ? $pref = str_replace('#', '', $pref) : '';
                $used_params[] = $param . '=' . $pref;
            } elseif ($att !== '') {
                // Remove the # in the color attribute just in case…
                $att_name === 'color' ? $att = str_replace('#', '', $att) : '';
                $used_params[] = $param . '=' . $att;
            }
        }
    }

    // Check if some player parameters has been used.
    if (!empty($used_params)) {
        // Append them to the src.
        $src .= '?' . implode('&amp;', $used_params);
    }

    /*
     * Get the player size for the output
     */

    // Set an array to be used to get the player size.
    $dims = array(
        'width' => $width,
        'height' => $width,
        'ratio' => $width,
    );

    // Replace empty tag values by prefs.
    foreach ($dims as $dim => $value) {
        empty($value) ? $dims[$dim] = get_pref('oui_video_' .$dim) : '';
    }

    // Get the video size.
    $video_size = $oui_video->playerSize($dims);

    $width = $video_size['width'];
    $height = $video_size['height'];

    /*
     * Set the output
     */

    $out = '<iframe
        width="' . $width . '"
        height="' . $height . '"
        src="' . $src . '"
        frameborder="0"
        allowfullscreen>
    </iframe>';

    return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
}

/*
 * Check a video url and its provider if provided.
 */
function oui_if_video($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_video.
    $oui_video = new Oui_Video;

    /*
     * Set and check Tag attributes
     */

    // Get tag attributes.
    $get_atts = $oui_video->getAtts(__FUNCTION__);

    // Set the array to be used by latts()
    foreach ($get_atts as $att => $options) {
        $get_atts[$att] = $options['default'];
    }

    // Set tag attributes.
    extract(lAtts($get_atts, $atts));

    // Look for wrong attribute values.
    $oui_video->checkAtts(__FUNCTION__, $atts);

    /*
     * Get video infos
     */

    // Check if the video is recognize as a video url.
    $video_infos = $oui_video->videoInfos($video);
    $result = $video_infos ? $video_infos : $oui_video->videoInfos($thisarticle[strtolower($video)]);

    // If the provider is provided check it too.
    if ($provider) {
        if ($provider === $result['provider']) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    // Txp 4.6+ don't need EvalElse() anymore.
    return defined('PREF_PLUGIN') ? parse($thing, $result) : parse(EvalElse($thing, $result));
}
