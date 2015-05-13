=== One-Click Child Theme ===
Contributors: tychay  
Donate link: http://www.kiva.org/lender/tychay  
Tags: theme, child theme, child theme creator, child theme creator, CSS, stylesheet, custom theme, customize theme, shared hosting  
Requires at least: 3.0  
Tested up to: 4.1.1  
Stable tag: trunk  

Adds a Theme option to any active theme allowing you to make a child theme.

== Description ==

Please visit the [plugin homepage](http://terrychay.com/wordpress-plugins/one-click-child-theme).

Useful for shared hosts, this allows you to easily create child themes from any
theme just by clicking.

Ever since WordPress 3.0, you shouldn’t directly modify CSS of any downloaded
themes because if you update the theme, your changes will be destroyed. Instead, it is recommended that you create a child theme and edit the CSS
there so that updates to the parent theme will be inherited instead of destroy
your changes.

The problem many run into is currently the only way to child theme something
is edit files on the filesystem. This is non-intuitive for shared-hosting
sites with one-click WordPress installs (it usually involves a “shell account”
or learning how to use FTP).

This attempts to get around that issue, by adding a button to the themes page
to allow you to child theme the page. (It’s not really one-click, though.)

Inspired by @janeforshort's and @designsimply's WordCamp SF 2011 talk on CSS
theming as requested by @sfgirl for [her blog](http://pintsizedmusings.com/).

== Installation ==

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure
intact, and then upload it to `/wp-content/plugins/`. Then just visit your
admin area and activate the plugin. That's it!

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

You can wait for the theme to get updated and have it break it, or you can:

1. Go through the steps for installing and running the plugin above to create a child theme
2. Click on the [Appearance &gt; Editor](http://codex.wordpress.org/Appearance_Editor_SubPanel) in your admin dashboard menu (or network admin menu if multi-site).
3. Select the parent theme in the drop down on the right, click `Select` and make sure you are editing the file `style.css` (select on the right).
4. Copy the changes you made, if you managed to remember them.
5. Select the child theme you created to the drop down in the left (you should be editing `style.css`).
6. Paste your changes to the end of the file.

= Your plugin broke my site! =

I didn't think that's possible, but if so, I want to fix it!

First, check what really happened. Is your admin console broken, is the
theme broken (go to a new window and check your blog). If a theme fails to
work and for some reason I didn't catch that error, WordPress should restore
the previous theme (or whatever the default theme is) so your actual blog
should be okay and recoverable. If for some reason it didn't default to the
right theme, go into the Appearance menu and re-enable the parent theme.

Then go to the [support page](https://wordpress.org/support/plugin/one-click-child-theme),
describe what happened (screenshots help too) and anything else and we'll try
our best to help you.

= I can't find this Theme Option button you are alluding to in the documentation? =

I really need to update the screenshot. It's still there, but the location has
changed as WordPress has been upgraded.

1. Go to the `Appearance` tab
2. Click on the Active theme (it should say "Theme Details" when you mouseover)
3. An overlay appears. The Theme option button "Child Theme" is there

= When I go to the Child Theme menu, it says "X is already a child theme" and I can't create a child theme. =

Making grandchildren of themes is non-trivial, so I disabled the form if it is
already a child theme. Instead I offer the ability to repair the Child theme or
copy template files from the parent into the child for editing.

= Can the plugin be deleted after I create a Child Theme with it? =

Yes. The main purpose of the plugin is fulfilled. Congratulations!
(Personally, I'd disable it, instead of delete it.)

Having said that, there are some things that this plugin help with after your
child theme's birth. Think of it as a parenting guide for your new child theme.

= Features like? =

* Repair a child theme created in the old style.
* Copy templates over from your parent theme into your child theme.
* Replace the child theme screenshot with one of your site

When you have an active child theme, click on `Appearance > Child Theme` to
get to these functions.

= What does the "Repair Child Theme" button do? =

WordPress changed the [recommended way of handling parent references in child themes](http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme).
If this plugin detects your child theme was done in the old style, it shows
this button. Clicking on it will make the plugin attempt a repair into the
new style.

= How come the screenshot service just shows a big 404 =

The most likely scenario is your WordPress `site_url` isn't publicly
accessible from the web.

= What's with the debugging/error code on child theme creation? =

You have an old version of this plugin, please update to the latest one.

I wrote this plugin back **during** a talk at WordCamp SF 2011 about CSS
Themeing in order to help the person sitting next to me. I just didn't get to
some things… for years. Sorry about that! The debugging code didn't affect any
behavior — it was a sign of me being lazy and not realizing that the plugin
would have tens of thousands of avid users!

= The theme screenshot service doesn't seem to be working. =

First of all, is the blog accessible from the public Internet? If not, then
that is the problem.

But sometimes, even then, mshots seems a bit on the slow side. The plugin
uses WordPress.com’s mShots service. You know, I worked at Automattic for years
on WordPress.com and I still haven't quite figured out mShots. Eventually
I've gotten the animated loading GIF to be replaced by a real retina-ready JPEG
screenshot of my blog homepage. Trust me, when it works, it works great! But
the experience may be a little like trying to get a vending machine to accept
a crumpled dollar bill — Americans know what I mean. Suffice it to say that
you might have to hit reload a couple times, or just fiddle around a bit.

= Why should I use your plugin and not [_insert competitor here_](https://wordpress.org/plugins/search.php?q=child+theme) =

What? People have forked my idea because I left [Automattic](http://automattic.com)
for [Wikimedia](http://wikimediafoundation.org/wiki/Home) three years ago?
This means war! Other plugins, you're going down! Steel yourself for the
pent-up aggression of a pointy-haired boss being kept away from a
programming console for years.

As to why this plugin is the best? OCCT is Coke Classic to every other plugin's
[New Coke](http://en.wikipedia.org/wiki/New_Coke). Experience the original
plugin taste your parents knew and loved! Plus, I have
four-year old screenshots on my theme page, a way cooler plugin icon, and a
baby picture of me and my brother on the banner. Also, this plugin strikes
the right balance of features, is free (no "pro" version and not even a PayPal
link), and (now that I've figured out how to admin the plugin page), I've
been adding volunteers to support it. Even if I sucked into the vortex of
middle management again (isn't going to happen, that s--t is **EVIL**) this
plugin will live forever in the hearts and minds of bloggers everywhere!

Which reminds me, if you want to help out, we're cool with that. Like WordPress
itself, this is a volunteer endeavor. Contact us in the support pages and we'll
hook you up!

== ChangeLog ==

**Version 1.7**

* Documentation: FAQ fixes

**Version 1.6**

* Feature: Added ability to generate theme screenshots (thanks [@janeforshort/@jenmylo](https://jane.wordpress.com/) for the idea)
* Feature: Redirect to theme page on child theme creation
* Feature: Successful child theme creation suggests you edit its `style.css` file
* Performance: Only run code in admin page
* Bug: Added in some missing gettext
* Bug: Removed the double errors/updates being displays
* Documentation: Make sure description is under 140 characters
* Documentation: Screenshots now display
* Documentation: Added banner image and plugin icon
* Documentation: Other minor tweaks including updated FAQ and license

**Version 1.5**

* Feature: Added ability to repair child theme
* Feature: Added ability to copy any template file from parent theme (thanks [Michael Rawlings](http://michaelrawlins.co.uk) for the idea)
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

