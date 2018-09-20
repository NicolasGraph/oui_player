<?php

/*
 * This file is part of oui_player_provider,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player_provider
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
 * EmbedInterface
 *
 * @package Oui\Player
 */

interface EmbedInterface
{
    /**
     * $label property getter.
     *
     * @return object $this
     */

    public function setLabel($txt, $tag = '');

    /**
     * $wrap property getter.
     *
     * @return object $this
     */

    public function setWrap($tag, $class = '');

    /**
     * $media property setter.
     *
     * @return object $this.
     */

    public function setMedia($value, $fallback = false);

    /**
     * $media property getter.
     *
     * @return string|array
     * @throws \Exception
     */

    public function getMedia();

    /**
     * $params property setter.
     *
     * @return object $this
     */

    public function setParams($nameVals = null);

    /**
     * $params property getter.
     *
     * @return array
     */

    public function getParams();

    /**
     * $provider property getter.
     *
     * @return string
     */

    public static function getProvider();

    /**
     * $script property getter.
     *
     * @param  bool  $wrap Whether to wrap to embed the script URL in a script tag or not;
     * @return string|null URL or HTML script tag; null if the property is not set.
     */

    public static function getScript($wrap = false);

    /**
     * Embed the provider script.
     */

    public function embedScript();

    /**
     * Collect provider prefs.
     *
     * @param  array $prefs Prefs collected provider after provider.
     * @return array Collected prefs merged with ones already provided.
     */

    public static function getIniPrefs();

    /**
     * Get a tag attributes.
     *
     * @param  string $tag      The plugin tag.
     * @param  array  $get_atts Stores attributes provider after provider.
     * @return array
     */

    public static function getTagAtts($tag);

    /**
     * $mediaInfos getter.
     *
     * @return array
     */

    public function getMediaInfos($fallback = false);

    /**
     * Get the player size.
     *
     * @return array 'width' and 'height' and 'responsive' associated values — Height could be not set (i.e. HTML5 audio player).
     * @throws \Exception
     * @TODO override the HTML audio player related method to remove $height + $ratio.
     */

    public function setDims($width = null, $height = null, $ratio = null, $responsive = null);

    /**
     * $dims getter.
     *
     * @return array
     */

    public function getDims();

    /**
     * Generate the player code.
     *
     * @return string HTML
     */

    public function getHTML();

    /**
     * Render the player.
     */

    public function render();

    /**
     * Main provider related tag callback method.
     */

    public static function renderPlayer($atts);

    /**
     * Conditional provider related tag callback method.
     */

    public static function renderIfPlayer($atts, $thing);
}
