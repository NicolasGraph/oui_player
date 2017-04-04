<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016 Nicolas Morand
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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

            return '<audio src="' . $src . '"' . (empty($params) ? '' : ' ' . implode(' ', $params)) . '></audio>';
        }
    }
}

if (txpinterface === 'admin') {
    Audio::getInstance();
}
