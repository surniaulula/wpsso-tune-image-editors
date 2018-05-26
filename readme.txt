=== WPSSO Tune WP Image Editors - Sharpen Resized / Thumbnail Images for Better Social Shares and SEO ===
Plugin Name: WPSSO Tune WP Image Editors
Plugin Slug: wpsso-tune-image-editors
Text Domain: wpsso-tune-image-editors
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-tune-image-editors/assets/
Tags: image, sharpen, imagemagick, imagick, resize
Contributors: jsmoriss
Requires PHP: 5.4
Requires At Least: 3.8
Tested Up To: 4.9.6
Stable Tag: 1.1.1

WPSSO Core add-on to provide tuning options for the WordPress image editors and PHP image extensions.

== Description ==

<img class="readme-icon" src="https://surniaulula.github.io/wpsso-tune-image-editors/assets/icon-256x256.png">

**Have you noticed that WordPress creates small images that seems a bit "fuzzy" &mdash; nothing like the nice sharp original you uploaded?**

The reason is that after an image is resized, that image *must* be sharpened &mdash; but WordPress doesn't do any sharpening, so the resized image remains a bit "fuzzy" &mdash; not what you want for a featured image or shared image on social sites! ;-)

The WPSSO Tune WP Image Editors (aka WPSSO TIE) add-on provides this missing WordPress feature &mdash; it automatically applies a default amount of sharpening to all JPEG images resized by the WordPress ImageMagick editor.

**Compatible with all image compression / optimization plugins:**

Image sharpening / adjustments are applied during the WordPress image resize operation, so the resulting resized image / thumbnail can be optimized afterwards as usual.

<h3>Users Love the WPSSO TIE Add-on</h3>

&#x2605;&#x2605;&#x2605;&#x2605;&#x2605; &mdash; "This plugin helped me tune my images to look really good on social media. Great job!" - [undergroundnetwork](https://wordpress.org/support/topic/really-useful-at-making-your-social-media-images-the-best/)

<h3>WPSSO TIE Free / Standard Features</h3>

* Extends the features of the WPSSO Core Free or Pro plugin.

* Applies adjustments to resized images (aka thumbnails) using ImageMagick:

	* Enable or disable image adjustments / sharpening for resized images.
	* Uses a better compression quality of 92% (WordPress default is 82%).
	* Applies a default amount of sharpening values to all resized images.

* Optionally select different primary / secondary image editor(s) for WordPress:

	* GD Only
	* GD and ImageMagick
	* ImageMagick Only
	* ImageMagick and GD (WordPress default)

* Download the Free version from [GitHub](https://surniaulula.github.io/wpsso-tune-image-editots/) or [WordPress.org](https://wordpress.org/plugins/wpsso-tune-image-editots/).

<h3>WPSSO TIE Pro / Additional Features</h3>

* Extends the features of WPSSO Core Pro (requires an active and licensed <a href="https://wpsso.com/">WPSSO Core Pro plugin</a>).

* Optionally fine-tune the image filter priority and image adjustment options:

	* Modify the default 'image_make_intermediate_size' filter hook priority.
	* Enable / disable contrast leveling.
	* Increase / decrease the compression quality percentage.
	* Adjust sharpening values individually (sigma, radius, amount, threshold).

<h3>WPSSO Core Plugin Prerequisite</h3>

WPSSO Tune WP Image Editors (aka WPSSO TIE) is an add-on for the WPSSO Core plugin. The WPSSO TIE Free add-on works with either the Free or Pro versions of WPSSO Core. The [WPSSO TIE Pro add-on](https://wpsso.com/extend/plugins/wpsso-tune-image-editors/) uses WPSSO Core Pro features and requires an active and licensed [WPSSO Core Pro plugin](https://wpsso.com/).

== Installation ==

<h3>Install and Uninstall</h3>

* [Install the WPSSO TIE Add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/install-the-plugin/)
* [Uninstall the WPSSO TIE Add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

<h3>Frequently Asked Questions</h3>

* None

== Other Notes ==

<h3>Additional Documentation</h3>

* None

== Screenshots ==

01. WPSSO TIE settings page to select the default WordPress image editors (Free version).

== Changelog ==

<h3>Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Free / Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-tune-image-editors/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-tune-image-editors/)

<h3>Changelog / Release Notes</h3>

**Version 1.1.1 (2018/04/05)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Renamed some WpssoUtil methods for Gutenberg related changes in WPSSO v3.57.0.

== Upgrade Notice ==

= 1.1.1 =

(2018/04/05) Renamed some WpssoUtil methods for Gutenberg related changes in WPSSO v3.57.0.

