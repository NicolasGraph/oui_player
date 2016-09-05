<?php

/**
 * Video url checking
 * Return the video provider and the video id.
 */
function _oui_video_infos($video)
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
