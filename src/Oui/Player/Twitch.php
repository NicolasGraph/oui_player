<?php

class Oui_Player_Twitch extends Oui_Player_Provider
{
    protected $provider = 'Twitch';
    protected $patterns = array('#^((http|https):\/\/(www.)?twitch\.tv\/[\S]+\/(v\/[0-9]+))$#i' => '4');
    protected $src = '//player.twitch.tv/?video=';
    protected $params = array(
        'width'       => array(
            'default' => '640',
        ),
        'height'       => array(
            'default' => '',
        ),
        'ratio'       => array(
            'default' => '16:9',
        ),
        'autoplay' => array(
            'default' => 'true',
            'valid' => array('true', 'false'),
        ),
        'muted' => array(
            'default' => 'false',
            'valid' => array('true', 'false'),
        ),
        'time'       => array(
            'default' => '',
        ),
    );

    /**
     * Get the video provider and the video id from its url
     *
     * @param string $video The video url
     */
    public function getItemInfos($video)
    {

        foreach ($this->patterns as $pattern => $id) {
            if (preg_match($pattern, $video, $matches)) {
                $match = array(
                    'provider' => strtolower($this->provider),
                    'id'       => str_replace('/', '', $matches[$id]),
                );

                return $match;
            }
        }

        return false;
    }
}

$instance = Oui_Player_Twitch::getInstance();
$instance->plugProvider();
