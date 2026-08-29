<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.tplugins.com/
 * @since             1.0.0
 * @package           TP_Product_Image_Flipper_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       TP Product Image Flipper for Woocommerce
 * Plugin URI:        https://www.tplugins.com/
 * Description:       Flip between 2 images on product shop/category page.
 * Version:           2.0.8
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Author:            TP Plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tp-product-image-flipper-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to: 11.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TP_PRODUCT_IMAGE_FLIPPER_FOR_WOOCOMMERCE_VERSION', '2.0.8' );
define( 'TP_PRODUCT_IMAGE_FLIPPER_PRO_URL', 'https://www.tplugins.com/product/tp-woocommerce-category-product-slider/' );
define( 'TP_PRODUCT_IMAGE_FLIPPER_NAME', 'TP Product Image Flipper Settings' );

/**
 * Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
 *
 * This has to be registered at file scope, before WooCommerce boots, and it must not
 * depend on WooCommerce being detected first - otherwise the plugin is listed as
 * incompatible on installs where WooCommerce is network activated or lives in a
 * folder other than /woocommerce/.
 */
add_action( 'before_woocommerce_init', 'tppif_declare_hpos_compatibility' );
function tppif_declare_hpos_compatibility() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

/**
 * Boot the plugin once every plugin is loaded, so WooCommerce is detected reliably
 * no matter how it was activated.
 */
add_action( 'plugins_loaded', 'tppif_bootstrap' );
function tppif_bootstrap() {

	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// include the settings page
	require_once plugin_dir_path( __FILE__ ) . 'settings.php';

	add_action( 'wp_enqueue_scripts', 'tp_product_image_flipper_front_scripts' );
	add_action( 'init', 'tppif_remove_action', 15 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'tp_create_flipper_images', 10 );
	add_action( 'wp_footer', 'tppif_script' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tp_product_image_flipper_add_plugin_page_settings_link' );
}

function tp_product_image_flipper_front_scripts() {
	wp_enqueue_style(
		'tp-product-image-flipper-for-woocommerce',
		plugins_url( '/css/tp-product-image-flipper-for-woocommerce.css', __FILE__ ),
		array(),
		TP_PRODUCT_IMAGE_FLIPPER_FOR_WOOCOMMERCE_VERSION
	);
}

function tppif_remove_action() {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
}

/**
 * Build the srcset/sizes attributes for an attachment.
 *
 * Returns an empty string when the attachment has no srcset, so we never emit an
 * empty srcset="" attribute.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $image_size    Registered image size.
 * @param string $srcset_sizes  Value for the sizes attribute.
 * @return string
 */
function tppif_srcset_attributes( $attachment_id, $image_size, $srcset_sizes ) {
	$srcset = wp_get_attachment_image_srcset( $attachment_id, $image_size );

	if ( ! $srcset ) {
		return '';
	}

	return ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $srcset_sizes ) . '"';
}

