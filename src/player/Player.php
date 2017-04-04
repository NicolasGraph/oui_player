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

class Player
{
    protected $plugin;
    protected $providers;
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
        'providers' => array(
        ),
    );

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
}
