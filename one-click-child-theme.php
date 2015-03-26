<?php
/*
**************************************************************************

Plugin Name:  One-Click Child Theme
Plugin URI:   http://terrychay.com/wordpress-plugins/one-click-child-theme
Version:      1.5.1
Description:  Easily child theme any theme from wp-admin wp-admin without going into shell or using FTP.
Author:       tychay
Author URI:   http://terrychay.com/
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  one-click-child-theme
//Domain Path:

**************************************************************************/
/*  Copyright 2011-2015  terry chay  (email : tychay@php.net)

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
/*
 * Performance: One-Click Child Theme is only active in admin page
 */
if (!is_admin) { return; }
/**
 * The namespace for the One-Click-Child-Theme Plugin
 */
class OneClickChildTheme {
	private $plugin_dir = '';
	function __construct() {
		$this->plugin_dir = dirname(__FILE__);
		// it has to be buried like this or you get an error:
		//  "You do not have sufficient permissions to access this page"
		add_action( 'admin_menu', array( $this, 'createAdminMenu' ) );
	}
	/**
	 * Adds an admin menu for One Click Child Theme in Appearances
	 */
	function createAdminMenu() {
		add_theme_page(
			__('Make a Child Theme', 'one-click-child-theme'),
			__('Child Theme', 'one-click-child-theme'),
			'install_themes',
			'one-click-child-theme-menu',
			array( $this, 'showThemePage' ) );
	}

	/**
	 * Show the theme page which has a form allowing you to child theme
	 * currently selected theme.
	 * @todo  move the post handling into the admin_action_* hook in admin.php
	 */
	function showThemePage()
	{
		if ( !empty($_POST['cmd'])) {
			// Handle Make Child Theme form
			if ( strcmp($_POST['cmd'],'create_child_theme') == 0 ) {
				$this->_handle_create_child_form();
				return;
			}
			// Handle one-click repair form
			if ( strcmp($_POST['cmd'],'repair_child_theme') == 0 ) {
				$this->_handle_repair_child_theme();
				$this->_show_child_already_form( $this->_child_theme_needs_repair() );
				return;
			}
			// Handle child filing form
			if ( strcmp($_POST['cmd'],'copy_template_file') == 0 ) {
				$this->_handle_copy_template_file();
				$this->_show_child_already_form( $this->_child_theme_needs_repair() );
				return;
			}
		}

		if ( is_child_theme() ) {
			$this->_show_child_already_form( $this->_child_theme_needs_repair() );
			return;
		}

		// Default behavior: not a child, interested in child themeing
		if ( !isset($theme_name) ) { $theme_name = ''; }
		if ( !isset($description) ) { $description = ''; }
		if ( empty($author) ) {
			global $current_user;
			get_currentuserinfo();
			$author = $current_user->display_name;
		}
		require $this->plugin_dir.'/templates/create_child_form.php';
	}

	/**
	 * Show the "is child already" template.
	 * @param  boolean $child_needs_repair whether or not child theme needs repair
	 */
	private function _show_child_already_form($child_needs_repair) {
		$current_theme = wp_get_theme();
		$filename='test.php';

		// Search for template files.
		// Note: since there can be files like {mimetype}.php, we must assume
		// that any root level .php files in the template directory are
		// templates.
		$template_files = glob ( get_template_directory().'/*.php' );
		foreach ( $template_files as $index=>$file ) {
			$template_files[$index] = basename( $file );
		}
		// Filter out any files in child already created
		$child_theme_dir = get_stylesheet_directory();
		foreach ( $template_files as $index=>$filename ) {
			if ( file_exists($child_theme_dir.'/'.$filename) ) {
				unset($template_files[$index]);
			}
		}
		require $this->plugin_dir.'/templates/is_child_already.php';
		//TODO: handle grandchildren
	}

