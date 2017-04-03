<?php

/*
 * oui_player - An extendable plugin to easily embed iframe
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * This file is part of oui_player.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see https://www.gnu.org/licenses/.
 */

class Audio extends Video
{
    protected $patterns = array(
        'filename' => array(
            'scheme' => '#^((?!(http|https):\/\/(www.)?)\S+\.(mp3|m4a|ogg|oga|webma|wav))$#i',
            'id'     => '1',
        ),
        'url' => array(
            'scheme' => '#^(((http|https):\/\/(www.)?)\S+\.(mp3|m4a|ogg|oga|webma|wav))$#i',
            'id'     => '1',
        ),
    );
    protected $dims = array();
    protected $params = array(
        'autoplay' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'controls' => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'loop'     => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'muted'    => array(
            'default' => '0',
            'valid'   => array('0', '1'),
        ),
        'preload'  => array(
            'default' => 'auto',
            'valid'   => array('none', 'metadata', 'auto'),
        ),
        'volume'   => array(
            'default' => '',
            'valid'   => 'number',
        ),
    );

    /**
     * Get the player code
     */
    public function getPlayer()
    {
        $item = preg_match('/([.][a-z]+\/)/', $this->play) ? $this->getInfos() : $this->play;
        $id = isset($item['id']) ? $item['id'] : $this->play;
        $type = isset($item['type']) ? $item['type'] : 'id';

        if ($item) {
            if ($type === 'url') {
                $src = $id;
            } else {
                if ($type === 'id') {
                    $file = \fileDownloadFetchInfo('id = '.intval($id).' and created <= '.now('created'));
                } elseif ($type === 'filename') {
                    $file = \fileDownloadFetchInfo("filename = '".\doSlash($id)."' and created <= ".now('created'));
                }
                $src = \filedownloadurl($file['id'], $file['filename']);
            }

            $params = $this->getParams();

            return '<audio src="' . $src . '"' . (empty($params) ? '' : ' ' . implode(' ', $params)) . '>' . \gtxt('oui_player_audio_unavailable') . '</audio>' . $this->append;
        }
    }
}

if (txpinterface === 'admin') {
    Audio::getInstance();
}
