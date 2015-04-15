<?php
/*
**************************************************************************

Plugin Name:  One-Click Child Theme
Plugin URI:   http://terrychay.com/wordpress-plugins/one-click-child-theme
Version:      1.6
Description:  Easily child theme any theme from wp-admin wp-admin without going into shell or using FTP.
Author:       tychay
Author URI:   http://terrychay.com/
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  one-click-child-theme
Domain Path: /languages

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
if (!is_admin()) { return; }
/**
* Load textdomain
 */
function _load_textdomain() {
	load_plugin_textdomain( 'one-click-child-theme', false, basename(dirname(__FILE__)) . '/languages' );
}
add_action( 'init', '_load_textdomain' );
/**
 * The namespace for the One-Click Child Theme Plugin
 */
class OneClickChildTheme {
	/**
	 * @const string Used for id generation and language text domain.
	 */
	const _SLUG = 'one-click-child-theme';
	/**
	 * Used for loading in files
	 * @var string
	 */
	private $_pluginDir = '';
	/**
	 * This plugin's theme page
	 */
	private $_themePageUrl = '';	
	/**
	 * Theme page name (menu slug)
	 * @var string
	 */
	private $_menuId = '';
	/**
	 * action for Create Child form
	 */
	private $_createChildFormId = '';
	/**
	 * action for Repair Child form
	 */
	private $_repairChildFormId = '';
	/**
	 * action for Copy Template form
	 */
	private $_copyTemplateFormId = '';
	/**
	 * action for screenshot generation
	 */
	private $_mshotSiteFormId = '';

