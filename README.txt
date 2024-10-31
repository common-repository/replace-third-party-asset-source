=== Replace Asset Source ===
Contributors: leej3nn20
Tags: asset, style, script, replace css, replace javascript
Requires at least: 5.0.1
Tested up to: 6.1
Stable tag: 1.3.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace plugins or themes asset source with your own desired source. Not design for auto local host asset file. But you can use for it too.

== Description ==

Replace any plugin or theme asset source with your own desired source. Main purpose is to help WordPress users to change the slow third party scripts or styles which using by some plugins but slow load speed in certain countries.

Usually, you could use some coding skills to find the original enqueue method from the target plugin, then dequeue it, and enqueue your own version of asset file. But you need to take care and follow the same way how the original plugin enqueued and the dependency plus version. Otherwise, you might encounter some unexpected issues.

With the help of this plugin, you can easily replace the asset source file without coding skills.

This plugin is not meant to auto local host the third party asset file, but you could use for it too. Upload your own local version of asset file in a better and secured folder (maybe from your theme folder), get the public accessible url, use it on the plugin setting page. Just try it out and have fun.

= How It Works = 

Once a new replacement set, this plugin will do a match on all queued asset styles and scripts source. If found the matching source, it will replaces target source URL with your replacement URL.

This plugin is not going to dequeue the target asset and enqueue your replacement asset. Issue might be happened if the new enqueue not follow the original plugin or theme like missing dependency or version incorrect etc.

If the target asset url not using the standard WordPress enqueue script method by the developer, the replacement will not works.

= Use Case =

There is a block in Malaysia since end of September 2021, which not allowed all local ISP network to access **maxcdn.bootstrapcdn.com**.

If there is a plugin in your site using the asset file hosted on **maxcdn.bootstrapcdn.com**, the visitor from Malaysia will encounter super slow loading issue.

With the help of this plugin, you can easily replace this asset file url to maybe **cdnjs.cloudflare.com** without disturbing the functionality of the original plugin.

**Tutorial for this use case:**

[Solve maxcdn bootstrapcdn problem in Malaysia](https://itchycode.com/use-replace-asset-source-wordpress-plugin-to-solve-maxcdn-bootstrapcdn-mcmc-blocked-issue-in-malaysia/)

== Installation ==

From within WordPress

1. Visit **Plugins > Add New**
1. Search for **Replace Asset Source**
1. Click the **Install Now** button to install the plugin
1. Click the **Activate** button to activate the plugin
1. The setting page will be found at **Settings > Replace Asset Source**
1. Follow the instructions in the setting page.

Manually

1. Upload `replace-third-party-asset-source.php` to the `/wp-content/plugins/` directory
1. **Activate** the plugin through the 'Plugins' menu in WordPress
1. The setting page will be found at **Settings > Replace Asset Source**
1. Follow the instructions in the setting page.


== Screenshots ==

1. Setting page.
2. Inspect and get the target url you wish to replace.
3. Setup a replacement.
4. After replacement, inspect again to check if it's working.

== Frequently Asked Questions ==

**How to find the Target Asset URL in my WordPress site?**

You need to inspect your website HTML elements to get the correct url. Do not put in the parameters. (Remove all values after `?` included the question mark)

**Will this plugin slow down my site?**

No, there is no additional css or js loading in frontend. In backend, just some tiny css and js loaded in this plugin's setting page.

**Can the replacement of asset url taking effect on WordPress Backend(wp-admin) too?**

Yes, the replacement will be taking effect in wp-admin area too.

**Why my target asset not being replaced successfully?**

Double check if your cache system cleared. Sometimes it could be the target asset directly written and harcoded in the plugin or theme without using standard WordPress enqueue way.

**What if I encounter difficulty when using this plugin and need help?**

Welcome to open a [support topic](https://wordpress.org/support/plugin/replace-third-party-asset-source/). Or you could shoot me an email at me@jenn.support.


== Changelog ==

= 1.3.1 2022-09-14 =
* Fix: Trailing comma error in lower PHP version.

= [1.3.0] 2022-03-21 =
* Enhance matching target asset logic
* Tested and working in Oxygen Builder
* Change: Filter `rtpas_matched_styles` replaced by `rtpas_enqueue_matched_styles`
* Change: Filter `rtpas_matched_scripts` replaced by `rtpas_enqueue_matched_scripts`
* New: Filter `rtpas_matched_replacement`
* New: Filter `rtpas_loader_matched_style`
* New: Filter `rtpas_loader_matched_script`
* Example of filter usage will be published in [my technical sharing blog](itchycode.com)
* Code refactoring

= [1.2.0] 2022-03-18 =
* Enhance matching target asset logic

= [1.1.0] 2021-11-15 =
* Provide filters for developer to dynamically adjust replacement list
* Added `rtpas_matched_styles` to filter matched styles
* Added `rtpas_matched_scripts` to filter matched scripts
* Fixed undefined offset error

= [1.0.2] 2021-11-07 =
* Enhance to support replacement if the target source only registered but not queue

= [1.0.1] 2021-10-28 =
* Initial release.
