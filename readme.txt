=== Tune Image Editors | WPSSO Add-on ===
Plugin Name: WPSSO Tune Image Editors
Plugin Slug: wpsso-tune-image-editors
Text Domain: wpsso-tune-image-editors
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-tune-image-editors/assets/
Tags: image, sharpen, imagemagick, imagick, resize
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.2
Tested Up To: 5.4.1
Stable Tag: 2.2.0

Improves the Look of Thumbnails and Resized Images for Better Click-Through-Rates on Social and Search Sites.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-tune-image-editors/assets/icon-256x256.png"></p>

**Have you noticed that WordPress creates small images that seems a bit "fuzzy" &mdash; nothing like the nice sharp original you uploaded?**

The reason is that after an image is resized, that image *must* be sharpened &mdash; but WordPress doesn't do any sharpening, so the resized image remains a bit "fuzzy" &mdash; not what you want for a featured image or shared image on social sites! ;-)

The WPSSO Tune Image Editors (aka WPSSO TIE) add-on provides this missing WordPress feature &mdash; it automatically applies sharpening to all JPEG images resized by the WordPress ImageMagick library.

**Compatible with all image compression / optimization plugins:**

Image sharpening / adjustments are applied during the WordPress image resize operation, so the resulting resized image / thumbnail can be optimized afterwards as usual.

<h3>Users Love the WPSSO TIE Add-on</h3>

&#x2605;&#x2605;&#x2605;&#x2605;&#x2605; &mdash; "This plugin helped me tune my images to look really good on social media. Great job!" - [undergroundnetwork](https://wordpress.org/support/topic/really-useful-at-making-your-social-media-images-the-best/)

<h3>WPSSO TIE Standard Features</h3>

* Extends the features of the WPSSO Core plugin.

* Applies adjustments to resized images (aka thumbnails) using ImageMagick:

	* Enable or disable image adjustments / sharpening for resized images.
	* Uses a better compression quality of 92% (WordPress default is 82%).
	* Applies a default amount of sharpening values to all resized images.

* Optionally select different primary / secondary image editor(s) for WordPress:

	* GD Only
	* GD and ImageMagick
	* ImageMagick Only
	* ImageMagick and GD (WordPress default)

* Optionally fine-tune the image filter priority and image adjustment options:

	* Modify the default 'image_make_intermediate_size' filter hook priority.
	* Enable / disable contrast leveling.
	* Increase / decrease the compression quality percentage.
	* Adjust sharpening values individually (sigma, radius, amount, threshold).

<h3>WPSSO Core Plugin Required</h3>

WPSSO Tune Image Editors (aka WPSSO TIE) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Tune Image Editors add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/install-the-plugin/).
* [Uninstall the WPSSO Tune Image Editors add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. WPSSO TIE settings page to select the default WordPress image editors and fine-tune the ImageMagick PHP extension.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-tune-image-editors/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-tune-image-editors/)

<h3>Changelog / Release Notes</h3>

**Version 2.2.0 (2020/04/17)**

* **New Features**
	* None.
* **Improvements**
	* Updated the reminder notice text to regenerate images after activating the add-on or changing the settings.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v7.0.1.

**Version 2.1.0 (2020/04/06)**

* **New Features**
	* None.
* **Improvements**
	* Updated "Requires At Least" to WordPress v4.2.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored WPSSO Core active and minimum version dependency checks.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v6.28.0.

**Version 2.0.4 (2020/01/07)**

* **New Features**
	* None.
* **Improvements**
	* Update for the add-on name, menu name, a few option labels, and their French translations.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.0.
	* WPSSO Core v6.27.1.

== Upgrade Notice ==

= 2.2.0 =

(2020/04/17) Updated the reminder notice text to regenerate images after activating the add-on or changing the settings.

= 2.1.0 =

(2020/04/06) Updated "Requires At Least" to WordPress v4.2. Refactored WPSSO Core active and minimum version dependency checks.

= 2.0.4 =

(2020/01/07) Update for the add-on name, menu name, a few option labels, and their French translations.

