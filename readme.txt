=== Shutter Reloaded ===
Contributors: Andrew Ozz
Donate link: 
Tags: images, javascript, viewer, lightbox
Requires at least: 2.1
Tested up to: 2.2.2
Stable tag: 1.1

Darkens the current page and displays an image on top (like Lightbox, Thickbox, etc.), but is a lot smaller (under 8KB) and faster.


== Description ==

Shutter Reloaded is an image viewer for your website that works similary to Lightbox, Thickbox, etc. but is under 8KB in size and does not require any external libraries. It has all standard features: resizing large images if the window is too small to display them, previous and next links for images that are in a set, multiple sets of images on the same page and pre-loading of neighbour images for faster display.

New in this version: support for Lightbox style activation (rel = lightbox[...]), better build-in help, several bugfixes.

This plugin offers full customisation: colour and opacity settings for the background, colour for the caption and the previous and next links and colour of the loading sign.

There are options to enable it for all links pointing to an image on your site (with option to exclude some pages), or just on selected pages. It can be enabled only for image links with a specific CSS class. That class can be set from the options page in WordPress, so if you already have thumbnails linked to images and set with a CSS class, you only need to enter that class in the Shutter Reloaded options.

It can also "auto-make" image sets for each page, so when several posts are displayed on the "Home" page, links to images on each post will be in a separate set.


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

Shutter Reloaded has been tested on WordPress 2.1.3, 2.2.1 and 2.2.2

= I have ... plugin installed that also outputs javascript in the header, will there be any conflicts/incompatibilities? =

Since Shutter Reloaded does not use any js libraries, it does not interfere with them. It uses an onload event but has a function to play nice with other scripts that use onload too (from WordPress).

= What will happen if my site visitors have Javascript disabled? =

Then none of your links will be changed and will work as usual.

= I have a thumbnail link but it points to a webpage, not to image. Will that affect Shutter Reloaded? =

No, Shutter Reloaded looks only for links pointing to an image (with thumbnails or not), and will not change any other link, even if the link has the same CSS class used for activation.


== Screenshots ==

For screenshots and demo, visit the home page for [Shutter Reloaded](http://www.laptoptips.ca/projects/wp-shutter-reloaded/). 


== Setup and Usage ==

* If you activate Shutter Reloaded for all image links on a specific page (or on the entire site) and don't want to use sets, you don't need to change anything else. 

* If you want to display captions under the images, you will need to set the **title=""** attribute on the image links. That can be done from the *Uploads* admin page or directly in the html when editing a Post or a Page in *Code* view.

* If you want to display images in a set, you will need to add **class="shutterset"** to the image links. That class will also be used for activation, so there is no need to add *class="shutter"* if you chose to activate Shutter Reloaded with a css class.

* If you want to make multiple sets, you will need to add **class="shutterset_123"** to the image links, where 123 can be any one to tree digits number (unique for each set).

See the build-in help (button on the top right side of the admin page) for more details.
