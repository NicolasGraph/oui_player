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

/**
 * Plugin pref functions
 */

function oui_player_pref_widget($name, $val)
{
    $class = 'Oui\Player\Admin';
    $obj = $class::getInstance();
    $widget = $obj->prefFunction($name, $val);
    return $widget;
}

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

function oui_player_truefalseradio($field, $checked = '', $tabindex = 0, $id = '')
{
    $vals = array(
        'false' => gTxt('no'),
        'true' => gTxt('yes'),
    );

    return radioSet($vals, $field, $checked, $tabindex, $id);
}
