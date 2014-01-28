=== Yelp Widget Pro ===
Contributors: dlocc
Donate link: http://imdev.in/
Tags: yelp, yelp widget, yelp shortcode, yelp api, yelp business listings, yelp reviews, yelp widget pro
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.8.1

Yelp Widget Pro makes it easy for you to add business listings to your website or blog via an easy-to-use and intuitive widget.

== Description ==

= Yelp Widget Pro Premium =
[Upgrade to Yelp Widget Pro Premium](http://wordimpress.com/wordpress-plugin-development/yelp-widget-pro/ "Upgrade to Yelp Widget Pro Premium")

*Yelp Widget Pro Premium* is a **significant upgrade** to *Yelp Widget Pro* that adds many features that will allow you to further customize your widgets with Google Maps, Yelp review snippets, additional graphics and display options plus so much more! Also included is priority support, lifetime updates, multisite support and well-documented shortcodes to use in any page or post.

= Yelp Widget Pro =
Yelp Widget Pro allows you to easily display Yelp profiles for any business on your website or blog using an intuitive and easily configurable widget. Yelp Widget Pro users are able to display business names, ratings, review counts and profile images in any WordPress sidebar. Customize the widget to display one or many listings from Yelp based on location.

This widget supports for Yelp v2.0's Search and Business API methods. Yelp Widget Pro allows for multiple widgets within the same or separate sidebars. No coding knowledge required.

Yelp Widget Pro is actively supported and developed. The open source version is available for free to the WordPress community. For additional options and priority support please consider [upgrading to Yelp Widget Pro Premium](http://wordimpress.com/wordpress-plugin-development/yelp-widget-pro/). If you like this plugin please [rate it on WordPress.org](http://wordpress.org/support/view/plugin-reviews/yelp-widget-pro/).

= Features =

1. Display Content by Yelp Business ID or Search Term.
2. Option to Cache Data to Save API Requests
3. Option to disable widget output title
4. Option to disable plugin CSS
5. Clean and easy-to-configure user interface
6. Actively developed and improved
7. Option to open links in new window
8. Option to no-follow links for all the SEOs

== Installation ==

1. Upload the `yelp-widget-pro` folder and it's contents to the `/wp-content/plugins/` directory or install via the WP plugins panel in your WordPress admin dashboard
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access the Plugin's options via your settings panel.  You will need to enter your Yelp API information prior to using the plugin.

Note: If you have Wordpress 2.7 or above you can simply go to 'Plugins' &gt; 'Add New' in the WordPress admin and search for "Yelp Widget Pro" and install it from there.

== Frequently Asked Questions ==

= Why should I use this plugin? =

Use this plugin if you have a website that would benefit in displaying search results for a Yelp search term.  For example, a travel website selling reservations could display the top resorts for a given destination.  A business website could also display their yelp business listing in their sidebar by using the Business ID search option

= How do I display only my business? =

If you would like to display only your certain business then you must enter in your "Yelp Business ID" in the widget's ID input.  The ID of the business is the last part of the Yelp URL of its Yelp page. Ex: `http://www.yelp.com/biz/togos-sandwiches-san-diego-3`, the id is `togos-sandwiches-san-diego-3`.  This is the *only* parameter you need to set to use this method.

= How do I disable the CSS =

If you would like to theme the widget yourself you should disable the plugin's CSS output.  To do that please visit the options page (Settings > Yelp Widget Pro) and check the appropriate option.

= The plugin looks funny in my sidebar, what's the deal? =

Some themes may have very small sidebars and CSS styles that conflict or alter the styles within Yelp Widget Pro.  To correct any styling errors you can either disable the plugin's CSS all together or override the CSS selectors in use to make the widget appear how you'd like.  CSS-related issues are not actively supported as there's too many variations between the thousands of WordPress themes available.

== Screenshots ==

1. A view of the Yelp Widget Pro Settings page displaying the metabox to enter in your Yelp API v2.0 Information

2. Many plugins don't have the option to disable CSS - this one does.  If you want to style the plugin to suit your needs then enable this option.

3. Yelp Widget Pro expanded displaying all available options as of version 1.1

4. How the widget looks in a website sidebar

== Changelog ==

= 1.8.1 =
* Fixed: Bug with maps markers not displaying points properly for both the search map and widget maps

= 1.8 =
* New: Display phone number of the businesses
* Updated: UI for WP 3.8 new style
* Updated: oAuth library modified to not conflict with plugins using same class
* Fixed: Issue with map shortcode preventing map from displaying properly
* Fixed: Tooltips in the admin screen
* Fixed: PHP notices with WP_DEBUG set to true
* Fixed: Various broken links

= 1.7.1 =
* Fix for new licensing

= 1.7 =
* New licensing system implemented

= 1.6 =
* Fixed: Illegal string offset warnings in PHP 5.4+
* Compatibility check with WordPress 3.7

= 1.5.7 =
* New: Grunt JS implemented and minified scripts implemented
* Fixed: Debugged shortcode and widget so no PHP notices or warnings
* Added functionality to better debug the widget

= 1.5.6.2 =
* Fixed: Undefined Variable PHP Notices

= 1.5.6.1 =
* Fixed: Issue with unavailable automatic updates and new licensing server

= 1.5.6 =
* Improvement: Updater script for Yelp plugin updates secured
* Update: Found workaround for PHP json validation for users of older PHP versions

= 1.5.5 =
* Updated/Fixed: Yelp changed their API (see for more info: https://groups.google.com/forum/#!topic/yelp-developer-support/SVoLQR9RiKo) for new API keys so that coordinates for businesses are not displayed. Yelp Widget Pro now has code to geocode the addresses to locations. Due to Google APIs limitation of 10 geocodings per request I have coded in a timeout of 200 miliseconds for each result over 10. More improvements coming to the Yelp search feature of the plugin.
* Updated: CSS improvements for image sizes that themes may override

= 1.5.4 =
* Updated: Improved logic to communicate between Software API and plugin

= 1.5.3 =
* Fixed: Minor bug fix with shortcode output

= 1.5.2 =
* New: Added "Read Full Review" link to individual reviews that link to Yelp with the ability to turn on and off per widget/shortcode
* New: Added field to customize the text on the "Read Full Review" link
* Updated: Minor standard theme style improvements for various theme compatibility
* Improved: Error handling. Added error message for Yelp businesses with no reviews - API doesn't allow
* Fixed: Error in handling target="_blank" and rel="nofollow" args in shortcode - updated online docs

= 1.5.1 =
* NEW: Added Google Map above or below positioning options functionality to main widget and shortcode (updated documentation)
* NEW: Added profile_image_size argument to customize size of business profile image when using search api shortcode
* NEW: Ability to disable Google maps scrollability to widget and shortcode (updated documentation)
* Fixed: CSS issue with admin widget hiding AJAX spinner
* Fixed and Improved: Google Maps Search shortcode longitude and latitude geocoding retrieval methods
* Fixed: Issue with Google Maps Search shortcode default biz image incorrect path (for businesses w/o an image set)
* Removed: Old widget.php file removed
* Removed: GPL license info from yelp-widget-pro.php

= 1.5 =
* NEW: Added Yelp Google Search Map widget and shortcode capability - enjoy!
* NEW: Added review filter option - time to filter those bad reviews!
* NEW: Added option to hide business information including profile image, name, reviews and address
* NEW: Added option to hide ratings stars and date (for black backgrounds and other reasons).
* NEW: Added shortcode cache option - now you can specify how long to cache shortcode output
* NEW: Added support and documentation links to individual widgets
* ADDED: New reviewer image size option (60x60)
* UPDATED: By default links open in a new window
* UPDATED: Google map markers results with no image display blank biz image
* Improved shortcodes handling of scripts
* Improved folder structure of plugin files
* Code improvement and optimization

= 1.3.6 =
* FIXED: Issue with Sorting feature not working properly
* NEW: Social media buttons in settings panel (like us if you get a chance!)
* UPDATED: Support links to forum updated

= 1.3.5.2 =
* UPDATED: Licensing logic for plugin
* Code cleanup and optimization

= 1.3.5.1 =
* UPDATED: Reverted license update method back to curl from wp_remote_get due to some issues with various hosts
* Minor UI Updates
* Code cleanup and improvements

= 1.3.5 =
* NEW: Added tooltips with information and links to screencast tutorials on the widgets and on widget settings pages
* NEW: Added default image for businesses without profile images
* NEW: Added Profile image size select; now you can easily modify the size of your Yelp profile image
* NEW: Added links to Options page, Rate the Plugin and Premium Upgrade on the Plugins page
* UPDATED: Changed default number of items in Search Method to 4 rather than 1
* UPDATED: Changed default cache value to 1 Day to encourage caching results
* UPDATED: Widget image output alt and title tags for better SEO optimization
* UPDATED: Readme.txt file with additional information and updated description
* UPDATED: Premium plugin update classes to latest version from GitHub
* FIXED: Fixed Premium licensing metabox inputs min-width issue
* FIXED: Business address repeating first address issue
* Added link to Premium Version on widget
* Code cleanup and organization


= 1.3 =
* Release of plugin w/ licensing logic
* UI and code cleanup

= 1.2 =
* Added localization support for future translations.  If you are interested in helping with translation please contact me!
* Additional UI updates and tweaks
* Updated yelp_widget_curl() function to use WordPress' HTTP API first and backup as cURL
* Integrating premium licencing logic; GPL compatible plugin
* Fixed UI bug with widget API selection radio
* Updated Facebook Like box to new WordImpress (no-ed) page
* Grammatical fixes
* Improved widget frontend CSS

= 1.1 =
* Improved frontend widget display CSS with percentage-based element widths
* Added business address display option to widget functionality
* Cleaned up Widget UI: Added toggle option panels
* Cleaned up options panel UI: Added metaboxes to hold content; fixed typo in introduction; added like box for WordImpressed
* Improved how scripts are loaded in the WordPress admin panel by only loaded them on the pages needed
* Coming soon: Premium Add-ons! Themes, New Features and More.

= 1.0 =
* Initial plugin release - Special thanks to the Yelp It plugin for kickstarting this widget