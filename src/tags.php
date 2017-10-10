<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2017 Nicolas Morand
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
 * Plugin tags
 */

namespace {

    /**
     * Generates a player.
     *
     * @param  array  $atts Tag attributes
     * @return string HTML
     */

    function oui_player($atts)
    {
        global $thisarticle, $oui_player_item;

        // Set tag attributes
        $get_atts = Oui\Player\Main::getAtts(__FUNCTION__);
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

        $play = isset($thisarticle[$play]) ? $thisarticle[$play] : $play;

        if ($provider) {
            $player = 'Oui\Player\\' . $provider;

            if (!class_exists($player)) {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        } else {
            $player = 'Oui\Player\Main';
        }

        $out = $player::getInstance($play, $latts)->getPlayer();

        return doLabel($label, $labeltag).(($wraptag) ? doTag($out, $wraptag, $class) : $out);
    }

    /**
     * Generates tag contents or alternative contents.
     *
     * Generated contents depends on whether the 'play' attribute value
     * matches a provider URL scheme.
     *
     * @param  array  $atts  Tag attributes
     * @param  string $thing Tag contents
     * @return mixed  Tag contents or alternative contents
     */

    function oui_if_player($atts, $thing)
    {
        global $thisarticle, $oui_player_item;

        // Sets tag attributes
        $get_atts = Oui\Player\Main::getAtts(__FUNCTION__);
        $latts = lAtts($get_atts, $atts);

        extract($latts);

        // Checks if the play attribute value is recognised.
        if ($provider) {
            $player = 'Oui\Player\\' . $provider;

            if (!class_exists($player)) {
                trigger_error('Unknown or unset provider: "' . $provider . '".');
                return;
            }
        } else {
            $player = 'Oui\Player\Main';
        }

        $play ?: $play = strtolower(get_pref('oui_player_custom_field'));

        $obj = $player::getInstance(isset($thisarticle[$play]) ? $thisarticle[$play] : $play);

        $oui_player_item = $obj->getInfos(false);

        $out = parse($thing, $oui_player_item);

        unset($GLOBALS['oui_player_item']);

        return $out;
    }
}
