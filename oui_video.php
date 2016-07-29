<?php

# --- BEGIN PLUGIN CODE ---
if (class_exists('\Textpattern\Tag\Registry')) {
    // Register Textpattern tags for TXP 4.6+.
    Txp::get('\Textpattern\Tag\Registry')
        ->register('oui_video')
        ->register('oui_if_video');
}


function oui_video($atts, $thing)
{
    global $thisarticle, $oui_video_provider;

    extract(lAtts(array(
        'video'        => '',
        'custom_field' => 'Video ID',
        'width'        => '0',
        'height'       => '0',
        'ratio'        => '4:3',
        'annotations'  => '', // Youtube (1)
        'api'          => '', // Youtube (0), Dailymotion (false).
        'autohide'     => '', // Youtube (2).
        'autoplay'     => '', // Vimeo (1), Youtube (0), Dailymotion (false).
        'autopause'    => '', // Vimeo (1).
        'badge'        => '', // Vimeo (1).
        'byline'       => '', // Vimeo (1).
        'controls'     => '', // Youtube (1), Dailymotion (true).
        'color'        => '', // Youtube (red), Dailymotion (ffcc33).
        'info'         => '', // Youtube (1), Dailymotion (true).
        'full_screen'  => '', // Youtube (1).
        'lang'         => '', // Youtube.
        'logo'         => '', // Youtube (1), Dailymotion (true).
        'loop'         => '', // Vimeo (0), Youtube (0).
        'no_cookie'    => '1', // Youtube.
        'origin'       => '', // Youtube, Dailymotion.
        'player_id'    => '', // Vimeo, Youtube, Dailymotion.
        'portrait'     => '', // Vimeo (1).
        'quality'      => '', // Dailymotion.
        'related'      => '', // Youtube (1), Dailymotion (true).
        'start'        => '', // Youtube, Dailymotion.
        'user_prefs'   => '', // Youtube (1).
        'syndication'  => '', // Dailymotion.
        'theme'        => '', // Youtube.
        'label'        => '',
        'labeltag'     => '',
        'wraptag'      => '',
        'class'        => __FUNCTION__
    ), $atts));

    $modest_branding = !$logo ? '1' : '';

    $custom_field = strtolower($custom_field);
    if (!$video && isset($thisarticle[$custom_field])) {
        $video = $thisarticle[$custom_field];
    }

    /*
     * Check for video URL to extract ID from
     */
    $match = _oui_video($video);

    if ($match) {
        $video = $match;
    } else {
        trigger_error("oui_video was not able to recognize your video");
        return;
    }

    switch ($oui_video_provider) {
        case 'vimeo':
            $src = '//player.vimeo.com/video/' . $video;
            /*
             * Attributes.
             */
            $qAtts = array(
                'autopause' => array($autopause => '0, 1'),
                'autoplay'  => array($autoplay => '0, 1'),
                'badge'     => array($badge => '0, 1'),
                'byline'    => array($byline => '0, 1'),
                'color'     => $color,
                'loop'      => array($loop => '0, 1'),
                'player_id' => $player_id,
                'portrait'  => array($portrait => '0, 1'),
                'title'     => array($info => '0, 1')
            );
            break;
        case 'youtube':
            $src = '//www.youtube' . ($no_cookie ? '-nocookie' : '') . '.com/embed/' . $video;
            /*
             * Attributes.
             */
            $qAtts = array(
                'autohide'       => array($autohide => '0, 1, 2'),
                'autoplay'       => array($autoplay => '0, 1'),
                'cc_load_policy' => array($user_prefs => '0, 1'),
                'color'          => array($color => 'red, white'),
                'controls'       => array($controls => '0, 1, 2'),
                'enablejsapi'    => array($api => '0, 1'),
                'fs'             => array($full_screen => '0, 1'),
                'iv_load_policy' => array($annotations => '1, 3'),
                'lang'           => array($loop => '0, 1'),
                'loop'           => array($loop => '0, 1'),
                'modestbranding' => array($modest_branding => '0, 1'),
                'playerapiid'    => array($player_id => '0, 1'),
                'rel'            => array($related => '0, 1'),
                'start'          => $start,
                'showinfo'       => array($info => '0, 1'),
                'theme'          => array($theme => 'dark, light')
            );
            break;
        case 'dailymotion':
            $src = '//www.dailymotion.com/embed/video/' . $video;
            /*
             * Attributes.
             */
            $qAtts = array(
                'api'                  => array($api => 'postMessage, fragment, location'),
                'autoplay'             => array($autoplay => '0, 1'),
                'controls'             => array($controls => '0, 1'),
                'endscreen-enable'     => array($related => '0, 1'),
                'id'                   => $player_id,
                'origin'               => $origin,
                'quality'              => array($quality => '240, 380, 480, 720, 1080, 1440, 2160'),
                'start'                => $start,
                'syndication'          => $syndication,
                'ui-highlight'         => $color,
                'ui-logo'              => array($logo => '0, 1'),
                'ui-start-screen-info' => array($info => '0, 1'),
            );
            break;
    }

    $qString = array();

    foreach ($qAtts as $att => $value) {
        if ($value) {
            if (!is_array($value)) {
                $qString[] = $att . '=' . $value;
            } else {
                foreach ($value as $val => $valid) {
                    if ($val) {
                        if (in_list($val, $valid)) {
                            $qString[] = $att . '=' . $val;
                        } else {
                            trigger_error(
                                "unknown attribute value; oui_dailymotion " . $att .
                                " attribute accepts the following values: " . $valid
                            );
                            return;
                        }
                    }
                }
            }
        }
    };

    /*
     * Check if we need to append a query string to the video src.
     */
    if (!empty($qString)) {
        $src .= '?' . implode('&amp;', $qString);
    }

    /*
     * If the width and/or height has not been set we want to calculate new
     * ones using the aspect ratio.
     */
    if (!$width || !$height) {
        $toolbarHeight = 25;

        // Work out the aspect ratio.
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


function oui_if_video($atts, $thing)
{
    global $thisarticle, $oui_video_provider;

    extract(lAtts(array(
        'custom_field' => null,
        'video' => null,
        'provider' => ''
    ), $atts));

    $result = $video ? _oui_video($video) : _oui_video($thisarticle[strtolower($custom_field)]);

    if ($provider) {
        if (strtolower($provider) === $oui_video_provider) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    return defined('PREF_PLUGIN') ? parse($thing, $result) : parse(EvalElse($thing, $result));
}


function _oui_video($video)
{
    global $oui_video_provider;

    if (preg_match('#^https?://((player|www)\.)?vimeo\.com(/video)?/(\d+)#i', $video, $matches)) {
        $oui_video_provider = 'vimeo';
        return $matches[4];
    } elseif (preg_match('#^(http|https)?:\/\/www\.youtube\.com(\/watch\?)?([^\&\?\/]+)#i', $video, $matches)) {
        $oui_video_provider = 'youtube';
        return $matches[3];
    } elseif (preg_match('#^(http|https)?[:\/\/]+youtu\.be\/([^\&\?\/]+)#i', $video, $matches)) {
        $oui_video_provider = 'youtube';
        return $matches[2];
    } elseif (preg_match('#^(http|https)?://www\.dailymotion\.com(/video)?/([A-Za-z0-9]+)#i', $video, $matches)) {
        $oui_video_provider = 'dailymotion';
        return $matches[3];
    } elseif (preg_match('#^(http|https)?://dai\.ly(/video)?/([A-Za-z0-9]+)#i', $video, $matches)) {
        $oui_video_provider = 'dailymotion';
        return $matches[3];
    }
    return false;
}
