=== TP Product Image Flipper for WooCommerce ===
Contributors: tpplugins
Donate link: https://www.tplugins.com/
Tags: product flipper image, woocommerce product flipper image, woocommerce product image flipper, woocommerce product gallery flipper
Requires at least: 4.2
Tested up to: 6.9.4
Stable tag: 2.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a secondary image to your products on shop and category pages that flips on mouse hover.

== Description ==

Enhance your WooCommerce store with a beautiful product image flipper. Give your customers a glimpse of another image from your product gallery — 100% responsive and mobile-friendly.

This plugin displays your product's featured image along with the first gallery image and flips between them on mouse hover.

<a href="https://tplugins.com/demos/gallery/tp-gallery-pro-demo.html" target="_blank">VIEW DEMO</a>

== 🚀 Upgrade to TP Gallery PRO — The All-in-One WooCommerce Gallery Solution ==

**Looking for more than just image flipping?** TP Gallery PRO includes everything this plugin does — and much more. One plugin to replace multiple gallery, slider, and swatch plugins.

<a href="https://www.tplugins.com/product/tp-gallery-pro/" target="_blank">🔗 Learn More About TP Gallery PRO</a> | <a href="https://tplugins.com/demos/gallery/tp-gallery-pro-demo.html" target="_blank">🎬 View Live Demo</a>

### 🛒 Loop Gallery — Flipper & Slider Modes
Everything this plugin does, plus a full slider option:
* **Flipper Mode** — Swap images on hover (just like this plugin)
* **Slider Mode** — Mini gallery with navigation arrows on each product
* **Customizable Arrows** — Circle, square, rounded, or minimal styles
* Works on shop, category, and all archive pages

### 🖼️ Product Page Gallery
Replace the default WooCommerce gallery:
* **5 Gallery Layouts** — Thumbnails bottom, left, right, grid mosaic, or slider only
* **3 Zoom Types** — Inner zoom, lens (magnifying glass), or window zoom
* **Video Support** — Embed YouTube & Vimeo videos directly in your gallery
* **Full-Screen Lightbox** — Beautiful lightbox with smooth transitions

### 🎨 Visual Variation Swatches
Replace dropdown selects with stunning visual options:
* **Color Swatches** — Visual color selection
* **Image Swatches** — Mini thumbnails for each option
* **Label Swatches** — Styled text buttons

### 🎯 Per-Variation Galleries
Display unique image sets for each product variation — automatic gallery switching when selecting a variation.

### 📦 Product, Category & Brand Sliders
Create beautiful carousels anywhere on your site with shortcodes:
* Product sliders with 5 design presets
* Category sliders (circle, grid, carousel styles)
* Brand sliders with 7 unique styles
* Cross-sells & upsells as Swiper sliders

### ⚡ Optimized Performance
* **Conditional Loading** — Scripts load only on relevant pages
* **HPOS Compatible** — Full High-Performance Order Storage support
* **FSE Ready** — Works perfectly with Full Site Editing / Block themes
* **100% RTL Support** — Perfect for Hebrew, Arabic, and other RTL languages

<a href="https://www.tplugins.com/product/tp-gallery-pro/" target="_blank">👉 **Get TP Gallery PRO Now — One Plugin, Endless Possibilities**</a>

---

### Free Plugin Features
* Flip between 2 images on product shop/category pages
* Responsive layout
* Responsive images with srcset and sizes attributes
* 100% mobile friendly
* Compatible with most premium themes
* Compatible with HPOS (High-Performance Order Storage)
* Remove duplicate images: Ensures only plugin-generated images are shown
* Images from gallery only: Use only gallery images for the flipper

### Pro Version Features 
* Works with Elementor products grid
* Display all product gallery images
* Responsive layout
* Navigation support
* Slider autoplay options
* Show/hide dots navigation
* Customizable slider arrows (color/background/icons)
* Customizable slider dots (circle/square/rectangle)
* Infinite loop
* Mouse dragging option
* RTL support
* Touch and swipe support
* 36 animation transform types
* Compatible with most premium themes
* Image size selection (all theme sizes available)
* Change images by dots or thumbnails