function tp_create_flipper_images() {

	$product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;

	// Some themes and block based templates fire this hook without setting the
	// global product, so fall back to the current post before giving up.
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$get_gallery_image_ids = $product->get_gallery_image_ids();
	$get_gallery_image_ids = is_array( $get_gallery_image_ids ) ? array_values( $get_gallery_image_ids ) : array();

	$image_size         = tppif_image_size();
	$image_srcset_sizes = tppif_image_srcset_sizes();
	$placeholder_img    = wc_placeholder_img_src( $image_size );

	// Check the value of 'images_from_gallery_only' option
	$images_from_gallery_only  = get_option( 'images_from_gallery_only' );
	$add_product_link_to_image = get_option( 'add_product_link_to_image' );

	// If 'images_from_gallery_only' is checked, we take the first two images from the gallery
	if ( $images_from_gallery_only == 1 && count( $get_gallery_image_ids ) >= 2 ) {
		$get_image_id        = $get_gallery_image_ids[0];
		$get_second_image_id = $get_gallery_image_ids[1];
	} else {
		$get_image_id        = $product->get_image_id();
		$get_second_image_id = isset( $get_gallery_image_ids[0] ) ? $get_gallery_image_ids[0] : null;
	}

	$image_url_top    = $get_image_id ? wp_get_attachment_image_url( $get_image_id, $image_size ) : false;
	$image_url_bottom = $get_second_image_id ? wp_get_attachment_image_url( $get_second_image_id, $image_size ) : false;

	if ( $image_url_top ) {

		$image_top_alt = get_post_meta( $get_image_id, '_wp_attachment_image_alt', true );
		if ( ! $image_top_alt ) {
			$image_top_alt = $product->get_name();
		}

		if ( $image_url_bottom ) {

			$image_bottom_alt = get_post_meta( $get_second_image_id, '_wp_attachment_image_alt', true );
			if ( ! $image_bottom_alt ) {
				$image_bottom_alt = $image_top_alt;
			}

			$output  = '<div class="tp-image-wrapper">';
			$output .= '<img class="tp-image" src="' . esc_url( $image_url_top ) . '"' . tppif_srcset_attributes( $get_image_id, $image_size, $image_srcset_sizes ) . ' alt="' . esc_attr( $image_top_alt ) . '">';
			$output .= '<img class="tp-image-hover" src="' . esc_url( $image_url_bottom ) . '"' . tppif_srcset_attributes( $get_second_image_id, $image_size, $image_srcset_sizes ) . ' alt="' . esc_attr( $image_bottom_alt ) . '">';
			$output .= '</div>';

		} else {

			$output  = '<div class="tp-image-wrapper">';
			$output .= '<img class="image" src="' . esc_url( $image_url_top ) . '"' . tppif_srcset_attributes( $get_image_id, $image_size, $image_srcset_sizes ) . ' alt="' . esc_attr( $image_top_alt ) . '">';
			$output .= '</div>';
		}
	} else {

		$output  = '<div class="tp-image-wrapper">';
		$output .= '<img class="image" src="' . esc_url( $placeholder_img ) . '" alt="' . esc_attr( $product->get_name() ) . '" />';
		$output .= '</div>';
	}

	if ( $add_product_link_to_image ) {
		$output = '<a href="' . esc_url( $product->get_permalink() ) . '">' . $output . '</a>';
	}

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every dynamic value is escaped while $output is built.
}


function tppif_image_size() {
	$default_size = 'woocommerce_thumbnail'; // 'thumbnail', 'medium', 'medium_large', 'large'

	/**
	* Filters the list of fliper image size.
	*
	* @since 1.0.6
	*
	* @param string[] $default_size An image size name. Defaults
	* are 'woocommerce_thumbnail','thumbnail', 'medium', 'medium_large', 'large'.
	*/
	return apply_filters( 'tppif_image_size', $default_size );
}

function tppif_image_srcset_sizes() {
	$default_sizes = '(max-width: 360px) 100vw, 360px';

	/**
	* Filters the list of fliper image size.
	*
	* @since 1.0.6
	*
	* @param string[] $default_size An image size name. Defaults
	* are (max-width: 360px) 100vw, 360px.
	*/
	return apply_filters( 'tppif_image_srcset_sizes', $default_sizes );
}

function tppif_script() {
	if ( get_option( 'remove_duplicate_images' ) ) {
	?>
	<script>
		document.addEventListener("DOMContentLoaded", function(){
			var products = document.querySelectorAll("li.product");

			products.forEach(function(product) {
				var images = product.querySelectorAll("img");

				images.forEach(function(image) {
					if (!image.closest('.tp-image-wrapper')) {
						image.style.display = "none";  // To hide the image
						// image.remove(); // To remove the image completely
					}
				});
			});
		});
	</script>
	<?php
	}
}

//------------------------------------------------
function tp_product_image_flipper_add_plugin_page_settings_link( $links ) {

	$links[] = '<a href="' .
		esc_url( admin_url( 'options-general.php?page=tp-product-image-flipper' ) ) .
		'">' . esc_html__( 'Settings', 'tp-product-image-flipper-for-woocommerce' ) . '</a>';

	$links[] = '<a class="tpc_get_pro" href="' . esc_url( TP_PRODUCT_IMAGE_FLIPPER_PRO_URL ) . '" target="_blank">' . esc_html__( 'Go Premium!', 'tp-product-image-flipper-for-woocommerce' ) . '</a>';

	return $links;
}
