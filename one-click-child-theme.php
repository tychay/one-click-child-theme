<?php /*
**************************************************************************

Plugin Name:  One-Click Child Theme
Plugin URI:   http://terrychay.com/wordpress-plugins/one-click-child-theme
Version:      1.2
Description:  Allows you to easily child theme any theme from the theme
			  options on the wp-admin instead of going into shell or
  			  using FTP.
Author:       tychay
Author URI:   http://terrychay.com/

**************************************************************************/
/*  Copyright 2011  terry chay  (email : tychay@automattic.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class OneClickChildTheme {
	private $plugin_dir = '';
	function __construct() {
		$this->plugin_dir = dirname(__FILE__);
		// it has to be buried liek this or you get an error "You do not have sufficient permissions to access this page"
		add_filter( 'admin_menu', array( $this, 'createMenu' ) );
	}
	function createMenu() {
		add_theme_page( 'Make a Child Theme', 'Child Theme', 'install_themes', 'one-click-child-theme-page', array( $this, 'showThemePage' ) );
	}

	/**
	 * Show the theme page which has a form allowing you to child theme currently selected theme.
	 */
	function showThemePage() {

		if ( !empty($_POST['theme_name']) ) {
			$theme_name = $_POST['theme_name'];
			$description = ( empty($_POST['description']) )
				? ''
				: $_POST['description'];
			$author_name = ( empty($_POST['author_name']) )
				? ''
				: $_POST['author_name'];
			$result = $this->_make_child_theme( $theme_name, $description, $author_name );
			if ( is_wp_error( $result ) ) {
				$error = $result->get_error_message();
				// $error is rendered below
			} else {
				var_dump($result);
				var_dump(switch_theme( $result['parent_template'], $result['new_theme'] ));
				// TODO: put a redirect in here somehow?
				//wp_redirect( admin_url('themes.php') ); //buffer issue :-(
				printf( __('<a href="%s">Theme switched!</a>', 'one-click-child-theme'), admin_url( 'themes.php' ) );
				exit;
			}
		}

		if ( !isset($theme_name) ) { $theme_name = ''; }
		if ( !isset($description) ) { $description = ''; }
		if ( empty($author) ) {
			global $current_user;
			get_currentuserinfo();
			$author = $current_user->display_name;
		}
		require $this->plugin_dir.'/panel.php';
	}

	/**
	 * Does the work to make a child theme based on the current theme.
	 *
	 * This currently supports the following files:
	 *
	 * 1. style.css: Follows the rules outlined in {@link http://codex.wordpress.org/Child_Themes the Codex}
	 * 2. rtl.css: right to left language support, if not avaialble in parent, it
	 *    uses TwentyEleven's rtl
	 * 3. screenshot.png: screenshot if available in the parent
	 *
	 * @author terry chay <tychay@autoamttic.com>
	 * @author Chris Robinson <http://contempographicdesign.com/> (for screenshot support).
	 * @return array|WP_Error If successful, it returns a hash contianing
	 * - new_theme: (directory) name of new theme
	 * - parent_template: (directory) name of parent template
	 * - parent_theme: (directory) name of parent theme
	 * - new_theme_path: full path to the directory cotnaining the new theme
	 * - new_theme_title: the name of the new theme
	 */
	private function _make_child_theme( $new_theme_title, $new_theme_description, $new_theme_author ) {
		$parent_theme_title = get_current_theme();
		$parent_theme_template = get_template(); //Doesn't play nice with the grandkids
		$parent_theme_name = get_stylesheet();

		// Turn a theme name into a directory name
		$new_theme_name = sanitize_title( $new_theme_title );
		$theme_root = get_theme_root();

		// Validate theme name
		$new_theme_path = $theme_root.'/'.$new_theme_name;
		if ( file_exists( $new_theme_path ) ) {
			return new WP_Error( 'exists', __( 'Theme directory already exists' ) );
		}

		mkdir( $new_theme_path );

		// Make style.css
		ob_start();
		require $this->plugin_dir.'/child-theme-css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path.'/style.css', $css );

		// RTL support
		$rtl_theme = ( file_exists( $theme_root.'/'.$parent_theme_name.'/rtl.css' ) )
			? $parent_theme_name
			: 'twentyeleven'; //use the latest default theme rtl file
		ob_start();
		require $this->plugin_dir.'/rtl-css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path.'/rtl.css', $css );

		// Copy screenshot
		$parent_theme_screenshot = $theme_root.'/'.$parent_theme_name.'/screenshot.png';
		if ( file_exists( $parent_theme_screenshot ) ) {
			copy( $parent_theme_screenshot, $new_theme_path.'/screenshot.png' );
		} elseif (file_exists( $parent_theme_screenshot = $theme_root.'/'.$parent_theme_template.'/screenshot.png' ) ) {
			copy( $parent_theme_screenshot, $new_theme_path.'/screenshot.png' );
		}

		// Make child theme an allowed theme (network enable theme)
		$allowed_themes = get_site_option( 'allowedthemes' );
		$allowed_themes[ $new_theme_name ] = true;
		update_site_option( 'allowedthemes', $allowed_themes );

		return array(
			'parent_template'    => $parent_theme_template,
			'parent_theme'       => $parent_theme_name,
			'new_theme'          => $new_theme_name,
			'new_theme_path'     => $new_theme_path,
			'new_theme_title'	 => $new_theme_title,
		);

	}

}

new OneClickChildTheme();
// Start this plugin
//add_action( 'admin_init', array('OneClickChildTheme','init'), 12 );
?>
