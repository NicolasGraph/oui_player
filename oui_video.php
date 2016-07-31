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
    global $thisarticle;

    extract(lAtts(array(
        'video'        => '',
        'provider'     => '',
        'custom_field' => 'Video',
        'width'        => '0',
        'height'       => '0',
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
        'no_cookie'    => '1', // Youtube.
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

    $modest_branding = !$logo ? '1' : '';

    $custom_field = strtolower($custom_field);
    if (!$video && isset($thisarticle[$custom_field])) {
        $video = $thisarticle[$custom_field];
    }

    /*
     * Check for video URL to extract provider and ID from
     */
    $match = _oui_video($video);

    if (!$match) {
        trigger_error("oui_video was not able to recognize your video");
        return;
    } else {
        $match_provider = key($match);
        $video_id = $match[$match_provider];
        if ($provider && strtolower($provider) !== $match_provider) {
            // Wrong provider, do nothing.
            return;
        } else {
            // Provider is ok, here are the related variable…
            switch ($match_provider) {
                case 'vimeo':
                    $src = '//player.vimeo.com/video/' . $video_id;
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
                    $src = '//www.youtube' . ($no_cookie ? '-nocookie' : '') . '.com/embed/' . $video_id;
                    $qAtts = array(
                        'autohide'       => array($autohide => '0, 1, 2'),
                        'autoplay'       => array($autoplay => '0, 1'),
                        'cc_load_policy' => array($user_prefs => '0, 1'),
                        'color'          => array($color => 'red, white'),
                        'controls'       => array($controls => '0, 1, 2'),
                        'enablejsapi'    => array($api => '0, 1'),
                        'end'            => $end,
                        'fs'             => array($full_screen => '0, 1'),
                        'hl'             => $lang,
                        'iv_load_policy' => array($annotations => '1, 3'),
                        'lang'           => array($loop => '0, 1'),
                        'loop'           => array($loop => '0, 1'),
                        'modestbranding' => array($modest_branding => '0, 1'),
                        'origin'         => $origin,
                        'playerapiid'    => array($player_id => '0, 1'),
                        'rel'            => array($related => '0, 1'),
                        'start'          => $start,
                        'showinfo'       => array($info => '0, 1'),
                        'theme'          => array($theme => 'dark, light')
                    );
                    break;
                case 'dailymotion':
                    $src = '//www.dailymotion.com/embed/video/' . $video_id;
                    $qAtts = array(
                        'api'                  => array($api => 'postMessage, location, 1'),
                        'autoplay'             => array(boolval($autoplay) => 'false, true'),
                        'controls'             => array(boolval($controls) => 'false, true'),
                        'endscreen-enable'     => array(boolval($related) => 'false, true'),
                        'id'                   => $player_id,
                        'mute'                 => array(boolval($mute) => 'false, true'),
                        'origin'               => $origin,
                        'quality'              => array($quality => '240, 380, 480, 720, 1080, 1440, 2160'),
                        'sharing-enable'       => array(boolval($mute) => 'false, true'),
                        'start'                => $start,
                        'subtitles-default'    => $lang,
                        'syndication'          => $syndication,
                        'ui-highlight'         => $color,
                        'ui-logo'              => array(boolval($logo) => 'false, true'),
                        'ui-theme'             => array($theme => 'dark, light')
                        'ui-start-screen-info' => array(boolval($info) => 'false, true'),
                    );
                    break;
            }

            /*
             * Check variable values and store player parameters
             */
            $qString = array();

            foreach ($qAtts as $att => $value) {
                if ($value) {
                    if (!is_array($value)) {
                        $qString[] = $att . '=' . $value;
                    } else {
                        foreach ($value as $val => $valid) {
                            if (in_list($val, $valid)) {
                                $qString[] = $att . '=' . $val;
                            } else {
                                trigger_error(
                                    "unknown attribute value; the " . $att .
                                    " attribute accepts the following values: " . $valid
                                );
                                return;
                            }
                        }
                    }
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
    }
}


function oui_if_video($atts, $thing)
{
    global $thisarticle;

    extract(lAtts(array(
        'custom_field' => null,
        'video' => null,
        'provider' => ''
    ), $atts));

    $result = $video ? _oui_video($video) : _oui_video($thisarticle[strtolower($custom_field)]);

    if ($provider) {
        if (strtolower($provider) === key($result)) {
            $result = $result ? true : false;
        } else {
            $result = false;
        }
    }

    return defined('PREF_PLUGIN') ? parse($thing, $result) : parse(EvalElse($thing, $result));
}


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