	public function __construct() {
		$this->_pluginDir          = dirname(__FILE__);
		$this->_menuId             = self::_SLUG . '-page';
		$this->_themePageUrl       = admin_url('themes.php?page='.$this->_menuId);
		$this->_createChildFormId  = self::_SLUG.'-create-child';
		$this->_repairChildFormId  = self::_SLUG.'-repair-child';
		$this->_copyTemplateFormId = self::_SLUG.'-copy-template';
		$this->_mshotSiteFormId    = self::_SLUG.'-mshot-site';

		// it has to be buried like this or you get an error:
		//  "You do not have sufficient permissions to access this page"
		add_action( 'admin_menu', array($this,'createAdminMenu') );

		// form handling code	
		add_action( 'admin_post_'.$this->_createChildFormId, array($this,'processCreateForm') );
		add_action( 'admin_post_'.$this->_repairChildFormId, array($this,'processRepairChildForm') );
		add_action( 'admin_post_'.$this->_copyTemplateFormId, array($this,'processCopyTemplateForm') );
		add_action( 'admin_post_'.$this->_mshotSiteFormId, array($this,'processMShotSiteForm') );
		// TODO: I could also use the $pagenow global, but is it still there?
		if ( basename($_SERVER['PHP_SELF']) == 'themes.php' && !empty($_REQUEST['occt_error']) ) {
			add_action( 'admin_notices', array($this,'showErrorNotice'));
		}
	}
	/**
	 * Handle error and update notices for this theme
	 *
	 * There are now four types of notices: success (green), warning (orange), error (red),
	 * and info (blue).
	 * 
	 * Put here because there is a redirect between all forms and error notifications and
	 * add_settings_error() only covers options API errors.
	 */
	public function showErrorNotice()
	{
		switch ($_GET['occt_error']) {
			case 'child_created': //SUCCESS: child theme created
				$type = 'updated'; //fade?
				$msg = sprintf(
					__('Theme switched! <a href="%s">Click here to edit the child stylesheet</a>.', self::_SLUG),
					add_query_arg(
						urlencode_deep(array(
							'file'  => 'style.css',
							'theme' => get_stylesheet(),
						)),
						admin_url('theme-editor.php')
					)
				);
				break;
			case 'create_failed': //ERROR: create file failed (probably due to permissions)
				$type = 'error';
				$msg = sprintf(
					__('Failed to create file: %s', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'edit_failed': //ERROR: edit file failed (probably do to permissions)
				$type = 'error';
				$msg = sprintf(
					__('Failed to edit file: %s', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'repair_success': //SUCCESS: repaired child theme
				$type = 'updated fade';
				$msg = __('Repaired child theme.', self::_SLUG);
				break;
			case 'no_template': //ERROR: template file not specified
				$type = 'error';
				$msg = __('No template file specified.', self::_SLUG);
			case 'missing_template': //ERROR: parent theme doesn't have template
				$type = 'error';
				$msg = sprintf(
					__('Template file %s does not exist in parent theme!', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'already_template': //ERROR: child theme already has template
				$type = 'error';
				$msg = sprintf(
					__('Template file %s already exists in child theme!', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'copy_failed': //ERROR: couldn't duplicate file for some reason
				$type = 'error';
				$msg = sprintf(
					__('Failed to duplicate file %s!', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'copy_success': //SUCCESS: template file created
				$type = 'updated'; //fade?
				$msg = sprintf(
					__('<a href="%s">File %s created!</a>', self::_SLUG),
					add_query_arg(
						urlencode_deep(array(
							'file'  => $_GET['filename'],
							'theme' => get_stylesheet(),
						)),
						admin_url('theme-editor.php')
					),
					esc_html($_GET['filename'])
				);
				break;
			case 'delete_failed': //ERROR: couldn't delete file for some reason
				$type = 'error';
				$msg = sprintf(
					__('Failed to delete file %s!', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'mshot_404': //ERROR: couldn't find mshot
				$type = 'error';
				$msg = sprintf(
					__('404 File not found at %s!', self::_SLUG),
					esc_html($_GET['url'])
				);
				break;
			case 'mshot_mime_wrong': //ERROR: couldn't find mshot
				$type = 'error';
				$msg = sprintf(
					__('Unrecognized mimetype at %s!', self::_SLUG),
					esc_html($_GET['url'])
				);
				break;
			case 'mshot_nocreate': //ERROR: couldn't find mshot
				$type = 'error';
				$msg = sprintf(
					__('Failed to create file %s!', self::_SLUG),
					esc_html($_GET['filename'])
				);
				break;
			case 'mshot_success': //SUCCESS: screenshot generated
				$type = 'updated fade'; //fade?
				$msg = __('Successfully changed screenshot.', self::_SLUG);
				break;
			default: //ERROR: it is a generic error message
				$type = 'error';
				$msg = esc_html($_GET['occt_error']);
		}
		printf(
			'<div class="%s"><p>%s</p></div>',
			$type,
			$msg
		);
	}
	/**
	 * Adds an admin menu for One Click Child Theme in Appearances
	 */
	public function createAdminMenu() {
		add_theme_page(
			__('Make a Child Theme', self::_SLUG), //page title
			__('Child Theme', self::_SLUG), //menu title
			'install_themes', //capability needed to view
			$this->_menuId, //menu slug (and page query url)
			array( $this, 'showThemePage' ) //callback function
			);
	}
	//
	// SHOW THEME PAGE
	// 
	/**
	 * Show the theme page which has a form allowing you to child theme
	 * currently selected theme.
	 *
	 */
	public function showThemePage()
	{
		// Form is processed in the admin_post_* hooks
		
		// Handle case where current theme is already a child
		if ( is_child_theme() ) {
			$this->_showFormAlreadyChild( $this->_child_theme_needs_repair() );
			return;
		}

		// Default behavior: We are not a child theme, but interested in creating one.
		// Grab default values from a form fail
		$theme_name = ( !empty($_GET['theme_name']) ) ? $_GET['theme_name'] : '';
		$description = ( !empty($_GET['description']) ) ? $_GET['description'] : '';
		if ( !empty($_GET['author_name']) ) {
			$author = $_GET['author_name'];
		} else {
			global $current_user;
			get_currentuserinfo();
			$author = $current_user->display_name;
		}
		// render default behaivor
		require $this->_pluginDir.'/templates/create_child_form.php';
	}

	/**
	 * Show the "is child already" template.
	 * @param  boolean $child_needs_repair whether or not child theme needs repair
	 * @todo  handle grandchildren
	 */
	private function _showFormAlreadyChild($child_needs_repair) {
		// set template parameters
		$current_theme = wp_get_theme();
		$child_theme_screenshot_url = ( $screenshot_filename = $this->_scanForScreenshot( get_stylesheet_directory() ) )
			? get_stylesheet_directory_uri().'/'.$screenshot_filename
			: '';
		$mshot_url = $this->_mshotUrl();
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
		require $this->_pluginDir.'/templates/is_child_already.php';
	}
	//
	// FORM HANDLING
	// 
	/**
	 * Handle the create child form.
	 */
	public function processCreateForm() {
		check_admin_referer( $this->_createChildFormId . '-verify' );
		$theme_name = $_POST['theme_name'];
		$description = ( empty($_POST['description']) )
			? ''
			: $_POST['description'];
		$author_name = ( empty($_POST['author_name']) )
			? ''
			: $_POST['author_name'];
		$result = $this->_make_child_theme( $theme_name, $description, $author_name );
		if ( is_wp_error( $result ) ) {
			// should show create child form again
			$this->_redirect(
				$this->_themePageUrl,
				$result->get_error_message(),
				array(
					'theme_name'  => $theme_name,
					'description' => $description,
					'author_name' => $author_name,
				)
			);
			return;
		} else {
			switch_theme( $result['parent_template'], $result['new_theme'] );
			// Redirect to themes page on success
			$this->_redirect(
				admin_url('themes.php'),
				'child_created'
			);
		}
	}
	/**
	 * Handle the repair_child_form form.
	 */
	public function processRepairChildForm()
	{
		check_admin_referer( $this->_repairChildFormId . '-verify' );
		$child_theme_dir = get_stylesheet_directory();
		$functions_file = $child_theme_dir.'/functions.php';
		$style_file = $child_theme_dir.'/style.css';

		// create functions.php if it doesn't exist yet
		if ( !file_exists($functions_file) ) {
			if ( !touch($functions_file) ) {
				// fixing is hopeless if we can't create the file :-(
				$this->_redirect(
					$this->_themePageUrl,
					'create_failed',
					array( 'filename' => $functions_file )
				);
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
			$this->_redirect(
				$this->_themePageUrl,
				'edit_failed',
				array( 'filename' => $style_file )
			);
			return;
		}

		// modify functions.php to prepend new rules
		$functions_text = file_get_contents( $this->_pluginDir.'/templates/functions.php' );
		// ^^^ above file has no final carriage return and ending comment so it should
		// "smash" the starting '<?php' string in any existing functions.php.
		$functions_text .= file_get_contents( $functions_file );
		if ( file_put_contents( $functions_file, $functions_text ) === false ) {
			$this->_redirect(
				$this->_themePageUrl,
				'edit_failed',
				array( 'filename' => $functions_file )
			);
			return;
		}
		$this->_redirect(
			$this->_themePageUrl,
			'repair_success'
		);
	}
	/**
	 * Handle the Copy Template form.
	 */
	public function processCopyTemplateForm() {
		check_admin_referer( $this->_copyTemplateFormId . '-verify' );
		$filename = ( empty($_POST['filename']) )
			? ''
			: $_POST['filename'];
		if ( !$filename ) {
			$this->_redirect(
				$this->_themePageUrl,
				'no_template'
			);
			return;
		}
		$child_theme_dir = get_stylesheet_directory();
		$template_dir = get_template_directory();
				var_dump('bar');
		if ( !file_exists($template_dir.'/'.$filename) ) {
			$this->_redirect(
				$this->_themePageUrl,
				'missing_template',
				array( 'filename' => $filename )
			);
			return;
		}
		if ( file_exists($child_theme_dir.'/'.$filename) ) {
			$this->_redirect(
				$this->_themePageUrl,
				'already_template',
				array( 'filename' => $filename )
			);
			return;
		}
		if ( !copy( $template_dir.'/'.$filename, $child_theme_dir.'/'.$filename ) ) {
			$this->_redirect(
				$this->_themePageUrl,
				'copy_failed',
				array( 'filename' => $filename )
			);
		}
		$this->_redirect(
			$this->_themePageUrl,
			'copy_success',
			array( 'filename' => $filename )
		);
	}
	/**
	 * Handle the mshot Screenshot form
	 */
	public function processMShotSiteForm()
	{
		check_admin_referer( $this->_mshotSiteFormId . '-verify' );

		// delete existing screenshot if it exists
		$child_theme_dir = get_stylesheet_directory();
		if ( $screenshot_filename = $this->_scanForScreenshot($child_theme_dir) ) {
			$screenshot_path = $child_theme_dir.'/'.$screenshot_filename;
			if ( !unlink($screenshot_path) ) {
				// most likely a directory problem Fail with an error
				$this->_redirect(
					$this->_themePageUrl,
					'delete_failed',
					array( 'filename' => $screenshot_path )
				);
				return;
			}
		}

		$mshot_url = $this->_mshotUrl();
		// Get the mshot
		$response = wp_remote_get($mshot_url);

		if ( $response['code'] == 404 ) {
			// The 404 image is gorgeous nowadays, but (if wp.com correctly handled error
			// codes for image generation) we'd not let them use it as a theme screenshot.
			$this->_redirect(
				$this->_themePageUrl,
				'mshot_404',
				array( 'url' => $mshot_url )
			);
		}
		// Should be 'image/jpeg', but let's hedge our bets
		switch ($response['headers']['content-type']) {
			case 'image/jpeg':
				$screenshot_filename = 'screenshot.jpg';
				break;
			case 'image/png':
				$screenshot_filename = 'screenshot.png';
				break;
			case 'image/gif':
				$screenshot_filename = 'screenshot.gif';
				break;
			default:
				$this->_redirect(
					$this->_themePageUrl,
					'mshot_mime_wrong',
					array( 'url' => $mshot_url )
				);
				return;
		}
		$screenshot_path = $child_theme_dir.'/'.$screenshot_filename;
		if ( file_put_contents($screenshot_path, $response['body']) === false ) {
			$this->_redirect(
				$this->_themePageUrl,
				'mshot_nocreate',
				array( 'filename' => $screenshot_path )
			);
			return;
		}

		$this->_redirect(
			$this->_themePageUrl,
			'mshot_success'
		);
	}

	//
	// PRIVATE METHOD
	//
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
		$parent_theme_dir = get_stylesheet_directory();

		// Turn a theme name into a directory name
		$new_theme_name = sanitize_title( $new_theme_title );
		$theme_root = get_theme_root();

		// Validate theme name
		$new_theme_path = $theme_root.'/'.$new_theme_name;
		if ( file_exists( $new_theme_path ) ) {
			return new WP_Error( 'exists', __( 'Theme directory already exists!', self::_SLUG ) );
		}

		mkdir( $new_theme_path );

		// Make style.css
		ob_start();
		require $this->_pluginDir.'/templates/child-theme-css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path.'/style.css', $css );

		// "Generate" functions.php 
		copy( $this->_pluginDir.'/templates/functions.php', $new_theme_path.'/functions.php' );

		// RTL support
		$rtl_theme = ( file_exists( $parent_theme_dir.'/rtl.css' ) )
			? $parent_theme_name
			: 'twentyfifteen'; //use the latest default theme rtl file
		ob_start();
		require $this->_pluginDir.'/templates/rtl-css.php';
		$css = ob_get_clean();
		file_put_contents( $new_theme_path.'/rtl.css', $css );

		// Copy screenshot
		if ( $screenshot_filename = $this->_scanForScreenshot( $parent_theme_dir ) ) {
			copy(
				$parent_theme_dir.'/'.$screenshot_filename,
				$new_theme_path.'/'.$screenshot_filename
			);
		} // removed grandfather screenshot check (use mshot instead, rly)

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
	//
	// PRIVATE UTILITY FUNCTIONS
	// 
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
	 * Handle error redirects (for admin_notices generated by plugin)
	 *
	 * Note add_query_arg() is written like shit. Here are it's problems:
	 * 
	 * 1. doesn't take advantage of built-in parse_url()
	 * 2. uses urlencode_deep() instead of an array_merge and built-in http_build_query()
	 * 3. doesn't urlencode() if $arg[0] is an array.
	 * 
	 * The 3rd one is extremely non-intuitive, but fixing it, would break backward
	 * compatibility due to double-escaping. I'm hacking around that. :-(
	 * 
	 * @param  string $url   The (base) url to redirect to, usually admin_url()
	 * @param  string $error the error code to use
	 * @param  string $args  other arguments to add to the query string
	 * @return null
	 */
	private function _redirect($url, $error, $args = array()) {
		$args['occt_error'] = $error;
		$args = urlencode_deep($args);
		wp_redirect( add_query_arg( $args, $url ) );
	}
	/**
	 * Searches directory for a theme screenshot
	 * 
	 * @param  string $directory directory to search (a theme directory)
	 * @return string|false 'screenshot.png' (or whatever) or false if there is no screenshot
	 */
	private function _scanForScreenshot($directory)
	{
		$screenshots = glob( $directory.'/screenshot.{png,jpg,jpeg,gif}', GLOB_BRACE );
		return (empty($screenshots)) 
			? false
			: basename($screenshots[0]);
	}
	/**
	 * Generate mshot of wordpress homepage.
	 *
	 * Recommende image dimensions from https://codex.wordpress.org/Theme_Development#Screenshot
	 * @todo  probably won't work correctly in multisite installs
	 * @todo  remove debugging code
	 */
	private function _mshotUrl()
	{
		$scheme = (is_ssl()) ? 'https' : 'http';
		return $scheme . '://s.wordpress.com/mshots/v1/'. urlencode(get_site_url()) . '?w=880&h=660';
	}

}

new OneClickChildTheme();
// Start this plugin
//add_action( 'admin_init', array('OneClickChildTheme','init'), 12 );
