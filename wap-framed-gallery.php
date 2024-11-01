<?php

/*
Plugin Name: 	WAP Gallery
Plugin URI:	 	https://plugins.wearepanda.co.uk/framed-gallery/
Description: 	Displays a custom gallery with the post's attachments
Version: 		1.0.0
Author: 		We Are Panda / Paul Riley
Author URI:  	https://wearepanda.co.uk/
License:     	GPL2
License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 	wapfg
*/



/*
 * Register plugin activation hook for v3.5+
 *
 *
 */
register_activation_hook(__FILE__, 'wap_gallery_install');

function wap_gallery_install() {

    global $wp_version;

    if (version_compare($wp_version, '4.0', '<')){

        wp_die('This plugin requires WordPress version 3.5 or higher.');

    }//end version check

}



/*
 * STYLES & SCRIPTS
 *
 * This function enqueues the general styles needed for the global content built-in styles and format
 *
 */
function wap_gallery_styles() {

    wp_enqueue_style('wap_gallery_style', plugins_url( 'css/wap-framed-gallery.css', __FILE__ ), null, null, 'all');

    wp_enqueue_script( 'wap_gallery_js', plugin_dir_url( __FILE__ ) . 'js/wap-framed-gallery.js', array(), '', true );

}

//add the styles to frontend
add_action( 'wp_enqueue_scripts', 'wap_gallery_styles' );


/*
 * LIGHTBOX CONTAINER
 *
 * Adds lightbox container and img placeholder for the lightbox image to be displayed
 *
 */
function lightbox_container() {
	
	echo '<div id="wap-lightbox"><img src="" /></div>';
	
}
add_action('wp_footer', 'lightbox_container');
 
 
/*
 * SHORTCODE
 *
 * Create shortcode to list all events
 */
add_shortcode( 'wap_gallery', 'wap_gallery_insert_gallery' );

function wap_gallery_insert_gallery( $atts ) {

    // begin output buffering
    ob_start();

    // set attributes to null or bring in from shortcode options
    $wap_gallery_Atts = shortcode_atts( array(

        'style' =>  'Wood_Butternut',
        'cols'  =>  3,
		'limit'	=> 3

    ), $atts );

	switch($wap_gallery_Atts["cols"]) {
		case 1:
			$cols_width = "xs100";
			break;
		case 2:
			$cols_width = "xs100 sm50";
			break;
		case 3:
			$cols_width = "xs100 sm33";
			break;
		case 4:
			$cols_width = "xs100 sm25";
			break;
	}

    //initialise html container variable
    $html = '';

    //get media
    $media = get_attached_media( 'image' );

    //var_dump($media);

	$i = 1;

    foreach ($media as $media_id => $media_data) {

		$media_url = wp_get_attachment_image_src($media_id, "medium");
		$media_lightbox_url = wp_get_attachment_image_src($media_id, "large");
		
		/*$break = ($i % $wap_gallery_Atts["cols"] > 0 ? false : true);

		if ($break) {
			$html .= '<div style="display:block; width:100%; height:1px;"></div>';
		}*/

		$html .= '<div class="wap-gallery-item ' . $cols_width . '" data-large="' . $media_lightbox_url[0] . '">';
		$html .= '<div class="wap-gallery-image-container ' . $wap_gallery_Atts["style"] . '"><div class="wap-gallery-image"><img style="width:100%; max-width:100%;" src="' . $media_url[0] . '" /></div><div class="shadow"></div></div>';
		$html .= '</div>';

		if( $i == $wap_gallery_Atts["limit"] ) {
			break;
		}

		$i++;

    }
	
	//close gallery container
	$html .= '</div>';
	
    echo $html;

    // end output buffering
    $output = ob_get_contents();

    // grab the buffered contents and empty the buffer
    ob_end_clean();

    //return buffered content to page
    return $output;

}

?>
