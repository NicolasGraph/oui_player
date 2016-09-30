# oui_player beta (formerly oui_video)

Easily embed customized players.

## Plugin requirements

oui_player's minimum requirements:

* [Textpattern CMS](http://textpattern.com/) 4.6+.

## Installation

1. [Download](https://github.com/NicolasGraph/oui_player/releases) the compiled plugin file, or, better, download the source and edit the manifest.json file to only include needed providers before to compile the plugin with the [MassPlugCompiler](https://github.com/gocom/MassPlugCompiler). Most frequently used providers should be included first to increase the plugin perfomances.
1. Paste the content of the plugin file under the **Admin > Plugins**, upload it and install;
1. Click *Options* or visit your **Admin>Preferences** tab to fill the plugin prefs.

## Supported players

### Video

* Vimeo;
* Youtube;
* Dailymotion;
* Myspace videos;
* Twitch;
* Abc News.

### Music

* Soundcloud;
* Mixcloud.

## Tags

### oui_player

Embeds a video in the page using an iframe.
*More informations and attributes in the plugin help file.*

`<txp:oui_player play="…" />`

### oui_if_player

Checks a video URL against the accepted URL schemes for one or all supported providers.
*More informations and attributes in the plugin help file.*

```
<txp:oui_if_player video="…">
[…]
</txp:oui_if_player>
```

## Examples

*More informations and attributes in the plugin help file.*

```
<txp:oui_if_player provider="vimeo" play="https://vimeo.com/155020267">
    <txp:oui_player provider="vimeo" play="155020267" autoplay="1" color="0099FF" />
</txp:oui_if_player>
```

## Author

[Nicolas Morand](https://twitter.com/NicolasGraph), inspired by [arc_youtube](http://andy-carter.com/txp/arc_youtube) and [arc_vimeo](http://andy-carter.com/txp/arc_vimeo) by [Andy Carter](http://andy-carter.com).
*Thank you to the Textpattern community and the core team.*

## License

This plugin is distributed under the [MIT licence](https://opensource.org/licenses/MIT).

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
