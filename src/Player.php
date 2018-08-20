<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

 /**
  * Player
  *
  * Manages public side plugin features.
  *
  * @package Oui\Player
  */

namespace Oui\Player;

class Player extends Base implements \Textpattern\Container\ReusableInterface
{
    /**
     * Initial plugin tags and attributes.
     *
     * @var array
     * @see getIniTagAtts().
     */

    private static $iniTagAtts = array(
        'oui_player'    => array(
            'class',
            'label',
            'labeltag',
            'provider',
            'play',
            'wraptag',
        ),
        'oui_if_player' => array(
            'play',
            'provider',
        ),
    );

    /**
     * Media URL/ID.
     *
     * @var string|array An array can be used to provide multiple sources to HTML5 players.
     * @see setMedia(), getMedia().
     */

    protected $media;

    /**
     * Media related provider.
     *
     * @var string
     * @see getMediaProvider().
     */

    protected $mediaProvider;

    /**
     * Associative array of $media value(s) and their related infos.
     *
     * @var array
     * @see setMediaInfos(), getMediaInfos().
     */

    protected $mediaInfos = array();

    /**
     * Associative array of 'media' and 'provider' values cached via the conditional tag for inheritence.
     *
     * @var array
     * @see setCache(), getCache().
     */

    protected static $cache = array();

    /**
     * Constructor
     * - Register all tags.
     */

    public function __construct() {
        parent::__construct();

        // Register initial tags.
        foreach (self::getIniTagAtts() as $tag => $atts) {
            $tagMethod = str_replace(array('oui', '_'), array('render', ''), $tag);
            $tagMethods[$tag] = $tagMethod;

            \Txp::get('\Textpattern\Tag\Registry')->register(array($this, $tagMethod), $tag);
        }

        // Register providers/extensions related tags.
        foreach (self::getProviders() as $provider => $author) {
            $lcProvider = strtolower($provider);

            foreach ($tagMethods as $tag => $method) {
                \Txp::get('\Textpattern\Tag\Registry')->register($author . '\\' . $lcProvider . '::' . $method, str_replace('player', $lcProvider, $tag));
            }
        }
    }

    /**
     * $iniTagAtts getter.
     *
     * @param  string $tag Optional tag name.
     * @return array
     */

    protected static function getIniTagAtts($tag = null)
    {
        return $tag ? static::$iniTagAtts[$tag] : static::$iniTagAtts;
    }

    /**
     * $providers setter.
     */

    protected static function setProviders()
    {
        foreach (explode('&', get_pref(self::getPlugin() . '_providers')) as $providerAuthor) {
            list($provider, $author) = explode('=', $providerAuthor);
            static::$providers[$provider] = $author;
        }
    }

    /**
     * $media setter.
     * Also call the $mediaInfos setter.
     *
     * @param string|array $value    The media URL/ID to play,
     *                               an array can be used to provide multiple sources to HTML5 players;
     * @param bool         $fallback Whether to force the $mediaInfos setter to set fallback infos or not.
     * @return object $this
     */

    public function setMedia($value, $fallback = false)
    {
        $this->media = $value;
        is_array($value) ?: $value = array($value);
        $mediaInfos = $this->getMediaInfos();

        if (!$mediaInfos || array_diff($value, array_keys($mediaInfos))) {
            $this->setMediaInfos($fallback);
        }

        return $this;
    }

    /**
     * $media getter.
     *
     * @return string|array.
     */

    public function getMedia()
    {
        return $this->media;
    }

    /**
     * $provider getter (call the setter if necessary).
     *
     * @param  bool  Whether to force the $mediaInfos setter to set fallback infos or not.
     * @return array|false false if $fallback is disable and the $media is not recognised as a valid provider related URL.
     */

    protected function getMediaProvider($fallback = false)
    {
        $this->mediaInfos or $this->setMediaInfos($fallback);
        $media = $this->getMedia();
        is_array($media) ?: $media = explode(', ', $media);

        if ($this->mediaProvider) {
            return $this->mediaProvider;
        }

        return false;
    }

    /**
     * $mediaInfos setter
     *
     * @param  bool $fallback Whether to set fallback media infos or not.
     * @return array
     */

    protected function setMediaInfos($fallback = false)
    {
        $providers = self::getProviders();
        $media = $this->getMedia();

        foreach ($providers as $provider => $author) {
            $this->mediaInfos = \Txp::get($author . '\\' . $provider)
                ->setMedia($media)
                ->getMediaInfos();

            if ($this->mediaInfos) {
                $this->mediaProvider = $provider;
                return $this->mediaInfos;
            }
        }

        if ($fallback) { // No matched provider, set default media infos.
            $this->mediaInfos = array(
                $media => array(
                    'id'  => $media,
                    'uri' => $media,
                )
            );

            $this->mediaProvider = get_pref(self::getPlugin() . '_provider');
        }

        return $this->mediaInfos;
    }

    /**
     * $mediaInfos getter.
     *
     * @return array
     */

