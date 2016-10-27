# oui_player (formerly oui_video)

An extendable plugin to easily embed customized iframe players.
This plugin does not use oembed, it builds iframe embedding codes by its own without any external request.

## Supported providers

* [Abc News](http://abcnews.go.com/video);
* [Archive](https://archive.org/);
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

## Customization

This plugin can be customized via [MassPlugCompiler](https://github.com/NicolasGraph/MassPlugCompiler) to exclude unused providers. Most frequently used providers should be included first to increase plugin perfomances.

## Installation

1. [Download](https://github.com/NicolasGraph/oui_player/releases) the compiled plugin file or the source to compile a customized file.
1. Paste the content of the compiled plugin file under the *Admin > Plugins* tab and click the _Upload_ button.
1. Confirm the plugin install by clicking the _Install_ button on the plugin preview page.
1. Enable the plugin and click _Options_ or visit your *Admin > Preferences* tab to fill the plugin prefs.

## Update

Unless contrary instructions, proceed as follow:

1. Follow the installation instruction above.
1. Disable and re-enable the plugin to update its preferences while keeping existing values untouched.

## Uninstall

1. Check the box on the left of the plugin row under the *Admin > Plugins*.
1. open the select list at the bottom of the plugins tables and choose _Delete_.
1. confirm the plugin deletion.

## Author

[Nicolas Morand](https://twitter.com/NicolasGraph), inspired by [arc_youtube](http://andy-carter.com/txp/arc_youtube) and [arc_vimeo](http://andy-carter.com/txp/arc_vimeo) by [Andy Carter](http://andy-carter.com).
*Thank you to the Textpattern community and the core team.*

## License

This plugin is distributed under the [MIT licence](https://opensource.org/licenses/MIT).

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