	/**
	 * Handle the copy_template form.
	 */
	private function _handle_copy_template_file() {
		$filename = ( empty($_POST['filename']) )
			? ''
			: $_POST['filename'];
		if ( !$filename ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				__('No template file specified.', 'one-click-child-theme'),
				$result->get_error_message(),
				'error'
			);
			return;
		}
		$child_theme_dir = get_stylesheet_directory();
		$template_dir = get_template_directory();
				var_dump('bar');
		if ( !file_exists($template_dir.'/'.$filename) ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf( __('Template file %s does not exist in parent theme!', 'one-click-child-theme'),
					$filename
					),
				$result->get_error_message(),
				'error'
			);
			return;
		}
		if ( file_exists($child_theme_dir.'/'.$filename) ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf( __('Template file %s already exists in child theme!', 'one-click-child-theme'),
					$filename
					),
				$result->get_error_message(),
				'error'
			);
			return;
		}
		if ( !copy( $template_dir.'/'.$filename, $child_theme_dir.'/'.$filename ) ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf( __('Failed to duplicate file %s!', 'one-click-child-theme'),
					$filename
					),
				$result->get_error_message(),
				'error'
			);
		}
		add_settings_error(
			'',
			'one-click-child-theme',
			sprintf(__('<a href="%s">File %s created!</a>', 'one-click-child-theme'),
				admin_url( sprintf( 'theme-editor.php?file=%s&theme=%s',
					urlencode($filename),
					urlencode(get_stylesheet())
					)),
				$filename
				),
			'updated'
		);
		return;
	}

	/**
	 * Handle the create_child_theme form.
	 */
	private function _handle_create_child_form() {
		$theme_name = $_POST['theme_name'];
		$description = ( empty($_POST['description']) )
			? ''
			: $_POST['description'];
		$author_name = ( empty($_POST['author_name']) )
			? ''
			: $_POST['author_name'];
		$result = $this->_make_child_theme( $theme_name, $description, $author_name );
		if ( is_wp_error( $result ) ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				$result->get_error_message(),
				'error'
			);
			require $this->plugin_dir.'/templates/create_child_form.php';
		} else {
			switch_theme( $result['parent_template'], $result['new_theme'] );
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf(__('<a href="%s">Theme switched!</a>', 'one-click-child-theme'), admin_url( 'themes.php' ) ),
				'updated'
			);
			$this->_show_child_already_form(false);
			// TODO: put a redirect in here somehow?
			//wp_redirect( admin_url('themes.php') ); //buffer issue :-(
			//exit;
		}
	}

	/**
	 * Handle the repair_child_theme form.
	 */
	private function _handle_repair_child_theme()
	{
		$child_theme_dir = get_stylesheet_directory();
		$functions_file = $child_theme_dir.'/functions.php';
		$style_file = $child_theme_dir.'/style.css';

		// create functions.php if it doesn't exist yet
		if ( !file_exists($functions_file) ) {
			if ( !touch($functions_file) ) {
				add_settings_error(
					'',
					'one-click-child-theme',
					sprintf( __('Failed to create file: %s', 'one-click-child-theme'), $functions_file ),
					'error'
				);
				// fixing is hopeless if we can't create the file
				return;
			}
		}

		// read in style.css
		$style_text = file_get_contents( $style_file );
		// prune out old rules
		$style_text = preg_replace(
			'!@import\s+url\(\s?["\']\.\./.*/style.css["\']\s?\);!ims',
			'',
			$style_text
		);
		$style_text = preg_replace(
			'!@import\s+url\(\s?["\']'.get_template_directory_uri().'/style.css["\']\s?\);!ims',
			'',
			$style_text
		);
		if ( file_put_contents( $style_file, $style_text) === false )	 {
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf( __('Failed edit to file: %s', 'one-click-child-theme'), $style_file ),
				'error'
			);
			return;
		}

		// modify functions.php to prepend new rules
		$functions_text = file_get_contents( $this->plugin_dir.'/templates/functions.php' );
		// ^^^ above file has no carriage return and ending comment so it should
		// "smash" the starting '<?php' string in any existing functions.php.
		$functions_text .= file_get_contents( $functions_file );
		if ( file_put_contents( $functions_file, $functions_text ) === false ) {
			add_settings_error(
				'',
				'one-click-child-theme',
				sprintf( __('Failed edit to file: %s', 'one-click-child-theme'), $functions_file ),
				'error'
			);
		}
		add_settings_error(
			'',
			'one-click-child-theme',
			__('Repaired child theme.', 'one-click-child-theme'),
			'updated'
		);
		return;
	}

	/**
	 * Detect if child theme needs repair.
	 *
	 * A child theme needs repair if it is missing a functions.php or the
	 * style.css still has a rule that points to the parent.
	 */
	private function _child_theme_needs_repair()
	{
		$child_theme_dir = get_stylesheet_directory();
		if ( !file_exists($child_theme_dir.'/functions.php') ) {
			return true;
		}
		$style_text = file_get_contents( $child_theme_dir.'/style.css' );
		// look for relative match (dificult to extract parent theme directory
		// so I'll assume any in this path is parent theme)
		if ( preg_match(
			'!@import\s+url\(\s?["\']\.\./.*/style.css["\']\s?\);!ims',
			$style_text
			) ) {
			return true;
		}
		// look for absolute match
		if ( preg_match(
			'!@import\s+url\(\s?["\']'.get_template_directory_uri().'/style.css["\']\s?\);!ims',
			$style_text
			) ) {
			return true;
		}
		return false;
	}

	/**
	 * Does the work to make a child theme based on the current theme.
	 *
	 * This currently supports the following files:
	 *
	 * 1. style.css: Follows the rules outlined in {@link http://codex.wordpress.org/Child_Themes the Codex}
	 * 2. functions.php: Followed the updated rules outlined in the Codex. Note
	 * 	  that since WordPress ?.? functions.php hierarchy is automatically
	 * 	  included.
	 * 3. rtl.css: right to left language support, if not avaialble in parent, it
	 *    uses TwentyFifteen's rtl
	 * 4. screenshot.png: screenshot if available in the parent
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
		require $this->plugin_dir.'/templates/child-theme-css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path.'/style.css', $css );

		// Copy functions.php
		copy( $this->plugin_dir.'/templates/functions.php', $new_theme_path.'/functions.php' );

		// RTL support
		$rtl_theme = ( file_exists( $theme_root.'/'.$parent_theme_name.'/rtl.css' ) )
			? $parent_theme_name
			: 'twentyfifteen'; //use the latest default theme rtl file
		ob_start();
		require $this->plugin_dir.'/templates/rtl-css.php';
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
