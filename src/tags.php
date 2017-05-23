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

/*
 * Display a video
 */
namespace {

    function oui_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        $class = 'Oui\Player\Main';
        $obj = $class::getInstance();

        // Set tag attributes
        $get_atts = $obj->getAtts(__FUNCTION__);
        $latts = lAtts($get_atts, $atts);
        extract($latts);

        if (!$play) {
            if ($oui_player_item) {
                $provider = $oui_player_item['provider'];
                $play = $oui_player_item['url'];
            } else {
                $play = strtolower(get_pref('oui_player_custom_field'));
            }
        }

        if ($provider) {
            $class = 'Oui\Player\\' . $provider;
            if (class_exists($class)) {
                $obj = $class::getInstance();
            } else {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        }

        $obj->play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;
        $obj->config = $latts;
        $out = $obj->getPlayer($labeltag, $label, $wraptag, $class);

        return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
    }

    /*
     * Check a video url and its provider if provided.
     */
    function oui_if_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        $class = 'Oui\Player\Main';
        $obj = $class::getInstance();

        // Set tag attributes
        $get_atts = $obj->getAtts(__FUNCTION__);
        $latts = lAtts($get_atts, $atts);
        extract($latts);

        // Check if the play attribute value is recognised.
        if ($provider) {
            $class = 'Oui\Player\\' . $provider;
            if (class_exists($class)) {
                $obj = $class::getInstance();
            } else {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        }

        $play ?: $play = strtolower(get_pref('oui_player_custom_field'));
        $obj->play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;
        $oui_player_item = $obj->getInfos();

        $out = parse($thing, $oui_player_item);
        unset($GLOBALS['oui_player_item']);

        return $out;
    }
}
