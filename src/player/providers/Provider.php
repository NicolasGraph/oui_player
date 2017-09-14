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
 * Provider
 *
 * @package Oui\Player
 */

namespace Oui\Player {

    abstract class Provider
    {
        /**
         * The value provided through the 'play'
         * attribute value of the plugin tag.
         *
         * @var string
         */

        public $play;

        /**
         * Infos.
         *
         * @var array
         * @see setInfos()
         */

        public $infos;

        /**
         * Associative array attributes in use and their values.
         *
         * @var array
         */

        public $config;

        /**
         * Associative array of 'play' value(s) and their.
         *
         * @var array
         * @example
         * protected static $patterns = array(
         *     'video' => array(
         *         'scheme' => '#^(http|https)://(www\.)?(youtube\.com/(watch\?v=|embed/|v/)|youtu\.be/)(([^&?/]+)?)#i',
         *         'id'     => '5',
         *         'glue'   => '&amp;',
         *     ),
         *     'list'  => array(
         *         'scheme' => '#^(http|https)://(www\.)?(youtube\.com/(watch\?v=|embed/|v/)|youtu\.be/)[\S]+list=([^&?/]+)?#i',
         *         'id'     => '5',
         *         'prefix' => 'list=',
         *     ),
         * );
         *
         * Where 'video' and 'list' are used to define the 'type' key of the $infos property
         * when an URL match the regular expression defined as the 'scheme'.
         * 'id' stores the index of the string to get from the matches.
         * If set, the 'glue' key allows to test multiple schemes and stick ID's with its value.
         * 'prefix' can defines an ID prefix.
         */

        protected static $patterns = array();

        /**
         * The player base path.
         *
         * @var string
         */

        protected static $src;

        /**
         * URL of a script to embed.
         *
         * @var string
         */

        protected static $script;

        /**
         * Default player size.
         *
         * @var array
         */

        protected static $dims = array(
            'width'    => array(
                'default' => '640',
            ),
            'height'   => array(
                'default' => '',
            ),
            'ratio'    => array(
                'default' => '16:9',
            ),
        );

        /**
         * Player parameters and related options/values.
         *
         * @var array
         * @example
         * protected static $params = array(
         *     'size'  => array(
         *         'default' => 'large',
         *         'force'   => true,
         *         'valid'   => array('large', 'small'),
         *     ),
         * );
         *
         * Where 'size' is a player parameter and 'large' is its default value.
         * 'force' allows to set the parameter even if its value is the default one.
         * The 'valid' key accept an array of values or a type of values as an HTML input type.
         */

        protected static $params = array();

        /**
         * Strings sticking different player URL parts.
         *
         * @var array
         */

        protected static $glue = array('/', '?', '&amp;');

        /**
         * Caches the class instance.
         *
         * @var object
         */

        private static $instance = null;

        /**
         * Singleton.
         */

        public static function getInstance()
        {
            $class = get_called_class();

            if (!isset(self::$instance[$class])) {
                self::$instance[$class] = new static();
            }

            return self::$instance[$class];
        }

        /**
         * Constructor.
         *
         * @see \register_callback()
         */

        public function __construct()
        {
            // Plugs in the Player class.
            $plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            \register_callback(array($this, 'getProvider'), $plugin, 'plug_providers', 0);

            if (isset(static::$script)) {
                \register_callback(array($this, 'embedScript'), 'textpattern_end');
            }
        }

        /**
         * Gets the class name as the provider name.
         */

        public function getProvider()
        {
            return array(substr(strrchr(get_class($this), '\\'), 1));
        }


        /**
         * Embeds the provider script.
         */

        public function embedScript()
        {
            if ($ob = ob_get_contents()) {
                ob_clean();
                echo str_replace(
                    '</body>',
                    '<script src="' . static::$script . '"></script>' . n . '</body>',
                    $ob
                );
            }
        }

        /**
         * Collects provider prefs.
         *
         * @param  array $prefs Prefs collected provider after provider.
         * @return array Collected prefs merged with ones already provided.
         */

        public function getPrefs($prefs)
        {
            $merge_prefs = array_merge(static::$dims, static::$params);

            foreach ($merge_prefs as $pref => $options) {
                $options['group'] = strtolower(str_replace('\\', '_', get_class($this)));
                $pref = $options['group'] . '_' . $pref;
                $prefs[$pref] = $options;
            }

            return $prefs;
        }

        /**
         * Get tag attributes.
         *
         * @param  string $tag      The plugin tag.
         * @param  array  $get_atts Stores attributes provider after provider.
         * @return array  Attributes
         */

        public function getAtts($tag, $get_atts)
        {
            $atts = array_merge(static::$dims, static::$params);

            foreach ($atts as $att => $options) {
                $att = str_replace('-', '_', $att);
                $get_atts[$att] = '';
            }

            return $get_atts;
        }

        /**
         * Gets the play property.
         *
         * @throws \Exception
         */

