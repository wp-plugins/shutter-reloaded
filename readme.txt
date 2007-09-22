=== Shutter Reloaded ===
Contributors: Andrew Ozz
Donate link: 
Tags: images, javascript, viewer, lightbox
Requires at least: 2.0.9
Tested up to: 2.3
Stable tag: 1.2

Darkens the current page and displays an image on top (like Lightbox, Thickbox, etc.), but is a lot smaller (under 8KB) and faster.


== Description ==

Shutter Reloaded is an image viewer for your website that works similary to Lightbox, Thickbox, etc. but is under 8KB in size and does not require any external libraries. It has all standard features: resizing large images if the window is too small to display them, previous and next links for images that are in a set, multiple sets of images on the same page and pre-loading of neighbour images for faster display.

New in version 1.2: Compatibility with WordPress version 2.0 (2.0.9 and 2.0.11) and 2.3, several improvements and small bugfixes. 

New in version 1.1: Support for Lightbox style activation (rel = lightbox[...]), better build-in help, several bugfixes.

This plugin offers full customisation: colour and opacity settings for the background, colour for the caption and the previous and next links and colour of the loading sign.

There are options to enable it for all links pointing to an image on your site (with option to exclude some pages), or just on selected pages. It can be enabled only for image links with CSS class = "shutter" with option to create a single set or multiple sets for each html page.

The plugin can also "auto-make" image sets for each page, so when several posts are displayed on the "Home" page, links to images on each post will be in a separate set. See the built-in help for more information.


== Installation ==

Standard WordPress quick and easy installation:
 
1. Download.
2. Unzip. 
3. Upload to the plugins folder.
4. Log in WordPress and activate the plugin.
5. Go to "Options - Shutter Reloaded" and set your preferences.

= Upgrade =

1. Deactivate and delete the old version.
2. Upload and activate the new one.


== Frequently Asked Questions == 

= Does this plugin work on WordPress version... =

Shutter Reloaded has been tested on WordPress 2.0.9, 2.0.11, 2.1.3, 2.2.1, 2.2.3 and 2.3-RC1

= I have ... plugin installed that also outputs javascript in the header, will there be any conflicts/incompatibilities? =

Since Shutter Reloaded does not use any js libraries, it does not interfere with them. It uses an onload event but has a function to play nice with other scripts that use onload too (from WordPress).

= What will happen if my site visitors have Javascript disabled? =

Then none of your links will be changed and will work as usual.

= I have a thumbnail link but it points to a webpage, not to image. Will that affect Shutter Reloaded? =

No, Shutter Reloaded looks only for links pointing to an image (with thumbnails or not), and will not change any other link, even if the link has the same CSS class used for activation.


== Screenshots ==

For screenshots and demo, visit the home page for [Shutter Reloaded](http://www.laptoptips.ca/projects/wp-shutter-reloaded/). 
