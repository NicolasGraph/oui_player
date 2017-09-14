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

/**
 * Plugin pref functions
 */

namespace {

    /**
     * Generates the right pref widget.
     *
     * @param  string $name The name of the preference (Textpattern variable)
     * @param  string $val  The value of the preference (Textpattern variable)
     * @return string HTML
     */

    function oui_player_pref_widget($name, $val)
    {
        return Oui\Player\Admin::prefFunction($name, $val);
    }

    /**
     * Generates a select list of custom + article_image + excerpt fields.
     *
     * @param  string $name The name of the preference (Textpattern variable)
     * @param  string $val  The value of the preference (Textpattern variable)
     * @return string HTML
     */

    function oui_player_custom_fields($name, $val)
    {
        $vals = array();
        $vals['article_image'] = gtxt('article_image');
        $vals['excerpt'] = gtxt('excerpt');

        $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

        if ($custom_fields) {
            foreach ($custom_fields as $row) {
                $vals[$row['val']] = $row['val'];
            }
        }

        return selectInput($name, $vals, $val);
    }

    /**
     * Generates a Yes/No radio button toggle using 'true'/'false' as values.
     *
     * @param  string $field    The field name
     * @param  string $checked  The checked button, either 'true', 'false'
     * @param  int    $tabindex The HTML tabindex
     * @param  string $id       The HTML id
     * @see    radioSet()
     * @return string HTML
     */

    function oui_player_truefalseradio($field, $checked = '', $tabindex = 0, $id = '')
    {
        $vals = array(
            'false' => gTxt('no'),
            'true' => gTxt('yes'),
        );

        return radioSet($vals, $field, $checked, $tabindex, $id);
    }
}
