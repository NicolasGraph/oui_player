<?php

/**
 * Plugin pref functions
 */
function oui_player_pref($name, $val)
{
    $widget = (new Oui_Player)->prefSelect($name, $val);
    return $widget;
}

function oui_player_custom_fields($name, $val)
{
    $custom_fields = safe_rows("name, val", 'txp_prefs', "name LIKE 'custom_%_set' AND val<>'' ORDER BY name");

    if ($custom_fields) {
        $vals = array();
        foreach ($custom_fields as $row) {
            $vals[$row['val']] = $row['val'];
        }
        $vals['article_image'] = gtxt('article_image');
        $vals['excerpt'] = gtxt('excerpt');
        return selectInput($name, $vals, $val);
    }
    return gtxt('no_custom_fields_recorded');
}

function oui_player_pref_color($name, $val)
{
    return fInput('color', $name, $val);
}

function oui_player_truefalseradio($field, $checked = '', $tabindex = 0, $id = '') {
    $vals = array(
        'false' => gTxt('no'),
        'true' => gTxt('yes'),
    );

    return radioSet($vals, $field, $checked, $tabindex, $id);
}
