<?php /*
**************************************************************************

Plugin Name:  One-Click Child Theme
Plugin URI:   http://terrychay.com/wordpress-plugins/one-click-child-theme
Version:      1.0.1
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
		$parent_theme_name = get_current_theme();
		$parent_template = get_template(); //Doesn't play nice with the grandkids
		$parent_theme = get_stylesheet();

		if ( !empty($_POST['theme_name']) ) {
			$theme_name = $_POST['theme_name'];
			$description = ( empty($_POST['description']) )
				? ''
				: $_POST['description'];
			$author_name = ( empty($_POST['author_name']) )
				? ''
				: $_POST['author_name'];
			// Turn a theme name into a directory name
			$theme_dir = sanitize_title( $theme_name );
			$theme_root = get_theme_root();
			// Validate theme name
			$theme_path = $theme_root.'/'.$theme_dir;
			if ( file_exists( $theme_path ) ) {
				$error = 'Theme directory already exists!';
			} else {
				mkdir( $theme_path );
				ob_start();
				require $this->plugin_dir.'/child-theme-css.php';
				$css = ob_get_clean();
				file_put_contents( $theme_path.'/style.css', $css );

				// RTL support
				$rtl_theme = ( file_exists( $theme_root.'/'.$parent_theme.'/rtl.css' ) )
					? $parent_theme
					: 'twentyeleven'; //use the latest default theme rtl file
				ob_start();
				require $this->plugin_dir.'/rtl-css.php';
				$css = ob_get_clean();
				file_put_contents( $theme_path.'/rtl.css', $css );

				switch_theme( $parent_template, $theme_dir );
				printf( __('<a href="%s">Theme switched!</a>', 'one-click-child-theme'), admin_url( 'themes.php' ) );
				//wp_redirect( admin_url('themes.php') ); //buffer issue :-(
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

}
function hereiam() {
		echo 'hello';
}

new OneClickChildTheme();
// Start this plugin
//add_action( 'admin_init', array('OneClickChildTheme','init'), 12 );
?>
