=== One-Click Child Theme ===
Contributors: tychay
Donate link: http://www.kiva.org/lender/tychay
Tags: birthday
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

Adds a Theme option to any active theme allowing you to make a child theme.

== Description ==

Useful for shared hosts, this allows you to easily create child themes from any
theme just by clicking.

In the current version of WordPress, you shouldn’t modify CSS of any downloaded themes
because if you update the theme, your changes will be destroyed. What you should
instead do is create a child theme and edit the CSS there, this way updates to the
parent theme will be inherited instead of destroy your changes. The problem is that
currently the only way to child theme something is edit files on the filesystem. This
is non-intuitive for shared-hosting sites with one-click WordPress installs (it
usually involves a “shell account” or learning how to use FTP).

This attempts to get around that issue, by adding a button to the themes page to allow
you to child theme the page. (It’s not really one-click, though.)

Inspired by @janeforshort‘s WordPress talk on CSS theming as requested by @sfgirl.
== Installation ==

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and
then upload it to `/wp-content/plugins/`. Then just visit your admin area and activate
the plugin. That's it!

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

###Using the plugin###

If you have teh capability to install themes in your admin, then the themes menu will
have a new submenu called "Child Theme". Clicking on this gives you a form that will
allow you to create a child theme based on the current active theme.

== ChangeLog ==

**Version 1.0**

* Initial release
* 456789001234567890012345678900123456789001234567890012345678900123456789001234567890

== Future Features ==

* No support for RTL compatible themes
* No support for multiple theme directories
* Error support is spotty at best
* Theme may be created, but it is not network enabled on multisite
