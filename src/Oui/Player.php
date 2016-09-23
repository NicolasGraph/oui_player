<?php

class Oui_Player
{
    protected $plugin = 'oui_player';
    protected $providers;
    protected $pophelp = 'http://help.ouisource.com/';
    protected $tags = array(
        'oui_player' => array(
            'class' => array(
                'default' => '',
            ),
            'label' => array(
                'default' => '',
            ),
            'labeltag' => array(
                'default' => '',
            ),
            'provider' => array(
                'default' => '',
            ),
            'play' => array(
                'default' => '',
            ),
            'wraptag' => array(
                'default' => '',
            ),
        ),
        'oui_if_player' => array(
            'play' => array(
                'default' => '',
            ),
            'provider' => array(
                'default' => '',
            ),
        ),
    );
    protected $privs = '1, 2';
    protected $prefs = array(
        'custom_field' => array(
            'widget'  => 'oui_player_custom_fields',
            'default' => 'article_image',
        ),
        'provider' => array(
        ),
    );

    /**
     * Register callbacks.
     */
    public function __construct()
    {
        $this->providers = callback_event($this->plugin, 'plug_providers', 0, 'Provider');
        $this->tags['oui_player']['provider']['valid'] = $this->providers;
        $this->tags['oui_if_player']['provider']['valid'] = $this->providers;
        $this->prefs['provider']['default'] = $this->providers[0];
        $this->prefs['provider']['valid'] = $this->providers;

        if (txpinterface === 'admin') {
            add_privs('plugin_prefs.' . $this->plugin, $this->privs);
            add_privs('prefs.' . $this->plugin, $this->privs);

            register_callback(array($this, 'lifeCycle'), 'plugin_lifecycle.' . $this->plugin);
            register_callback(array($this, 'optionsLink'), 'plugin_prefs.' . $this->plugin, null, 1);

            // Add privs to provider prefs only if they are enabled.
            foreach ($this->providers as $provider) {
                $group = $this->plugin . '_' . strtolower($provider);
                $pref = $group . '_prefs';
                if (!empty($_POST[$pref]) || (!isset($_POST[$pref]) && get_pref($pref))) {
                    add_privs('prefs.' . $group, $this->privs);
                }
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

    public function appendComma()
    {
        return ', ';
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
                safe_delete('txp_lang', "owner = '" . $this->plugin . "'");
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
            if ($valid === array('0', '1')) {
                $widget = 'yesnoradio';
            } elseif ($valid === array('true', 'false')) {
                $widget = $this->plugin . '_truefalseradio';
            } else {
                $widget = $this->plugin . '_pref';
            }
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

        if ($tag === $this->plugin) {
            foreach ($this->providers as $provider) {
                $class = __CLASS__ . '_' . $provider;
                $get_atts = (new $class)->getAtts($tag, $get_atts);
            }
        }

        return $get_atts;
    }

    /**
     * Get the video provider and the video id from its url
     *
     * @param string $play The item url
     */
    public function getItemInfos($play)
    {
        foreach ($this->providers as $provider) {
            $class = __CLASS__ . '_' . $provider;
            $match = (new $class)->getItemInfos($play);
            if ($match) {
                return $match;
            }
        }

        return false;
    }
}

new Oui_Player();
