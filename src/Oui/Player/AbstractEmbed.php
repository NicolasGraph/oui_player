<?php

/*
 * This file is part of oui_player_provider,
 * an extendable plugin to easily embed
 * customizable players in Textpattern CMS.
 *
 * https://github.com/NicolasGraph/oui_player_provider
 *
 * Copyright (C) 2018 Nicolas Morand
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA..
 */

/**
 * AbstractEmbed
 *
 * @package Oui\Player
 */

abstract class AbstractEmbed extends AbstractAdmin implements EmbedInterface
{
    /**
     * The provider name (set from the class name).
     *
     * @var string
     * @see setProvider(), getProvider().
     */

    protected static $provider;

    /**
     * The player base path.
     *
     * @var string
     * @see getSrcBase().
     */

    protected static $srcBase;

    /**
     * Strings sticking different player URL parts.
     *
     * @var array
     * @see setSrcGlue(), getSrcGlue(), resetSrcGlue(), getSrc().
     */

    protected static $srcGlue = array('/', '?', '&amp;');

    /**
     * URL of a script to embed.
     *
     * @var string
     * @example 'https://platform.vine.co/static/scripts/embed.js'
     * @see getScript(), embedScript(), $scriptEmbedded.
     */

    protected static $script;

    /**
     * Whether the script is already embed or not.
     *
     * @var bool
     * @see embedScript(), getScriptEmbedded().
     */

    protected static $scriptEmbedded = false;

    /**
     * Initial player size.
     *
     * @var array
     * @see getIniDims(), getTagAtts().
     */

    protected static $iniDims = array(
        'width'      => '640',
        'height'     => '',
        'ratio'      => '16:9',
        'responsive' => array(
            'default' => 'false',
            'valid'   => array('true', 'false'),
        ),
    );

    /**
     * Current Player size.
     *
     * @var array
     * @see setDims(), getDims().
     */

    protected $dims;

    /**
     * The value provided through the play attribute.
     *
     * @var string
     * @see setMedia(), getMedia().
     */

    protected $media;

    /**
     * Associative array of different media types related values.
     * scheme: regex to check against a media URL/filename;
     * id: index of the media ID in the matches;
     * glue: an optional string to append to the first ID if multiple ID's can be macthed in the same URL;
     * prefix: an optional string to prepend to the current ID.
     *
     * @var array
     * @example
     * protected static $mediaPatterns = array(
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
     * @see getMediaPatterns(), setMediaInfos().
     */

    protected static $mediaPatterns = array();

    /**
     * Media related infos.
     *
     * @var array
     * @see setMediaInfos(), getMediaInfos().
     */

    protected $mediaInfos;

    /**
     * Player parmaters and their values.
     *
     * @var array
     * @see setParams(), getParams().
     */

    protected $params;

    /**
     * Initial player parameters and related options.
     *
     * @var array
     * @example
     * protected static $iniParams = array(
     *     'size'  => array(
     *         'default' => 'large',
     *         'force'   => true,
     *         'valid'   => array('large', 'small'),
     *     ),
     * );
     *
     * Where 'size' is a player parameter and 'large' is its default value.
     * 'force' allows to set the parameter even if its value is the default one.
     * The 'valid' key accept an array of values or a string as an HTML input type.
     * @see getIniParams(), getTagAtts().
     */

    protected static $iniParams = array();

    /**
     * Player label and labeltag
     *
     * @var array
     * @see setLabel(), getLabel().
     */

    protected $label;

    /**
     * Player wraptag and class
     *
     * @var array
     * @see setWrap(), getWrap().
     */

    protected $wrap;

    /**
     * class related preferences event.
     *
     * @var string
     * @see setPrefsEvent(), getPrefsEvent().
     */

    protected static $prefsEvent;

    /**
     * Associative array of preference ID's — names without event prefix — and their current values.
     *
     * @var array.
     * @see setPrefs(), getPrefs().
     */

    protected static $prefs;

    /**
     * Constructor.
     */

    final public function __construct()
    {
        self::setProvider();

        $lcProvider = strtolower(self::getProvider());

        parent::__construct();

        foreach (Player::getIniTagAtts() as $tag => $atts) {
            $tagMethod = str_replace(array('oui', '_'), array('render', ''), $tag);
            $tag = str_replace('player', $lcProvider, $tag);

            \Txp::get('\Textpattern\Tag\Registry')->register(array($this, $tagMethod), $tag);
        }
    }

