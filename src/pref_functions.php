<?php

/**
 * Plugin pref functions
 */
function oui_video_pref($name, $val)
{
    $widget = (new Oui_Video)->prefSelect($name, $val);
    return $widget;
}

function oui_video_custom_fields($name, $val)
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

function oui_video_pref_color($name, $val)
{
    return fInput('color', $name, $val);
}
