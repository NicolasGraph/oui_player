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

namespace Oui\Player {

    abstract class Provider
    {
        public $play;
        public $infos;
        public $config;

        protected static $patterns = array();
        protected static $src;
        protected static $script;
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
        protected static $params = array();
        protected static $glue = array('/', '?', '&amp;');

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
         * Register callbacks.
         */
        public function __construct()
        {
            // Plug in Oui\Player class
            $plugin = strtolower(str_replace('\\', '_', __NAMESPACE__));
            \register_callback(array($this, 'getProvider'), $plugin, 'plug_providers', 0);

            if (isset(static::$script)) {
                \register_callback(array($this, 'getScript'), 'textpattern_end');
            }
        }

        /**
         * Get the class name as the provider name.
         */
        public function getProvider()
        {
            return array(substr(strrchr(get_class($this), '\\'), 1));
        }


        /**
         * Get the class name as the provider name.
         */
        public function getScript()
        {
            if ($ob = ob_get_contents()) {
                ob_clean();
                echo str_replace('</body>', '<script src="' . static::$script . '"></script>' . n . '</body>', $ob);
            }
        }

        /**
         * Get provider prefs.
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
         * @param string $tag      The plugin tag.
         * @param array  $get_atts The array where attributes are stored provider after provider.
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
         * Check if the play property is a recognised URL scheme.
         */
        public function getPlay()
        {
            if ($this->play) {
                return explode(', ', $this->play);
            }

            throw new \Exception(gtxt('undefined_property'));
        }

        /**
         * Get the item URL, provider and ID from the play property.
         */
        public function setInfos()
        {
            $infos = array();

            foreach ($this->getPlay() as $play) {
                foreach (static::$patterns as $pattern => $options) {
                    if (preg_match($options['scheme'], $play, $matches)) {
                        $prefix = isset($options['prefix']) ? $options['prefix'] : '';

                        if (!array_key_exists($play, $infos)) {
                            $infos[$play] = array(
                                    'play' => $prefix . $matches[$options['id']],
                                    'type' => $pattern,
                                );
                            if (!isset($options['next'])) {
                                break;
                            }
                        } else {
                            // Bandcamp accepts track+album, Youtube accepts video+list.
                            $infos['play'] .= static::$glue[1] . $prefix . $matches[$options['id']];
                            $infos['type'] = array($infos['type'], $pattern);
                        }
                    }
                }
            }

            return $this->infos = $infos;
        }

        /**
         * Get player parameters in in use.
         */
        public function getParams()
        {
            $params = array();

            foreach (static::$params as $param => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $param);
                $default = $infos['default'];
                $att = str_replace('-', '_', $param);
                $value = isset($this->config[$att]) ? $this->config[$att] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && ($pref !== $default || isset($infos['force']))) {
                    // Remove # from the color pref as a color type is used for the pref input.
                    $params[] = $param . '=' . str_replace('#', '', $pref);
                } elseif ($value !== '') {
                    // Remove the # in the color attribute just in case…
                    $params[] = $param . '=' . str_replace('#', '', $value);
                }
            }

            return $params;
        }

        /**
         * Get the player size.
         */
        public function getSize()
        {
            $dims = array();

            foreach (static::$dims as $dim => $infos) {
                $pref = \get_pref(strtolower(str_replace('\\', '_', get_class($this))) . '_' . $dim);
                $default = $infos['default'];
                $value = isset($this->config[$dim]) ? $this->config[$dim] : '';

                // Add attributes values in use or modified prefs values as player parameters.
                if ($value === '' && $pref !== $default) {
                    $dims[$dim] = $pref;
                } elseif ($value !== '') {
                    $dims[$dim] = $value;
                } else {
                    $dims[$dim] = $default;
                }
            }

            if (!$dims['width'] || !$dims['height']) {
                // Work out the aspect ratio.
                preg_match("/(\d+):(\d+)/", $dims['ratio'], $matches);
                if (isset($matches[0]) && $matches[1]!=0 && $matches[2]!=0) {
                    $aspect = $matches[1] / $matches[2];
                } else {
                    $aspect = 1.778;
                }

                // Calcuate the new width/height.
                if ($dims['width']) {
                    $dims['height'] = $dims['width'] / $aspect;
                    preg_match("/(\D+)/", $dims['width'], $unit);
                    isset($unit[0]) ? $dims['height'] .= $unit[0] : '';
                } elseif ($dims['height']) {
                    $dims['width'] = $dims['height'] * $aspect;
                    preg_match("/(\D+)/", $dims['height'], $unit);
                    isset($unit[0]) ? $dims['width'] .= $unit[0] : '';
                }
            }

            return $dims;
        }

        /**
         * Check if the play property is a recognised URL scheme.
         */
        public function getInfos()
        {
            $isUrl = preg_grep('/([.][a-z]+\/)/', $this->getPlay());

            if ($this->infos || ($isUrl && $this->setInfos() !== false)) {
                return $this->infos;
            }

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
         * Get the player code
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
