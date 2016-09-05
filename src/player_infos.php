<?php

/**
 * Get provider related variables and associate attributes to player paramaters
 *
 * @param string $provider The video provider
 * @param string $video_id The video id
 * @param string $no_cookie The no_cookie attribute or pref value (Youtube)
 */
function _oui_video_player_infos($provider, $video_id, $no_cookie)
{
    switch ($provider) {
        case 'vimeo':
            $src = '//player.vimeo.com/video/' . $video_id;
            $params = array(
                'autopause' => array('autopause' => '1'),
                'autoplay'  => array('autoplay' => '0'),
                'badge'     => array('badge' => '1'),
                'byline'    => array('byline' => '1'),
                'color'     => array('color' => '00adef'),
                'loop'      => array('loop' => '0'),
                'player_id' => array('player_id' => ''),
                'portrait'  => array('portrait' => '1'),
                'title'     => array('title' => '1')
            );
            $player_infos = array($src => $params);
            return $player_infos;
            break;
        case 'youtube':
            $cookie = ($no_cookie || (!$no_cookie && get_pref('oui_video_youtube_no_cookie') === '1')) ? '-nocookie' : '';
            $src = '//www.youtube' . $cookie . '.com/embed/' . $video_id;
            $params = array(
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
            $player_infos = array($src => $params);
            return $player_infos;
            break;
        case 'dailymotion':
            $src = '//www.dailymotion.com/embed/video/' . $video_id;
            $params = array(
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
            $player_infos = array($src => $params);
            return $player_infos;
            break;
        default:
            trigger_error('Unknown video provider.');
            return;
    }
}
