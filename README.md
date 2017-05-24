# oui_player (formerly oui_video)

An extendable plugin to easily display HTML5 players or embed customized iframe players in [Textpattern CMS](http://www.textpatern.com).
This plugin does not use oembed, it builds iframe embedding codes by its own without any external request.

## Supported providers

* [Abc News](http://abcnews.go.com/video);
* [Archive](https://archive.org/);
* [Bandcamp](https://bandcamp.com/);
* [Dailymotion](http://www.dailymotion.com/);
* [Mixcloud](https://www.mixcloud.com/);
* [Myspace videos](https://myspace.com/myspace/videos);
* [Soundcloud](https://soundcloud.com/);
* [Twitch](https://www.twitch.tv/);
* [Viddsee](https://www.viddsee.com/);
* [Vimeo](http://www.vimeo.com/);
* [Vine](http://vine.co/);
* [Youtube](https://www.youtube.com/).

## Plugin requirements

oui_player's minimum requirements:

* [Textpattern CMS](http://textpattern.com/) 4.6+.

## Customization (advanced users)

This plugin can be customized by excluding/adding providers via the _manifest.json_ file. Then it can be installed via [Composer](https://getcomposer.org) or compiled via this [MassPlugCompiler fork](https://github.com/NicolasGraph/MassPlugCompiler).

## Installation

### From the admin interface

1. [Download](https://github.com/NicolasGraph/oui_player/releases) the compiled plugin file or the source to compile a customized file.
2. Paste the content of the compiled plugin file under the "Admin > Plugins":?event=plugin tab and click the _Upload_ button.
3. Confirm the plugin install by clicking the _Install_ button on the plugin preview page.
4. Enable the plugin and click _Options_ or visit your *Admin > Preferences* tab to fill the plugin prefs.

### Via Composer

After [installing Composer](https://getcomposer.org/doc/00-intro.md)…

1. Target your project directory:
`$ cd /path/to/your/textpattern/installation/dir`
2. If it's not already done, lock your version of Txp:
`$ composer require textpattern/lock:4.6.2`, where `4.6.2` is the Txp version in use.
3. Install oui_player:
`$ composer require oui/oui_player`
4. Connect to the Txp admin interface and click _Options_ or visit your *Admin > Preferences* tab to fill the plugin prefs.

## Author

[Nicolas Morand](https://twitter.com/NicolasGraph), inspired by [arc_youtube](http://andy-carter.com/txp/arc_youtube) and [arc_vimeo](http://andy-carter.com/txp/arc_vimeo) by [Andy Carter](http://andy-carter.com).
*Thank you to the Textpattern community and the core team.*

## License

This plugin is distributed under the [MIT licence](https://opensource.org/licenses/MIT).

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
