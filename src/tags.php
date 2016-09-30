<?php

/*
 * Display a video
 */
function oui_player($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_Player.
    $main_class = Oui_Player::getInstance();

    /*
     * Set and check Tag attributes
     */

    // Get tag attributes.
    $get_atts = $main_class->getAtts(__FUNCTION__);

    // Set the array to be used by latts()
    foreach ($get_atts as $att => $options) {
        $get_atts[$att] = '';
    }

    // Set tag attributes.
    extract(lAtts($get_atts, $atts));

    /*
     * Get video infos
     */

    $play ?: $play = strtolower(get_pref('oui_player_custom_field'));
    $play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;

    // Check class.
    if ($provider) {
        $provider_class = 'Oui_Player_' . $provider;
        $provider_instance = $provider_class::getInstance();
        $match = $provider_instance->getItemInfos($play);
    } else {
        $match = $main_class->getItemInfos($play);
    }

    // Check if the video is recognize as a video url.
    if ($match) {
        $provider = $match['provider'];
        $id = $match['id'];
    } else {
        $id = $play;
    }


    /*
     * Get player Infos
     */

    // Returns player infos
    if (!$provider) {
        $provider_class = 'Oui_Player_' . $provider;
        $provider_instance = $provider_class::getInstance();
    }

    $provider_prefs = strtolower($provider_class);
    $player_infos = $provider_instance->getParams();
    $src = $player_infos['src'] . $id;
    $params = $player_infos['params'];

    /*
     * Prepare player parameters for the output
     */

    // Create a list of needed parameters
    $used_params = array();
    $ignore = array(
        'height',
        'ratio',
        'width',
    );

    foreach ($params as $param => $infos) {
        if (!in_array($param, $ignore)) {
            $pref = get_pref('oui_player_' . $provider . '_' . $param);
            $default = $infos['default'];
            $att_name = str_replace('-', '_', $param);
            $att = $$att_name;

            // Add modified attributes or prefs values as player parameters.
            if ($att === '' && $pref !== $default) {
                // Remove # from the color pref as a color type is used for the pref input.
                $used_params[] = $param . '=' . str_replace('#', '', $pref);
            } elseif ($att !== '') {
                // Remove the # in the color attribute just in case…
                $used_params[] = $param . '=' . str_replace('#', '', $att);
            }
        }
    }

    /*
     * Get the player size for the output
     */

    // Set an array to be used to get the player size.
    $dims = array(
        'width'  => $width ? $width : get_pref($provider_prefs . '_width'),
        'height' => $height ? $height : get_pref($provider_prefs . '_height'),
        'ratio'  => $ratio ? $ratio : get_pref($provider_prefs . '_ratio'),
    );

    // Check if some player parameters has been used.
    $output = $provider_instance->getOutput($src, $used_params, $dims);

    return doLabel($label, $labeltag).(($wraptag) ? doTag($output, $wraptag, $class) : $output);
}

/*
 * Check a video url and its provider if provided.
 */
function oui_if_player($atts, $thing)
{
    global $thisarticle;

    // Instanciate Oui_Player.
    $main_class = Oui_Player::getInstance();

    /*
     * Set and check Tag attributes
     */

    // Get tag attributes.
    $get_atts = $main_class->getAtts(__FUNCTION__);

    // Set the array to be used by latts()
    foreach ($get_atts as $att => $options) {
        $get_atts[$att] = $options['default'];
    }

    // Set tag attributes.
    extract(lAtts($get_atts, $atts));

    /*
     * Get video infos
     */

    // Check if the video is recognize as a video url.
    $play_infos = $main_class->getItemInfos($play);
    $result = $play_infos ? $play_infos : $main_class->getItemInfos($thisarticle[strtolower($play)]);

    // If the provider is provided check it too.
    if ($provider) {
        if ($provider === $result['provider']) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    return parse($thing, $result);
}
