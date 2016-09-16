<?php

class Oui_Video
{
    protected $plugin = 'oui_video';
    protected $providers = array(
        'Vimeo',
        'Youtube',
        'Dailymotion',
    );
    protected $pophelp = 'http://help.ouisource.com/';
    protected $tags = array(
        'oui_video' => array(
            'class' => array(
                'default' => '',
            ),
            'height' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'label' => array(
                'default' => '',
            ),
            'labeltag' => array(
                'default' => '',
            ),
            'provider' => array(
                'default' => '',
                'valid'   => array('vimeo', 'youtube', 'dailymotion'),
            ),
            'ratio' => array(
                'default' => '',
            ),
            'video' => array(
                'default' => '',
            ),
            'width' => array(
                'default' => '',
                'valid'   => '/^\d+$/',
            ),
            'wraptag' => array(
                'default' => '',
            ),
        ),
        'oui_if_video' => array(
            'video' => array(
                'default' => '',
            ),
            'provider' => array(
                'default' => '',
                'valid'   => array('vimeo', 'youtube', 'dailymotion'),
            ),
        ),
    );
    protected $privs = '1, 2';
    protected $prefs = array(
        'custom_field' => array(
            'widget'  => 'oui_video_custom_fields',
            'default' => 'article_image',
        ),
        'provider' => array(
            'default' => 'youtube',
            'valid'   => array('dailymotion', 'vimeo', 'Youtube'),
        ),
        'width' => array(
            'default' => '',
        ),
        'height' => array(
            'default' => '',
        ),
        'ratio' => array(
            'default' => '',
        ),
    );

    /**
     * Register callbacks.
     */
    public function __construct()
    {
        if (txpinterface === 'admin') {
            add_privs('plugin_prefs.' . $this->plugin, $this->privs);
            add_privs('prefs.' . $this->plugin, $this->privs);
            register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . $this->plugin);
            register_callback(array($this, 'setPrefs'), 'prefs', null, 1);
            register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . $this->plugin, null, 1);