    public function getMediaInfos()
    {
        return $this->mediaInfos;
    }

    /**
     * $cache setter.
     *
     * @return array
     */

    protected static function setCache($media = null, $provider = null)
    {
        return self::$cache = $media ? compact('media', 'provider') : array();
    }

    /**
     * $cache getter.
     *
     * @return array
     */

    protected static function getCache()
    {
        return self::$cache;
    }

    /**
     * Get all tag attributes — including providers related ones.
     *
     * @param string $tag A plugin tag.
     * @return array Tag attributes
     */

    protected static function getTagAtts($tag)
    {
        $allAtts = self::getIniTagAtts($tag);

        if ($tag === self::getPlugin()) {
            // Collects provider attributes.
            foreach (self::getProviders() as $provider => $author) {
                $class = $author . '\\' . $provider;
                $allAtts = array_merge($allAtts, $class::getTagAtts($tag));
            }
        }

        return array_fill_keys($allAtts, '');
    }

    /**
     * Generate a player.
     * oui_player tag related callback method.
     *
     * @param  array  $atts Tag attributes
     * @return string HTML
     */

    public function renderPlayer($atts)
    {
        $lAtts = lAtts(self::getTagAtts('oui_player'), $atts);

        $notParams = array_merge(
            self::getIniTagAtts(),
            array('width', 'height', 'ratio', 'responsive')
        );

        $params = array();

        // Parse attribute values.
        foreach ($lAtts as $lAtt => $value) {
            switch ($value) {
                case '':
                    $lAtts[$lAtt] = null;
                    break;
                case 'true':
                    $lAtts[$lAtt] = true;
                    break;
                case 'false':
                    $lAtts[$lAtt] = false;
                    break;
            }

            // Store player parameters related values.
            if (!in_array($lAtt, $notParams) && $lAtts[$lAtt] !== null) {
                $params[$lAtt] = $lAtts[$lAtt];
            }
        }

        extract($lAtts);

        $playProvider = $this->parsePlayProvider($play, $provider, true, true);

        if (!$playProvider) {
            return;
        }

        extract($playProvider);

        $player = \Txp::get(self::getProviders()[$provider] . '\\' . $provider)
            ->setMedia($play, true)
            ->setDims(
                $width,
                isset($height) ? $height : null,
                isset($ratio) ? $ratio : null,
                $responsive
            )->setParams($params);

        $label ? $player->setLabel($label, $labeltag) : '';
        $wraptag ? $player->setWrap($wraptag, $class) : '';

        return $player->render();
    }

    /**
     * Generates tag contents or alternative contents.
     * oui_if_player tag related callback method.
     *
     * Generated contents depends on whether the 'play' attribute value
     * matches a provider URL scheme.
     *
     * @param  array  $atts  Tag attributes
     * @param  string $thing Tag contents
     * @return mixed  Tag contents or alternative contents
     */

    public function renderIfPlayer($atts, $thing)
    {
        $lAtts = lAtts(self::getTagAtts('oui_if_player'), $atts);

        extract($lAtts);

        $playProvider = $this->parsePlayProvider($play, $provider);

        if ($playProvider) {
            extract($playProvider);

            $isValid = \Txp::get(self::getProviders()[$provider] . '\\' . $provider)->setMedia($play)->getMediaInfos();

            if ($isValid) { // Set the cache.
                self::setCache(implode(', ', array_keys($isValid)), $provider);
                $out = parse($thing, true); // Set the output.
                self::setCache(); // Reset the cache.
            }
        }

        return isset($out) ? $out : parse($thing, false);
    }

    /**
     * Parse play and provider attribute to get their right values.
     *
     * @param string $play     The play attribute value;
     * @param string $provider The provider attribute value;
     * @param string $cache    Whether to get potentially cached values or not;
     * @param string $fallback Whether to get the default provider if the play value is not recognised.
     */

    protected function parsePlayProvider(
        $play = null,
        $provider = null,
        $cache = false,
        $fallback = false
    ) {
        global $thisarticle;

        if (!$play) { // Set the potentially missing media URL/ID.
            $cache = $cache ? self::getCache() : '';
            $field = strtolower(self::getMediaField());

            if ($cache) { // Play the cache related media.
                $play = $cache['media'];
                $provider = $cache['provider'];
            } elseif (isset($thisarticle[$field])) { // Play the field related media.
                $play = $thisarticle[$field];
            } else {
                trigger_error('play attribute or related field required');
            }
        }

        if ($play) {
            // Explode the play attribute value if it is a comma separated list of values.
            explode(', ', $play, -1) ? $play = explode(', ', $play) : '';

            // Find the potentially missing provider name.
            $provider = $provider ? ucfirst($provider) : $this->setMedia($play)->getMediaProvider($fallback);
            $play = compact('play', 'provider');
        }

        return $play;
    }
}

txpinterface === 'public' ? \Txp::get('Oui\Player\Player') : '';
