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
 * Version:           2.0.2
 * Requires at least: 4.2
 * Requires PHP:      5.6
 * Author:            TP Plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tp-product-image-flipper-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to:      8.4.0
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
define( 'TP_PRODUCT_IMAGE_FLIPPER_FOR_WOOCOMMERCE_VERSION', '2.0.2' );
define( 'TP_PRODUCT_IMAGE_FLIPPER_PRO_URL', 'https://www.tplugins.com/product/tp-woocommerce-category-product-slider/' );
define( 'TP_PRODUCT_IMAGE_FLIPPER_NAME', 'TP Product Image Flipper Settings' );

/**
 * Check if WooCommerce is active
 */
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );

    // include the settings page
    include(plugin_dir_path(__FILE__) . 'settings.php');

    //define( 'TP_PRODUCT_IMAGE_FLIPPER_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
    $plugin_name = 'tp-product-image-flipper-for-woocommerce';

    add_action( 'wp_enqueue_scripts', 'tp_product_image_flipper_front_scripts' );
    function tp_product_image_flipper_front_scripts() {
        //wp_enqueue_style( $plugin_name, plugin_dir_url( __FILE__ ) . 'css/tp-product-image-flipper-for-woocommerce.css', array(), TP_PRODUCT_IMAGE_FLIPPER_FOR_WOOCOMMERCE_VERSION, 'all' );
        wp_enqueue_style( 'tp-product-image-flipper-for-woocommerce', plugins_url( '/css/tp-product-image-flipper-for-woocommerce.css' , __FILE__ ) );
    }
    
    add_action( 'init' , 'tp_remove_action' , 15 );
    function tp_remove_action() {
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

    }

    // remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    add_action( 'woocommerce_before_shop_loop_item_title', 'tp_create_flipper_images', 10);

    function tp_create_flipper_images() {
        global $post, $product;
    
        $get_gallery_image_ids = $product->get_gallery_image_ids();
        $image_size = tppif_image_size();
        $image_srcset_sizes = tppif_image_srcset_sizes();
    
        // Check the value of 'images_from_gallery_only' option
        $images_from_gallery_only  = get_option( 'images_from_gallery_only' );
        $add_product_link_to_image = get_option( 'add_product_link_to_image' );
    
        // If 'images_from_gallery_only' is checked, we take the first two images from the gallery
        if ($images_from_gallery_only == 1 && count($get_gallery_image_ids) >= 2) {
            $get_image_id  = $get_gallery_image_ids[0];
            $get_second_image_id = $get_gallery_image_ids[1];
        } else {
            $get_image_id = $product->get_image_id();
            $get_second_image_id = isset($get_gallery_image_ids[0]) ? $get_gallery_image_ids[0] : null;
        }
    
        // Rest of your original function starts here...
        $image_url_top = wp_get_attachment_image_url( $get_image_id, $image_size );
        $image_url_top_srcset = wp_get_attachment_image_srcset( $get_image_id, $image_size );
    
        $placeholder_img = wc_placeholder_img_src($image_size);
    
        if($get_image_id){
            $image_top_alt = get_post_meta($get_image_id, '_wp_attachment_image_alt', TRUE);
            if(!$image_top_alt){
                $image_top_alt = $product->get_name();
            }
    
            if($get_second_image_id){
                $image_bottom_alt = get_post_meta($get_second_image_id, '_wp_attachment_image_alt', TRUE);
                if(!$image_bottom_alt){
                    $image_bottom_alt = $image_top_alt;
                }
    
                $output = '<div class="tp-image-wrapper">';
                    $image_url_bottom = wp_get_attachment_image_url( $get_second_image_id, $image_size );
                    $image_url_bottom_srcset = wp_get_attachment_image_srcset( $get_second_image_id, $image_size );
        
                    $output .= '<img class="tp-image" src="'.esc_url($image_url_top).'" srcset="'.esc_attr( $image_url_top_srcset ).'" sizes="'.$image_srcset_sizes.'" alt="'.$image_top_alt.'">';
                    $output .= '<img class="tp-image-hover" src="'.esc_url($image_url_bottom).'" srcset="'.esc_attr( $image_url_bottom_srcset ).'" sizes="'.$image_srcset_sizes.'" alt="'.$image_bottom_alt.'">';
                            
                $output .= '</div>';
                
            } else {
                $output = '<div class="tp-image-wrapper">';
                    $output .= '<img class="image" src="'.esc_url($image_url_top).'" srcset="'.esc_attr( $image_url_top_srcset ).'" sizes="'.$image_srcset_sizes.'" alt="'.$image_top_alt.'">';
                $output .= '</div>';
            }
        } else {
            $output = '<div class="tp-image-wrapper">';
                $output .= '<img class="image" src="'.$placeholder_img.'" />';
            $output .= '</div>';
        }
    
        if($add_product_link_to_image) {
            echo '<a href="'.esc_url(get_permalink($post->ID)).'">'.$output.'</a>';
        }
        else {
            echo $output;
        }
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

    add_action( 'wp_footer', 'tppif_script' );
    function tppif_script() {
        if(get_option( 'remove_duplicate_images' )) {
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
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tp_product_image_flipper_add_plugin_page_settings_link' );
    function tp_product_image_flipper_add_plugin_page_settings_link( $links ) {
        
        $links[] = '<a href="' .
            admin_url( 'options-general.php?page=tp-product-image-flipper' ) .
            '">' . __('Settings') . '</a>';
        
        $links[] = '<a class="tpc_get_pro" href="'.TP_PRODUCT_IMAGE_FLIPPER_PRO_URL.'" target="_blank">' . __('Go Premium!') . '</a>';

        return $links;
    }

    //------------------------------------------------
    // Compatible with HPOS
    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );

}