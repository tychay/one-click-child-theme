=== One-Click Child Theme ===
Contributors: tychay
Donate link: http://www.kiva.org/lender/tychay
Tags: theme, child theme, shared hosting, css, custom themeing
Requires at least: 3.0
Tested up to: 4.1
Stable tag: trunk

Adds a Theme option to any active theme allowing you to make a child theme.

== Description ==

Please visit the [plugin homepage](http://terrychay.com/wordpress-plugins/one-click-child-theme).

Useful for shared hosts, this allows you to easily create child themes from any
theme just by clicking.

Ever since WordPress 3.0, you shouldn’t directly modify CSS of any downloaded
themes because if you update the theme, your changes will be destroyed. Instead,
it is recommended that you create a child theme and edit the CSS there, this way
updates to the parent theme will be inherited instead of destroy your changes.
The problem is that currently the only way to child theme something is edit
files on the filesystem. This is non-intuitive for shared-hosting sites with
one-click WordPress installs (it usually involves a “shell account” or learning
how to use FTP).

This attempts to get around that issue, by adding a button to the themes page to
allow you to child theme the page. (It’s not really one-click, though.)

Inspired by @janeforshort‘s and @designsimply's WordCamp SF 2011 talk on CSS
theming as requested by @sfgirl for [her blog](http://pintsizedmusings.com/).

== Installation ==

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure
intact, and then upload it to `/wp-content/plugins/`. Then just visit your admin
area and activate the plugin. That's it!

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

###Using the plugin###

If you have the capability to install themes in your admin, then the themes menu
will have a new submenu called "Child Theme". Clicking on this gives you a form
that will allow you to create a child theme based on the current active theme.

== Screenshots ==

1. To use the plugin, click one of these two palces in the Appearance section of your `wp_admin` 
2. Fill out this form
3. You will see the theme will be successfully child-themed, but will track the parent theme correctly.

== Frequently Asked Questions ==

= I already modified my CSS in the existing theme? How do I use One Click Child Theme to fix this? =

You can wait for the theme to get updated and have it break it, of you can:

1. Go through the steps for installing and running the plugin above.
2. Click on the [Appearance &gt; Editor](http://codex.wordpress.org/Appearance_Editor_SubPanel) in your admin dashboard menu (or network admin menu if multi-site).
3. Select the parent theme in the drop down on the right, click `Select` and make sure you are editing the file `style.css` (select on the right).
4. Copy the changes you made, if you managed to remember them.
5. Select the child theme you created to the drop down in the left (you should be editing `style.css`).
6. Paste your changes to the end of the file.

= What does the "Repair Child Theme" button do? =

WordPress changed the [recommended way of handling parent references in child themes][http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme]. if this plugin detected 
the child theme was done in the old style, it shows this button. Clicking on it
will make the plugin attempt a repair into the new style.

== TODO List ==

* There is a buffering issue with form handling occurring so late, fix that. See https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
* The in theme button for childing is no longer there. Figure out if I can add it back.
* In some cases, settings_error() can be shown twice. Remove settings_error() after buffering fixed
* Better support for grandchildren (should copy the files over)
* Support for multiple theme directories [ may be fixed ]
* Error support is spotty at best
* Add a redirect to the theme page on completion (buffering issue needs fixed first)
* Use Theme_Upgrader/WP_Upgrader to figure out what files user may have trashed and ported them

== ChangeLog ==

**Version 1.6**

* Performance: Only run code in admin page
* Bug: Added in some missing gettext 
* Documentation: Make sure description is under 140 characters
* Documentation: Updated license
* Documentation: Other minor tweaks

**Version 1.5**

* Feature: Added ability to repair child theme
* Feature: Added ability to copy any template file from parent theme.
* Design: Upgrade look of form to resemble most admin forms.
* Bug Fix: Properly shows a status message on success.
* Documentation: Added section for FAQ and Screenshots.
* Documentation: Some housecleaning of filesystem structure of plugin

**Version 1.4**

* Bug Fix: Modified to account for [changed best practice from using @import to function.php](http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)

**Version 1.2**

* Bug Fix: Remembers to network enable (activate) the theme after creation.
* Feature: Added screenshot support (Thanks! Chris Robinson <http://contempographicdesign.com/>)
* Bug Fix: WP_Error handling
* Documentation: Refactored codebase

**Version 1.1**

* Feature: Added RTL support

**Version 1.0.1**

* Commenting changes.

**Version 1.0**

* Initial release
* 456789001234567890012345678900123456789001234567890012345678900123456789001234567890