    /**
     * {@inheritDoc}
     */

    final public function setLabel($txt, $tag = '') {
        $this->label = array($txt, $tag);

        return $this;
    }

    /**
     * $label property getter.
     *
     * @return array
     */

    final protected function getLabel() {
        return $this->label;
    }

    /**
     * {@inheritDoc}
     */

    final public function setWrap($tag, $class = '') {
        $this->wrap = array($tag, $class);

        return $this;
    }

    /**
     * $wrap property getter.
     *
     * @return array
     */

    final protected function getWrap() {
        return $this->wrap;
    }

    /**
     * {@inheritDoc}
     */

    final public function setMedia($value, $fallback = false)
    {
        $this->media = is_array($value) ? array_unique($value) : $value;
        $this->setMediaInfos($fallback);

        return $this;
    }

    /**
     * {@inheritDoc}
     */

    final public function getMedia()
    {
        if (!$this->media) {
            throw new \Exception('Undefined $media property, use setMedia(…) before trying to get it.');
        }

        return $this->media;
    }

    /**
     * {@inheritDoc}
     */

    public function setParams($nameVals = null)
    {
        $this->params = array();

        foreach (self::getIniParams() as $param => $infos) {
            $pref = str_replace('#', '', static::getPref($param));
            $default = is_array($infos) ? $infos['default'] : $infos;
            $attName = str_replace('-', '_', $param);
            isset($nameVals[$attName]) ? $att = $nameVals[$attName] : '';

            if (isset($att)) {
                if ($att !== $default || isset($infos['force'])) {
                    $this->params[$param] = $att;
                }

                unset($att);
            } elseif ($pref !== $default || isset($infos['force'])) {
                $this->params[$param] = $pref;
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */

    public function getParams()
    {
        $this->params !== null ?: $this->setParams();

        return $this->params;
    }

    /**
     * $params property item getter.
     *
     * @param  string $name Parameter name.
     * @return string|array Parameter value or the $params full array.
     */

    final protected function getParam($name)
    {
        $params = $this->getParams();

        return isset($params[$name]) ? $params[$name] : null;
    }

    /**
     * $provider property setter.
     */

    final protected static function setProvider()
    {
        static::$provider = substr(strrchr(get_called_class(), '\\'), 1);
    }

    /**
     * {@inheritDoc}
     */

    final public static function getProvider()
    {
        self::setProvider();

        return static::$provider;
    }

    /**
     * {@inheritDoc}
     */

    final public static function getScript($wrap = false)
    {
        if (isset(static::$script)) {
            return $wrap ? '<script src="' . static::$script . '"></script>' : static::$script;
        }

        return null;
    }

    /**
     * $scriptEmbedded property getter.
     *
     * @return bool|null null if the $script property is not set.
     */

    final protected static function getScriptEmbedded()
    {
        return isset(static::$script) ? static::$scriptEmbedded : null;
    }

    /**
     * $iniDims property getter.
     *
     * @return array
     */

    final protected static function getIniDims()
    {
        return static::$iniDims;
    }

    /**
     * $iniParams property getter.
     *
     * @return array
     */

    final protected static function getIniParams()
    {
        return static::$iniParams;
    }

    /**
     * $mediaPatterns property getter.
     *
     * @return array
     */

    final protected static function getMediaPatterns()
    {
        if (array_key_exists('scheme', static::$mediaPatterns)) {
            return array(static::$mediaPatterns);
        }

        return static::$mediaPatterns;
    }

    /**
     * $srcBase property getter.
     *
     * @return string
     */

    final protected static function getSrcBase()
    {
        return static::$srcBase;
    }

    /**
     * $srcGlue property getter.
     *
     * @param integer $i Index of the $srcGlue value to get;
     * @return mixed Value of the $srcGlue item as string, or the $srcGlue array.
     */

    final protected static function getSrcGlue($i = null)
    {
        return $i ? static::$srcGlue[$i] : static::$srcGlue;
    }

    /**
     * $srcGlue property setter.
     *
     * @param integer $i     Index of the $srcGlue value to set;
     * @param string  $value Value of the $srcGlue item.
     */

    final protected static function setSrcGlue($i, $value)
    {
        static::$srcGlue[$i] = $value;
    }

    /**
     * {@inheritDoc}
     */

    final public function embedScript()
    {
        $out = ob_get_contents();

        ob_clean();
        echo str_replace('</body>', self::getScript(true) . n . '</body>', $out);

        static::$scriptEmbedded = true;
    }

    /**
     * {@inheritDoc}
     */

    final public static function getIniPrefs()
    {
        return array_merge(self::getIniDims(), self::getIniParams());
    }

    /**
     * {@inheritDoc}
     */

    final public static function getTagAtts($tag)
    {
        $atts = array_keys(array_merge(self::getIniDims(), self::getIniParams()));
        $parsedAtts = array();

        foreach ($atts as $att) {
            $parsedAtts[] = str_replace('-', '_', $att); // Underscore to hyphen in attribute names.
        }

        return $parsedAtts;
    }

    /**
     * Set the current media(s) infos.
     *
     * @param  bool  $fallback Whether to set fallback $mediaInfos values or not.
     * @return array
     */

    final protected function setMediaInfos($fallback = false)
    {
        $medias = $this->getMedia();
        !is_array($medias) ? $medias = array($medias) : '';
        $this->mediaInfos = array();

        foreach ($medias as $media) {
            $notId = preg_match('/([.][a-z]+)/', $media); // URL or filename.

            if ($notId) {
                $glue = null;

                // Check the URL or filename against defined $mediaPatterns property values.
                foreach (self::getMediaPatterns() as $pattern => $options) {
                    if (preg_match($options['scheme'], $media, $matches)) {
                        $prefix = isset($options['prefix']) ? $options['prefix'] : '';

                        if (!array_key_exists($media, $this->mediaInfos)) {
                            $this->mediaInfos[$media] = array(
                                'id'      => $matches[$options['id']],
                                'uri'     => $prefix . $matches[$options['id']],
                                'pattern' => $pattern,
                            );

                            if (!isset($options['glue'])) {
                                break;
                            } else { // Bandcamp and Youtube, at least, accept multiple matches.
                                $glue = $options['glue'];
                            }
                        } else {
                            $this->mediaInfos[$media]['uri'] .= $glue . $prefix . $matches[$options['id']];
                            $this->mediaInfos[$media]['pattern'] = $pattern;
                        }
                    }
                }
            } elseif ($fallback) {
                $this->mediaInfos[$media] = array(
                    'uri' => $media,
                );
            }

            if (method_exists($this, 'resetSrcGlue') && array_key_exists($media, $this->mediaInfos)) {
                $this->resetSrcGlue($media);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */

    final public function getMediaInfos($fallback = false)
    {
        $this->mediaInfos or $this->setMediaInfos($fallback);

        return $this->mediaInfos;
    }

    /**
     * {@inheritDoc}
     */

    final public function setDims(
        $width = null,
        $height = null,
        $ratio = null,
        $responsive = null
    ) {
        // Get dimensions from attributes, or fallback to preferences.
        $atts = compact('width', 'height', 'ratio');

        foreach (self::getIniDims() as $dim => $value) {
            if ($dim !== 'responsive') {
                is_bool($atts[$dim]) || $atts[$dim] === '' ? $atts[$dim] = '00' : '';

                $$dim = str_replace(' ', '', $atts[$dim] ? $atts[$dim] : static::getPref($dim));

                if ($dim !== 'ratio') {
                    $dUnit = $dim[0] . 'Unit';
                    $$dUnit = preg_match("/\D+/", $$dim, $match) ? $match[0] : '';
                    $$dim = (int) $$dim;
                }
            }
        }

        if (method_exists($this, 'getData') && (!$width && !$height || !$ratio && (!$width || !$height))) {
            $width = $this->getData('width');
            $height = $this->getData('height');
            $wUnit = $hUnit = '';
        }

        // Work out the provided ratio.
        $aspect = null;

        if (!empty($ratio)) {
            if (preg_match("/(\d+):(\d+)/", $ratio, $matches)) {
                list(, $wRatio, $hRatio) = $matches;
            }

            if (empty($wRatio) || empty($hRatio)) {
                throw new \Exception(gTxt('oui_player_invalid_ratio', array('{ratio}' => $ratio)));
            }

            $aspect = $wRatio / $hRatio;
        }

        // Calculate player width and/or height.
        if ($responsive === '') {
            $responsive = static::getPref('responsive') === 'true';
        } else {
            $responsive = $responsive ? true : false;
        }

        if ($responsive) {
            if ($aspect) {
                $height = 1 / $aspect * 100 . '%';
            } elseif (isset($height)) {
                if (!$width || !$height) {
                    throw new \Exception(gTxt('undefined_player_size'));
                }

                $wUnit === $hUnit ? $height = $height / $width * 100 . '%' : '';
            }

            $width = '100%';
        } else {
            if (isset($height) && (!$width || !$height)) {
                if (!$aspect) {
                    throw new \Exception(gTxt('undefined_player_size'));
                }

                if ($width) {
                    $height = $width / $aspect;
                    $wUnit ? $height .= $wUnit : '';
                } else {
                    $width = $height * $aspect;
                    $hUnit ? $width .= $hUnit : '';
                }
            }
        }

        // Re-append unit if needed.
        is_int($width) && $wUnit && $wUnit !== 'px' ? $width .= $wUnit : '';

        if (isset($height)) {
            $responsive && !$hUnit ? $hUnit = 'px' : '';
            is_int($height) && $hUnit && ($responsive || $hUnit !== 'px') ? $height .= $hUnit : '';
        }

        $this->dims = compact('width', 'height', 'responsive');

        return $this;
    }

    /**
     * {@inheritDoc}
     */

    final public function getDims()
    {
        $this->dims ?: $this->setDims();

        return $this->dims;
    }

    /**
     * Build the player src value.
     *
     * @return string
     */

    protected function getSrc()
    {
        $media = $this->getMedia();
        $media = $this->getMediaInfos(true)[$media]['uri'];
        $srcGlue = self::getSrcGlue();
        $src = self::getSrcBase() . $srcGlue[0] . $media; // Stick player URL and ID.

        // Stick defined player parameters.
        $params = $this->getParams();

        if (!empty($params)) {
            $joint = strpos($src, $srcGlue[1]) ? $srcGlue[2] : $srcGlue[1]; // Avoid repeated srcGlue elements (interrogation marks).
            $src .= $joint . http_build_query($params, '', $srcGlue[2]); // Stick.
        }

        return $src;
    }

    /**
     * {@inheritDoc}
     */

    public function getHTML() {

        $src = $this->getSrc();

        if (!$src) {
            return;
        }

        // Embed the provider related $script if needed.
        if (self::getScript() && !self::getScriptEmbedded()) {
            register_callback(array($this, 'embedScript'), 'textpattern_end');
        }

        $dims = $this->getDims();

        extract($dims);

        // Define responsive related styles.
        $style = 'style="border: none';
        $wrapStyle = '';

        if ($responsive) {
            $style .= '; position: absolute; top: 0; left: 0; width: 100%; height: 100%';
            $wrapStyle .= 'style="position: relative; padding-bottom:' . $height . '; height: 0; overflow: hidden"';
            $width = $height = false;
        } else {
            foreach (array('width', 'height') as $dim) {
                if (is_string($$dim)) {
                    $style .= '; ' . $dim . ':' . $$dim;
                    $$dim = false;
                }
            }
        }

        $style .= '"';

        // Build the player code.
        $player = sprintf(
            '<iframe title="%s" src="%s"%s%s %s %s></iframe>',
            gTxt('oui_player_iframe_title', array('{provider}' => self::getProvider())),
            $src,
            !$width ? '' : ' width="' . $width . '"',
            !$height ? '' : ' height="' . $height . '"',
            $style,
            'allowfullscreen'
        );

        list($wraptag, $class) = $this->getWrap();
        list($label, $labeltag) = $this->getLabel();

        $wrapStyle && !$wraptag ? $wraptag = 'div' : '';
        $wraptag ? $player = n . $player . n : '';

        $out = doLabel($label, $labeltag) . n . doTag($player, $wraptag, $class, $wrapStyle);

        return $out;
    }

    /**
     * {@inheritDoc}
     */

    final public function render()
    {
        return pluggable_ui(self::getPlugin() . '_ui', strtolower(self::getProvider()), $this->getHTML(), $this);
    }

    /**
     * {@inheritDoc}
     */

    final public static function renderPlayer($atts)
    {
        $atts['provider'] = self::getProvider();

        return \Txp::get('\Oui\Player\Player')->renderPlayer($atts);
    }

    /**
     * {@inheritDoc}
     */

    final public static function renderIfPlayer($atts, $thing)
    {
        $atts['provider'] = self::getProvider();

        return \Txp::get('\Oui\Player\Player')->renderIfPlayer($atts, $thing);
    }
}