            // Add privs to provider prefs only if they are enabled.
            foreach ($this->providers as $provider) {
                $group = $this->plugin . '_' . strtolower($provider);
                $pref = $group . '_prefs';
                if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                    add_privs('prefs.' . $group, $this->privs);
                }
            }

            foreach ($this->getPrefs() as $pref => $options) {
                register_callback(array($this, 'pophelp'), 'admin_help', $pref);
            }
        } else {
            if (class_exists('\Textpattern\Tag\Registry')) {
                // Register Textpattern tags for TXP 4.6+.
                foreach ($this->tags as $tag => $attributes) {
                    Txp::get('\Textpattern\Tag\Registry')->register($tag);
                }
            }
        }
    }

    /**
     * Handler for plugin lifecycle events.
     *
     * @param string $evt Textpattern action event
     * @param string $stp Textpattern action step
     */
    public function lifeCycle($evt, $stp)
    {
        switch ($stp) {
            case 'enabled':
                $this->setPrefs();
                break;
            case 'deleted':
                safe_delete('txp_prefs', "event LIKE '" . $this->plugin . "%'");
                safe_delete('txp_lang', "name LIKE '" . $this->plugin . "%'");
                break;
        }
    }

    /**
     * Jump to the prefs panel.
     */
    public function optionsLink()
    {
        $url = defined('PREF_PLUGIN')
               ? '?event=prefs#prefs_group_' . $this->plugin
               : '?event=prefs&step=advanced_prefs';
        header('Location: ' . $url);
    }

    /**
     * Define the pref widget
     *
     * @param array $options Current pref options
     */
    public function prefWidget($options)
    {
        $valid = isset($options['valid']) ? $options['valid'] : false;

        if ($valid && is_array($valid)) {
            $widget = $valid === array('0', '1') ? 'yesnoradio' : $this->plugin . '_pref';
        } else {
            $widget = 'text_input';
        }

        return $widget;
    }

    /**
     * Build select inputs for plugin prefs
     *
     * @param string $name the name of the preference (Textpattern variable)
     * @param string $val The value of the preference (Textpattern variable)
     */
    public function prefSelect($name, $val)
    {
        $prefs = $this->getPrefs();

        foreach ($prefs as $pref => $options) {
            if ($pref === $name) {
                $valid = $options['valid'];
                $vals = array();

                foreach ($valid as $value) {
                    $value === '' ?: $vals[$value] = gtxt($pref . '_' . $value);
                }

                return selectInput($name, $vals, $val, $valid[0] === '' ? true : false);
            }
        }
    }

    /**
     * Install plugin prefs
     */
    public function getPrefs()
    {
        $prefs = array();

        foreach ($this->prefs as $pref => $options) {
            $options['group'] = $this->plugin;
            $pref = $options['group'] . '_' . $pref;
            $prefs[$pref] = $options;
        }

        foreach ($this->providers as $provider) {
            $options = array(
                'default' => '0',
                'valid'   => array('0', '1'),
            );
            $options['group'] = $this->plugin;
            $pref = $options['group'] . '_' . strtolower($provider) . '_prefs';
            $prefs[$pref] = $options;
        }

        foreach ($this->providers as $provider) {
            $class = __CLASS__ . '_' . $provider;
            $prefs = (new $class)->getPrefs($prefs);
        }

        return $prefs;
    }

    /**
     * Install plugin prefs
     */
    public function setPrefs()
    {
        $prefs = $this->getPrefs();
        $position = 250;

        foreach ($prefs as $pref => $options) {
            if (get_pref($pref, null) === null) {
                set_pref(
                    $pref,
                    $options['default'],
                    $options['group'],
                    PREF_PLUGIN,
                    isset($options['widget']) ? $options['widget'] : $this->prefWidget($options),
                    $position
                );
            }
            $position = $position + 10;
        }
    }

    /**
     * Get external popHelp contents
     *
     * @param string $evt Textpattern action event
     * @param string $stp Textpattern action step
     * @param string $ui Textpattern user interface element
     */
    public function pophelp($evt, $stp, $ui, $vars)
    {
        return str_replace(HELP_URL, $this->pophelp, $ui);
    }

    /**
     * Get a tag attribute list
     *
     * @param string $tag The plugin tag
     */
    public function getAtts($tag)
    {
        $get_atts = array();

        foreach ($this->tags[$tag] as $att => $options) {
            $get_atts[$att] = $options;
        }

        foreach ($this->providers as $provider) {
            $class = __CLASS__ . '_' . $provider;
            $get_atts = (new $class)->getAtts($tag, $get_atts);
        }

        return $get_atts;
    }

    /**
     * Look for wrong attribute values
     *
     * @param string $tag The plugin tag
     * @param array $atts The Txp variable containing attribute values in use
     */
    public function checkAtts($tag, $atts)
    {
        $get_atts = $this->getAtts($tag);

        foreach ($atts as $att => $val) {
            $valid = isset($get_atts[$tag][$att]['valid']) ? $get_atts[$tag][$att]['valid'] : false;

            if ($valid) {
                if (is_array($valid) && !in_array($val, $valid)) {
                    $valid = implode(', ', $valid);
                    trigger_error(
                        'Unknown attribute value for ' . $att .
                        '. Exact valid values are: ' . $valid . '.'
                    );
                } elseif (!is_array($valid) && !preg_match($valid, $val)) {
                    trigger_error(
                        'Unknown attribute value for ' . $att .
                        '. A valid value must respect the following pattern ' . $valid . '.'
                    );
                }
            }
        }

        return;
    }

    /**
     * Get the video provider and the video id from its url
     *
     * @param string $video The video url
     */
    public function getVidInfos($video)
    {
        foreach ($this->providers as $provider) {
            $class = __CLASS__ . '_' . $provider;
            $match = (new $class)->getVidInfos($video);
            if ($match) {
                return $match;
            }
        }

        return false;
    }
}

new Oui_Video();