        public function getPlay()
        {
            if ($this->play) {
                return explode(', ', $this->play);
            }

            throw new \Exception(gtxt('undefined_property'));
        }

        /**
         * Sets the current media(s) infos.
         *
         * @return array The current media(s) infos.
         * @see    get_Play()
         */

        public function setInfos()
        {
            $infos = array();

            foreach ($this->getPlay() as $play) {
                $glue = null;

                foreach (static::$patterns as $pattern => $options) {
                    if (preg_match($options['scheme'], $play, $matches)) {
                        $prefix = isset($options['prefix']) ? $options['prefix'] : '';

                        if (!array_key_exists($play, $infos)) {
                            $infos[$play] = array(
                                'play' => $prefix . $matches[$options['id']],
                                'type' => $pattern,
                            );

                            // Bandcamp and Youtube accept multiple matches.
                            if (!isset($options['glue'])) {
                                break;
                            } else {
                                $glue = $options['glue'];
                            }
                        } else {
                            $infos[$play]['play'] .= $glue . $prefix . $matches[$options['id']];
                            $infos[$play]['type'] = $pattern;
                        }
                    }
                }
            }

            return $this->infos = $infos;
        }

        /**
         * Gets player parameters in in use
         * from the plugin tag attributes
         * or from the plugin prefs.
         *
         * @return array Parameters and their values.
         * @see    \get_pref()
         */

        public function getParams()
        {
            $params = array();

            foreach (static::$params as $param => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $att = str_replace('-', '_', $param);
                $value = isset($this->config[$att]) ? $this->config[$att] : '';

                // Adds attributes values in use or modified prefs values as player parameters.
                if ($value === '' && ($pref !== $default || isset($infos['force']))) {
                    // Removes # from the color pref as a color type is used for the pref input.
                    $params[] = $param . '=' . str_replace('#', '', $pref);
                } elseif ($value !== '') {
                    // Removes the # in the color attribute just in case…
                    $params[] = $param . '=' . str_replace('#', '', $value);
                }
            }

            return $params;
        }

        /**
         * Gets the player size
         * from the plugin tag attributes
         * or from the plugin prefs.
         *
         * @return array Player size.
         * @see    \get_pref()
         */

        public function getSize()
        {
            foreach (static::$dims as $dim => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $dim);
                $default = $infos['default'];
                $att = isset($this->config[$dim]) ? $this->config[$dim] : '';

                // Adds attributes values in use or modified prefs values as player parameters.
                if ($att) {
                    $$dim = $att;
                } elseif ($pref && $pref !== $default) {
                    $$dim = $pref;
                } elseif ($default) {
                    $$dim = $default;
                }
            }

            if (!isset($width) || !isset($height)) {
                // Works out the aspect ratio.
                if (isset($ratio)) {
                    preg_match("/(\d+):(\d+)/", $ratio, $matches);

                    if ($matches && $matches[1]!=0 && $matches[2]!=0) {
                        $aspect = $matches[1] / $matches[2];
                    } else {
                        trigger_error(gtxt('invalid_player_ratio'));
                    }

                    // Calculates the new width/height.
                    $defined = $width ? 'width' : 'height';
                    $undefined = $defined === 'width' ? 'height' : 'width';
                    $$undefined = $$defined / $aspect;
                    // Has unit?
                    preg_match("/(\D+)/", $$defined, $unit);
                    // Adds unit if it exists.
                    isset($unit[0]) ? $$undefined .= $unit[0] : '';
                } else {
                    trigger_error(gtxt('undefined_player_size'));
                }
            }

            return array('width' => $width, 'height' => $height);
        }

        /**
         * Gets the infos property; set it if necessary.
         *
         * @return array An associative array of
         * @see    getPlay()
         *         setInfos()
         */

        public function getInfos()
        {
            $isUrl = preg_grep('/([.][a-z]+\/)/', $this->getPlay());

            // Returns infos from parsed URL's…
            if ($this->infos || ($isUrl && $this->setInfos() !== false)) {
                return $this->infos;
            }

            // or build default ones.
            $infos = array();

            foreach ($this->getPlay() as $play) {
                $infos[$play] = array(
                    'play' => $play,
                    'type' => 'id',
                );
            }

            return $infos;
        }

        /**
         * Generates the player.
         *
         * @return string HTML
         * @see    getinfos()
         *         getPlay()
         *         getParams()
         *         getSize()
         */

        public function getPlayer()
        {
            $play = $this->getInfos()[$this->getPlay()[0]]['play'];

            $src = static::$src . static::$glue[0] . $play;
            $params = $this->getParams();

            if (!empty($params)) {
                $joint = strpos($src, static::$glue[1]) ? static::$glue[2] : static::$glue[1];
                $src .= $joint . implode(static::$glue[2], $params);
            }

            $dims = $this->getSize();

            extract($dims);

            return sprintf(
                '<iframe width="%s" height="%s" src="%s" %s></iframe>',
                $width,
                $height,
                $src,
                'frameborder="0" allowfullscreen'
            );
        }
    }
}
