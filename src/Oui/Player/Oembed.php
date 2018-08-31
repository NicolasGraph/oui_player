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
 * Provider
 *
 * @package Oui\Player
 */

abstract class Oembed extends Embed
{
    /**
     * JSON related API endpoint.
     *
     * @var string
     */

    protected static $endPoint;

    /**
     * Provider URL
     *
     * @var string
     */

    protected static $URLBase;

    /**
     * OEmbed data
     *
     * @var string
     */

    protected $data;

    /**
     * $endPoint getter.
     */

    final protected static function getEndPoint()
    {
        return static::$endPoint;
    }

    /**
     * $URLBase getter.
     */

    final protected static function getURLBase()
    {
        return static::$URLBase;
    }

    /**
     * Build media URL.
     */

    final protected function getMediaURL()
    {
        return self::getURLBase() . $this->getMediaInfos()[$this->getMedia()]['uri'];
    }

    /**
     * $data setter
     */

    protected function setData()
    {
        $url = self::getEndPoint() . $this->getMediaURL() . '&for=' . get_pref('siteurl');

        if (extension_loaded('curl')) {
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

            $json = curl_exec($curl);

            curl_close($curl);
        } else {
            $json = file_get_contents($url);
        }

        if ($json && $this->data = @json_decode($json)) {
            return;
        }

        throw new \Exception('Invalid JSON file.');
    }

    /**
     * $data unsetter
     */

    final protected function unsetData()
    {
        $this->data = null;
    }

    /**
     * $data getter
     */

    final protected function getData($name)
    {
        $this->data or $this->setData();

        return txpspecialchars($this->data->$name);
    }
}