### Pro Version Video
[youtube https://www.youtube.com/watch?v=XRCnK5OF0TQ]

== Installation ==

1. Unzip the downloaded zip file.
2. Upload the plugin folder to the `wp-content/plugins/` directory of your WordPress site.
3. Activate TP Product Image Flipper for WooCommerce through the 'Plugins' menu in WordPress.

No configuration needed — just activate the plugin and you're done!

== 👍 You May Also Like ==

- 🆕 [TP Advanced Search for WooCommerce PRO](https://www.tplugins.com/product/tp-advanced-search-for-woocommerce-pro/)

- ✅ [TP WooCommerce Product Gallery](https://wordpress.org/plugins/tp-woocommerce-product-gallery/)

- ✅ [TP Restore Categories And Taxonomies](https://wordpress.org/plugins/tp-restore-categories-and-taxonomies/)

- ✅ [TP Price Drop Notifier for WooCommerce](https://wordpress.org/plugins/tp-price-drop-notifier-for-woocommerce/)

- ✅ [TP Product Quick View for WooCommerce](https://wordpress.org/plugins/tp-product-quick-view-for-woocommerce/)

- ✅ [TP Product Tooltip for WooCommerce](https://wordpress.org/plugins/tp-product-tooltip/)

= Change Image Size =
TP Product Image Flipper uses the WooCommerce image size called `woocommerce_thumbnail`. To change this size, use our filter:

`add_filter('tppif_image_size', 'your_function');`

= Example =
The following example uses full image size. Add this function to your theme's functions.php file:

`<?php
add_filter('tppif_image_size', 'tp_change_flipper_image_size');
function tp_change_flipper_image_size($default_image_size) {
    return 'full';
}
?>`


== Frequently Asked Questions ==

= Do I need WooCommerce installed on my site? =

Yes, this plugin is designed to work with WooCommerce, so you need an active WooCommerce store.

= What about mobile devices? =

TP Product Image Flipper for WooCommerce is 100% responsive and tested on all devices and screen resolutions. However, on mobile devices there is no hover event, so only the first image will be displayed.

= I need a feature that you don't have =

Check out <a href="https://www.tplugins.com/product/tp-gallery-pro/" target="_blank">TP Gallery PRO</a> — it includes the Loop Gallery feature with both Flipper and Slider modes, plus product page galleries, visual swatches, variation galleries, and much more. Or <a href="https://www.tplugins.com/contact-us/" target="_blank">contact us</a> with your feature request.

= I activated the plugin but it's not working =

If the plugin is not working after activation, this could be due to several reasons. The most common cause is another plugin using the `remove_action` hook.

TP Product Image Flipper uses `remove_action` on `woocommerce_template_loop_product_thumbnail` to replace the default WooCommerce image on shop/category pages. If another plugin or theme does the same, it can create a conflict.

= Can I change the image size? =

Yes! Use this filter to change the image size:

`add_filter('tppif_image_size', 'your_function_name');
function your_function_name($default_image_size) {
    return 'thumbnail';
}
`

== Screenshots ==

1. Shop Page (1/4)
2. Pro Version dots mode (2/4)
3. Pro Version thumbs mode (3/4)
4. Pro Version thumbs mode with custom CSS (4/4)

== Changelog ==

= 2.0.6 - Update =
* Update - WordPress 6.9 compatibility
* Update - WooCommerce 10.4.2 compatibility

= 2.0.5 - Update =
* Update - WordPress 6.8.2 compatibility
* Update - WooCommerce 10.1.2 compatibility

= 2.0.4 - Update =
* Update - WordPress 6.7.1 compatibility
* Update - WooCommerce 9.5.2 compatibility

= 2.0.3 - Update =
* Update - WordPress 6.5.2 compatibility
* Update - WooCommerce 8.7.0 compatibility

= 2.0.2 - Update =
* Update - WordPress 6.4.2 compatibility
* Update - WooCommerce 8.4.0 compatibility

= 2.0.1 - Update =
* Update - WordPress 6.3.2 compatibility
* Update - WooCommerce 8.2.1 compatibility
* Compatible with HPOS (High-Performance Order Storage)

= 2.0.0 - Update =
* New - Settings page: Go to admin area under WordPress settings, new submenu "TP Product Image Flipper"
* Update - WooCommerce 7.9.0 compatibility
* New option - Remove duplicate images: Fixes image duplication caused by templates/plugins using the same hook
* New option - Images from gallery only
* Added WooCommerce High-Performance Order Storage feature compatibility

= 1.0.8 - Update =
* Update - WordPress 6.2.2 compatibility
* Update - WooCommerce 7.8.2 compatibility

= 1.0.7 - Update =
* Responsive images with srcset and sizes attributes

= 1.0.6 - Update =
* Added new image size filter

= 1.0.5 - Update =
* Update - WordPress 5.8.2 compatibility
* Update - WooCommerce 6.0.0 compatibility

= 1.0.4 - Update - WordPress 5.8 compatibility =

= 1.0.3 - Added ALT attribute to flipper images =
* If image ALT is empty, uses product title instead

= 1.0.2 - Fixed RTL sites admin area CSS =

= 1.0.1 - Updated demo link =

= 1.0.0 - Released on 30 August 2020 =
* Initial release

== Upgrade Notice ==

= 2.0.6 =
Compatibility update for WordPress 6.9 and WooCommerce 10.4.2. Recommended for all users.