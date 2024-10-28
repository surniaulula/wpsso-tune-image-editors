=== WPSSO Tune WP Image Editors ===
Plugin Name: WPSSO Tune WP Image Editors
Plugin Slug: wpsso-tune-image-editors
Text Domain: wpsso-tune-image-editors
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-tune-image-editors/assets/
Tags: image, sharpen, imagemagick, imagick, resize
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.4.33
Requires At Least: 5.9
Tested Up To: 6.7.0
Stable Tag: 4.1.0

Improves the appearance of WordPress images for better click through rates from social and search sites.

== Description ==

<!-- about -->

**Have you noticed that WordPress creates small images that are a bit "fuzzy" - nothing like the nice sharp original you uploaded?**

After resizing an image, the image must be sharpened but WordPress doesn't do any sharpening, so the resized image remains a bit "fuzzy" - not what you want for a featured image or shared image on social sites! The WPSSO Tune WP Image Editors (WPSSO TIE) add-on provides this missing WordPress image sharpened feature - it automatically applies sharpening to all JPEG images resized by the WordPress ImageMagick library.

**Compatible with all image compression / optimization plugins:**

Image sharpening is applied during the WordPress resize operation so the resulting images can still be optimized with any compression / optimization plugin.

<!-- /about -->

<h3>WPSSO TIE Add-on Features</h3>

Extends the features of the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/) (required plugin).

Applies adjustments to resized images (aka thumbnails) using ImageMagick:

* Enable or disable image adjustments / sharpening for resized images.
* Uses a better compression quality of 92% (WordPress default is 82%).
* Applies a default amount of sharpening values to all resized images.

Optionally select different primary / secondary image editor(s) for WordPress:

* GD Only
* GD and ImageMagick
* ImageMagick Only
* ImageMagick and GD (WordPress default)

Optionally fine-tune the image filter priority and image adjustment options:

* Modify the default 'image_make_intermediate_size' filter hook priority.
* Enable / disable contrast leveling.
* Increase / decrease the compression quality percentage.
* Adjust sharpening values individually (sigma, radius, amount, threshold).

<h3>WPSSO Core Required</h3>

WPSSO Tune WP Image Editors (WPSSO TIE) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which creates extensive and complete structured data to present your content at its best for social sites and search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Tune WP Image Editors add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/install-the-plugin/).
* [Uninstall the WPSSO Tune WP Image Editors add-on](https://wpsso.com/docs/plugins/wpsso-tune-image-editors/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. WPSSO TIE settings page to select the default WordPress image editors and fine-tune the ImageMagick PHP extension.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-tune-image-editors/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-tune-image-editors/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium edition customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<p><strong>WPSSO Core Standard edition users (ie. the plugin hosted on WordPress.org) have access to <a href="https://wordpress.org/plugins/wpsso-tune-image-editors/advanced/">the latest development version under the Advanced Options section</a>.</strong></p>

<h3>Changelog / Release Notes</h3>

**Version 4.1.0 (2024/08/25)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v18.10.0.

== Upgrade Notice ==

= 4.1.0 =

(2024/08/25) Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.

