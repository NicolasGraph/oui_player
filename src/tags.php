<?php

/*
 * This file is part of oui_player,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player
 *
 * Copyright (C) 2016-2018 Nicolas Morand
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

        $namespace = 'Oui\Player'; // Plugin namespace.
        $main_class = $namespace . '\Main'; // Main plugin class.
        $lAtts = lAtts($main_class::getAtts(__FUNCTION__), $atts); // Gets used attributes.

        extract($lAtts); // Extracts used attributes.

        if (!$play) {
            if (isset($oui_player_item['play'])) {
                $play = $oui_player_item['play'];
            } else {
                $play = $thisarticle[get_pref('oui_player_custom_field')];
            }
        }

        if (!$provider && isset($oui_player_item['provider'])) {
            $provider = $oui_player_item['provider'];
        }

        $class_in_use = $provider ? $namespace . '\\' . ucfirst($provider) : $main_class;

        $player = $class_in_use::getInstance($play, $lAtts)->getPlayer();

        return doLabel($label, $labeltag).(($wraptag) ? doTag($player, $wraptag, $class) : $player);
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

        $namespace = 'Oui\Player'; // Plugin namespace.
        $main_class = $namespace . '\Main'; // Main plugin class.

        extract(lAtts($main_class::getAtts(__FUNCTION__), $atts)); // Extracts used attributes.

        if (!$play) {
            $field = get_pref('oui_player_custom_field');
            $play = isset($thisarticle[$field]) ? $thisarticle[$field] : false;
        }

        if ($play) {
            $class_in_use = $provider ? $namespace . '\\' . ucfirst($provider) : $main_class;

            if ($is_valid = $class_in_use::getInstance($play)->isValid()) {
                $oui_player_item = array('play' => $play);
                $provider ? $oui_player_item['provider'] = $provider : '';
            }

            $out = parse($thing, $is_valid);

            unset($GLOBALS['oui_player_item']);

            return $out;
        }

        return parse($thing, false);
    }
}
