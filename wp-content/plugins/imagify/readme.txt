=== Imagify Image Optimizer ===
Contributors: wp_media, GregLone
Tags: compress image, images, performance, optimization, photos, upload, resize, gif, png, jpg, reduce image size, retina
Requires at least: 3.7.0
Tested up to: 4.9.4
Stable tag: 1.7

Dramatically reduce image file sizes without losing quality, make your website load faster, boost your SEO and save money on your bandwidth.

== Description ==

Speed up your website with lighter images without losing quality.

Imagify is the most advanced image compression tool, you can now use this power directly in WordPress.
After enabling it all your images including thumbnails and retina images from WP Retina x2 will be automatically optimized on the fly when you will add in into WordPress.

WooCommerce and NextGen Gallery compatible.

= What is Image Compression? =

Learn more about image compression, check that: [https://imagify.io/images-compression](https://imagify.io/images-compression)

= Why use Imagify to optimize your images? =

You already have lots of unoptimized images? Not a problem, you will love the Bulk Optimizer to optimize all your existing images in one click.

Imagify can directly resize your images, **you won't have to lose time anymore on resizing your images before uploading them**.

Three level of compression are available:

- Normal, a lossless compression algorithm. The image quality won't be altered at all.
- Aggressive, a lossy compression algorithm. Stronger compression with a tiny loss of quality most of the time this is not even noticeable at all.
- Ultra, our strongest compression method using a lossy algorithm.

With the backup option, you can change your mind whenever you want by restoring your images to their original version or optimize them to another compression level.

= What our users think of Imagify? =

> "Imagify is an awesome tool that is powerful & easy to use. It's fast, rivals and surpasses other established plugins/software. Awesome!" — [Simon Harper](https://twitter.com/SRHDesign/status/663758140505235456)
>
> "If you want to "squeeze" your images as much as possible and "trim out" your website on the highest professional level... Imagify" — [Ivica Delic](https://twitter.com/Free_LanceTools/status/685503950909476865)
>
> "Clearly Imagify is the most awesome WordPress plugin to compress images on your website! A must try" — [Eric Walter](https://twitter.com/EricWaltR/status/679053496382038016)
>

= Is Imagify Free? =

You can optimize for free 25MB of images (about 250 images) every month and you will receive a 25MB bonus upon registration.

Need more? Have a look at our plans: [https://imagify.io/pricing](https://imagify.io/pricing)

= What's next? =

Have a look at our upcoming features by following our development roadmap: [https://trello.com/b/3Q8ZnSN6/imagify-roadmap](https://trello.com/b/3Q8ZnSN6/imagify-roadmap)

= Who we are? =

We are [WP Media](https://wp-media.me/), the startup behind WP Rocket the best caching plugin for WordPress.

Our mission is to improve the web, we are making it faster with [WP Rocket](https://wp-rocket.me/) we want to make it lighter with Imagify.

= Get in touch! =

* Website: [Imagify.io](https://imagify.io)
* Contact Us: [https://imagify.io/contact](https://imagify.io/contact)
* Twitter: [https://twitter.com/imagify](https://twitter.com/imagify)

= Related Plugins =
* [WP Rocket](https://wp-rocket.me/): Best caching plugin to speed-up your WordPress website.
* [Rocket Lazy Load](https://wordpress.org/plugins/rocket-lazy-load/): Best Lazy Load script to reduce the number of HTTP requests and improves the websites loading time.

License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Installation ==

= WordPress Admin Method =
1. Go to you administration area in WordPress `Plugins > Add`
2. Look for `Imagify` (use search form)
3. Click on Install and activate the plugin
4. Optional: find the settings page through `Settings > Imagify`

= FTP Method =
1. Upload the complete `imagify` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Optional: find the settings page through `Settings > Imagify`

== Frequently Asked Questions ==

= Which formats can be optimized? =

Imagify can optimize jpg, png and gif (whether animated or not) formats.

= Can I use the plugin with a free account? =

Absolutely. You are limited to a quota of 25 MB of images per month with a free account. Once this quota is reached, you cannot optimize new images until your quota is renewed or you purchase credits.

= On how many websites can I use the plugin? =

You can use the plugin on as many sites as you wish. The only limit is the optimization quota of your account.

= I used Kraken, Shortpixel, Optimus, EWWW or WP Smush, will Imagify further optimize my images? =

Absolutely. Most of the time, Imagify will still be able to optimize your images even if you have already compressed them with another tool.

= What is the difference between the Normal, Aggressive and Ultra compression levels? =

Normal compression is a "lossless" optimization. This means there is no loss of image quality. Aggressive and Ultra compression are more powerful, so the picture quality will be somewhat reduced. The weight of the image will be much less.

= Is the EXIF data of images removed? =

By default EXIF data is removed. It is however possible to keep it by enabling the option.

= Will the original images be deleted? =

No. Imagify automatically replaces the images with an optimized image. The backup option allows you to keep the original images and restore them with one click.

= Is it possible to re-optimize images with a different level? =

Yes. By activating the backup option in the plugin, you can re-optimize each image with a different compression level.

= If I use Imagify, do I need to continue optimizing and resizing my images with Photoshop? =

Do not waste your time resizing and optimizing your images in Photoshop. Imagify takes care of everything!

= What happens when the plugin is disabled? =

When the plugin is disabled, your existing images remain optimized. Backups of the original images are still available if you have enabled the images backup option.

== Screenshots ==

1. Bulk Optimization

2. Settings Page

3. Media Page

4. Other Media Page

== Changelog ==
= 1.7 - 2018/03/13 =
* New: you can now optimize the images from your themes and plugins, or from any other folder in your site!
* Improvement: compatibility with old and new versions of WP Offload S3 plugins.
* Improvement: don't start the bulk optimization process if cURL is not available.
* Bug Fix: image dimensions not being stored sometimes after it is resized.
* Bug Fix: the comparison tool could display multiple handles.
* Bug Fix: issue with php 7.2.
* Dev stuff: lots of internal changes, many things have been rewritten.
* Dev stuff: the default options can now be filtered.

= 1.6.14.2 - 2018/01/15 =
* Improvement: force browsers not to use the old version of our script for the charts.

= 1.6.14.1 - 2018/01/11 =
* Bug Fix: no more conflicts between our script used for the charts and theme builders, or plugins that use an outdated version of this script.

= 1.6.14 - 2018/01/10 =
* New: added compatibility with partners' plugins.
* Improvement: updated the script used for the charts, it will lower the risk of conflicts with other plugins (that are also up-to-date).
* Improvement: the comparison tool button is now also inserted when clicking the next/previous buttons in the media modal.
* Bug Fix: the comparison tool button should not be inserted several times anymore.
* Bug Fix: the images wouldn't appear in the comparison tool sometimes.

= 1.6.13.1 - 2017/11/08 =
* Bug Fix: fixed a php error with php 5.2.

= 1.6.13 - 2017/11/07 =
* New: added links to the documentation in Imagify' settings and bulk optimization pages.
* Improvement: better compatibility with NextGen Gallery plugin. Imagify no longer resizes NextGen images nor removes exif, to let NextGen Gallery do its job peacefully.
* Improvement: better compatibility with WP Real Media Library plugin, our modal wasn't working correctly.
* Improvement: better compatibility with plugins that use cookies, like Duo Two-Factor Authentication and Shield Security, to prevent being disconnected.
* Improvement: better compatibility with SiteGround. A "security" measure was preventing Imagify to work correctly.
* Improvement: better compatibility with hosts that limit some SQL queries, it prevented our bulk optimization to work.
* Improvement: better compatibility with Heartbeat Control plugin, it prevented our bulk optimization to work.
* Improvement: better compatibility with Formidable Forms Pro plugin, the bulk optimizer was never satisfied.
* Bug Fix: fixed a few bugs when optimizing in NextGen Gallery.

= 1.6.12 - 2017/10/18 =
* New: added links to the documentation in the plugin's admin bar item and the plugin's row (plugins page). There is more to come.
* Improvement: image attachments that don't have some mandatory WordPress metadata are not included in Imagify stats anymore.
* Fix: the "Optimized size" progress bar in the bulk optimization page now behaves like the "Original size" one does.
* Dev stuff: auto-optimization can be disabled on an attachment basis with the new filter `imagify_auto_optimize_attachment`. For example it can be used to disable auto-optimization for a specific file extension.
* Dev stuff: classes are now auto-loaded. Some constants have been removed.

= 1.6.11 - 2017/10/12 =
* Improvement: Imagify now works with the iOS app, and with XML-RPC in general.
* Improvement: we harmonized and improved how user roles are handled.
* Improvement: prevent optimized image to be cached by the browser in the comparison tool.
* Fix: sometimes the comparison tool's button wouldn't show on the attachment edition page.
* Fix: the bulk optimization button works again.

= 1.6.10 - 2017/10/05 =
* New: if new thumbnail sizes appear after activating a new theme or plugin, you can now optimize only these missing sizes instead of restoring and re-optimizing all images.
* Improvement: CSS and JS files have been split and are loaded only when needed.
* Improvement: in each NextGen Galleries you now have "Optimize" and "Restore" bulk actions.
* Improvement: better banner placements with languages with long sentences (looking at you, Germany).
* Improvement: messages like the "WELL DONE" one can now be translated.
* Bug Fix: the account infos in the admin bar now works properly on front-end.
* Bug Fix: some thumbnail sizes with curious name were not listed in the settings page.
* Bug Fix: improved library size calculation for "What plan do I need?". Some thumbnail sizes were missing, lowering the result.
* Regression fix: the issue with Imagify's popup on WP Rocket options screen is now also solved when WP Rocket is white-labelled.
* Lots of various small fixes and code improvements.

= 1.6.9.1 - 2017/08/12 =
* Regression fix: don't load Imagify's popup files on WP Rocket options screen to avoid conflicts.

= 1.6.9 - 2017/08/11 =
* Improvement: the bulk optimization now stops as soon as the quota is fully consumed, instead of trying to optimize more images and getting error messages one after the other.
* Improvement: updated (almost) all JavaScript libraries we use. SweetAlert won't conflict with new versions anymore. Few code improvements.
* Improvement: in the medias list, improved the Imagify column behavior on small screens.
* Improvement: when optimizing in NextGen Gallery, update some NGG data.
* Bug Fix: revamped the whole Enable Media Replace plugin compatibility. Optimization, restoration, and backup should work properly now.
* Bug Fix: revamped the way to restore images in NextGen Gallery to prevent deletion of alt text, description, and tags.
* Regression fix: fixed optimization and restoration not working in NextGen Gallery.
* Regression fix: fixed the bulk optimization not working with PHP 5.2.

= 1.6.8 - 2017/07/26 =
* Improvement: don't display the restore bulk action in the medias list if there is nothing to restore.
* Improvement: you can know select and unselect all image sizes at once in the settings page.
* Improvement: detect when the backup directory is not writable. A warning is displayed dynamically under the backup setting, a notice is also displayed on some pages.
* Improvement: some strings were still not translated in the bulk optimization page.
* Bug Fix: the "Save & Go to Bulk Optimizer" button now redirects you even if no settings have been changed.
* Lots of various small fixes and code improvements.

= 1.6.7.1 - 2017/07/13 =
* Bug Fix: Fixed the "Unknown error" during a bulk optimization.

= 1.6.7 - 2017/07/12 =
* Improvement: Compatibility with the plugin WP Offload S3 Pro, and fixed a few things for both Lite and Pro versions.
* Improvement: Improved performance on the bulk optimization page for huge image libraries.
* Improvement: When performing a bulk optimization, moved the attachments with the "WELL DONE" message at the end of the queue, it helps to speed up things.
* Improvement: Use cURL directly only to optimize an image. It helps when cURL is not available: less things will break in that case.
* Bug Fix: Fixed a bug with the plugin Screets Live Chat, prior to version 2.2.8.
* Regression fix: Fixed the buffer size on the bulk optimization page.
* Dev stuff: Added a hook allowing to filter arguments when doing a request to our API. It can be used to increase the timeout value for example.

= 1.6.6 - 2017/06/27 =
* New: Compatibility with the plugin WP Offload S3 Lite. Your images now will be sent to Amazon S3 after being optimized. Also works when you store your images only on S3, not locally.
* Improvement: Added a filter to the asynchronous job arguments.
* Bug fix: Compatibility with Internet Explorer 9 to 11.
* Regression fix: The comparison tool stopped working in the medias list since the previous version.

= 1.6.5 - 2017/06/22 =
* Improvement: Code quality of the whole plugin has been improved to fit more WordPress coding standards.
* Improvement: Lots of internationalization improvements. Now the plugin's internationalization fully rely on the repository system.
* Bug Fix: Fixed an error with php 7.1: `Uncaught Error: [] operator not supported for strings in /wp-content/plugins/imagify/inc/functions/admin.php:134`.

= 1.6.4 - 2017/04/06 =
* Improvement: Provide a link to optimize in higher level when an image is already optimized.
* Improvement: Add a dedicated message for 413 HTTP error when the image is too big to be uploaded on our servers.

= 1.6.3 - 2016/12/16 =
* Improvement: The discount is now automatically applied in when you buy from the plugin and a promotion is active

= 1.6.2 - 2016/11/22 =
* Bug Fix: Correctly display the modal when clicking on the plan suggestion button on bulk optimization page

= 1.6.1 - 2016/11/22 =
* Bug Fix: Better offer suggestion when your medias library is bigger than 3GB

= 1.6 - 2016/11/21 =
* New: Knowing how many MB/GB you need to optimize your existing and future images is complicated. We love to make things easier, so Imagify will do it and advise you the best plan.
* New: You can now buy all the plans without leaving your WordPress administration
* Improvement: Some styles fixed in the interface

= 1.5.10 - 2016/10/05 =
* Improvement: Set to 1 the Bulk buffer size when there are more than 10 thumbnails to avoid "Unknown error" on the Bulk Optimization

= 1.5.9 - 2016/09/27 =
* Bug Fix: Don't delete the thumbnail when the maximum file size is set to one of the thumbnail size
* Bug Fix: Don't strip the image meta data if possible (only with Imagick)
* Bug Fix: Fix persistent "WELL DONE" message because of "original_size" meta value was 0

= 1.5.8 - 2016/08/24 =
* Regression fix: Check if the backup option is active before doing a backup when an image is resized

= 1.5.7 - 2016/08/23 =
* Improvement: Resize images bigger than the maximum width defined in the settings using WP Image Editor instead of Imagify API

= 1.5.6 - 2016/07/29 =
* Improvement: Dynamically update from the API the maximum image size allowed in bulk optimization
* Improvement: Updated SweetAlert to SweetAlert2

= 1.5.5 =
* Bug Fix: Fix issue with "original_size" at 0 in "_imagify_data" to be able to re-optimize an image with a "Forbidden" error.

= 1.5.4 =
* Improvement: Increase to 4 the number of parallel queries during a bulk optimization
* Improvement: Don't display Intercom chat if the user turned off the option in the web app

= 1.5.3 =
* Regression Fix: Display the Original File size in "View Details" section

= 1.5.2.1 =
* Bug Fix: Fix JS error: Uncaught ReferenceError: imagify is not defined in /assets/options.min.js
* Bug Fix: Don't show "Optimize" button during optimizing process in "Edit Media" screen

= 1.5.1 =
* Bug Fix: Thumbnail sizes in settings page aren't reset anymore on plugin update
* Bug Fix: Fix PHP Warning: Cannot unset offset in a non-array variable in /inc/functions/admin-stats.php on line 23
* Bug Fix: Fix PHP Warning: Invalid argument supplied for foreach() in /inc/functions/admin-stats.php on line 233

= 1.5 =
* New: NextGen Gallery compatibility - Optimize all your images uploaded with NextGen Gallery
* New: Asynchronous Optimization - No more latency when you upload new images, Imagify will optimize them in background!
* Improvement: Bulk Optimization: Interface improvements for a better experience

= 1.4.7 =
* Bug Fix: Fix issue between Bulk Optimization & WP Engine. The query to get unoptimized images is limited to 2500 images to be able to use the Bulk Optimization on this hosting.
* Bug Fix: Fix SSL certificate problem: unable to get local issuer certificate

= 1.4.6 =
* Bug Fix: Fix the "All your images have been optimized by Imagify" issue when images still need to be optimized. This issue occurred only since 1.4.5 for some users. Sorry for the inconvenience!

= 1.4.5 =
* Improvement: Bulk Optimization: optimize all SQL queries and improve by 65% the process time \o/
* Improvement: Chart.js library updated
* Improvement: Media List JS notice removed

= 1.4.4 =
* Improvement: Visual fix: CSS prefixed in notices to avoid class conflicts
* Improvement: Visual fix: improve Imagify Notices CSS to avoid issue with WP Engine CSS
* Improvement: Medias: new "Compare Original VS Optimized" action link in grid view mode
* Improvement: Settings: new sample images for visual comparison of compression levels (removes unused sample images)

= 1.4.3 =
* New: Medias: new "Compare Original VS Optimized" action link in list view
* Improvement: Visual fix: CSS prefixed in notices to avoid class conflicts
* Improvement: Medias: comparison are now available for image from 36Opx wide
* Improvement: Settings: new sample images for visual comparison of compression levels

= 1.4.2 =
* New: Add German translation
* New: You can define the `IMAGIFY_HIDDEN_ACCOUNT` constant in wp-config.php to hide all your Imagify account infos in the Admin Bar and Bulk Optimization
* Bug Fix: Fix PHP Notice: Undefined index original_size in /inc/functions/admin-stats.php on line 185
* Bug Fix: Fix PHP Notice: Undefined index optimized_size in /inc/functions/admin-stats.php on line 186

= 1.4.1 =
* Improvement: Medias: better comparison for big portrait images
* Improvement: Medias: Don't display the "Compare Original VS Optimized" button for images without backup
* Bug Fix: WPML: Fix AJAX error caused by WPML to avoid issue during the API key validation process
* Bug Fix: Yoast: Remove JS error caused by Yoast SEO on the attachment edit screen to avoid issue with our "Compare Original VS Optimized"

= 1.4 =
* New: Medias: Click a button to open images comparison between Original and Optimized (available for big enough images)
* Improvement: Add async method to optimize resized images

= 1.3.6 =
* Improvement: Optimize attachments resized with the WordPress editor tool
* Improvement: Compatibility with the "Replace the file, use new file name and update all links" option from "Enable Media Replace" plugin
* Improvement: Add a notice message during the Bulk Optimization if the quota is consumed
* Improvement: Better styles for compression details next to your images
* Bug Fix: No freeze anymore during the Bulk Optimization if an unknown error occurred with an image
* Bug Fix: Add a notice message if we can't get all unoptimized images during the Bulk Optimization process
* Bug Fix: Fix PHP Warning: set_time_limit(): Cannot set time limit in safe mode in ../inc/admin/ajax.php on line 137
* Bug Fix: Details about compressed images in modal media box are now closed by default
* Regression Fix: Get all attachments with the message "You've consumed all your data" during the Bulk Optimization process to be able to optimize them

= 1.3.5.2 =
* Regression Fix: Check mark displayed better on certain settings pages

= 1.3.5 =
* Bug Fix: Check box display issue fixed on Imagify settings page: SVG Icons cleaning

= 1.3.4 =
* New: Add Italian translation

= 1.3.3 =
* Bug Fix: Fixed behavior in multisite networks where Imagify options would not get saved when the plugin wasn't network-activated, but only activated for specific sites within the network.

= 1.3.2 =
* New: Add Spanish translation
* Bug Fix: Avoid lack of performance in the WordPress administration if the Imagify's servers are down.

= 1.3.1 =
* Bug Fix: Remove a notice message which causes a lack of performance in the administration. (thanks Kevin Gauthier to warn us)

= 1.3 =
* New: Add GIF support
* New: You can now decide to keep EXIF data on your images

= 1.2.4 =
* Bug Fix: Don't duplicate Imagify data in the attachment edit screen (wp-admin/post.php)

= 1.2.3 =
* Improvement: Use AJAX to display the quota in the admin bar to avoid a call to our API on each pages.

= 1.2.2 =
* Bug Fix: Bulk Optimization: Fix issue when the backup option isn't activated. The compression level applied was "Normal" instead the one saved in the settings.
* Bug Fix: Bulk Optimization: Don't try to re-optimize an image already optimized which has the same compression level than the one saved in the settings.

= 1.2.1 =
* Regression fix: Fix the Bulk Optimization issue when you never optimized any images and avoid the message "All your images have been optimized by Imagify. Congratulations!".

= 1.2 =
* New: compression level: Ultra
* New: You can now choose to display Admin Bar Imagify's menu, or not.
* New: See the differences between Ultra, Aggressive and Normal option inside Imagify Options page.
* Bug Fix: Admin Bar: Styles are now included in front-end too.
* Bug Fix: Admin Bar: Better styles in certain cases.
* Bug Fix: Deactivate a conflict plugin doesn't return a blank page anymore!
* Bug Fix: Display the right original image size after a resize (meta data)
* Regression Fix: Bulk Optimization: update in live the unconsumed credit during a bulk optimization.

= 1.1.6 =
* Improvement: Quick access to your profile informations (quota) in Admin Bar > Imagify
* Improvement: More precise information about global size saved using Imagify (bulk optimization page)
* Improvement: When your bulk optimization is over, success message isn't inside the table anymore
* Improvement: To quit the bulk optimization processing you have to confirm your action
* Bug Fix: JS: `console` undefined on some IE browsers
* Bug Fix: PHP Warning: `Illegal string offset 'sizes' in ../inc/functions/admin-stats.php on line 180`
* Bug Fix: Don't count GIF & SVG in the Imagify statistics

= 1.1.5 =
* Improvement: Display a default preview to avoid issues with 404 images and a security restriction on SSL websites on the Bulk Optimization page
* Improvement: Don't count all exceeded images to avoid lack of speed on the Bulk Optimization page
* Bug Fix: Don't try to re-optimize images with an empty error message or with an already optimized message on the Bulk Optimization
* Bug Fix: Don't generate special chars in the password to avoid issue on the Imagify app log in

= 1.1.4 =
* Improvement: Don't add the WP Rocket ads if this plugin is activated
* Bug Fix: Ignore thumbnails with infinite width like 9999 to avoid an issue with the "Resize larger images" option

= 1.1.3 =
* Bug Fix: Fix PHP Warning: `curl_setopt() [function.curl-setopt]: CURLOPT_FOLLOWLOCATION cannot be activated when safe_mode is enabled or an open_basedir is set in ../inc/api/imagify.php on line 218`

= 1.1.2 =
* Regression fix: Fix the "%undefined%" and the overview chart issues on the Bulk Optimization page
* Regression fix: Fix PHP Warning: Illegal string offset 'sizes' in ../inc/classes/class-attachment.php on line 347
* Regression fix: Fix PHP Notice: Uninitialized string offset: 0 in ../inc/classes/class-attachment.php on line 347
* Regression fix: Fix PHP Warning: Illegal string offset 'file' in ../inc/classes/class-attachment.php on line 410

= 1.1.1 =
* New: Add a notice on the Bulk Optimization & Imagify Settings page when the monthly free quota is consumed
* Bug Fix: Fix issue on Chrome & Opera on the Bulk Optimization: images are optimized from the newest to the oldest.

= 1.1 =
* New: Add new option "Resize larger Images"
* Improvement: Bulk optimization: results table is not shrinkable to the infinite anymore (scrollable)
* Improvement: Better visual in options page
* Bug Fix: Check if an attachment exists to avoid an issue which is stopped the Bulk Optimization
* Bug Fix: Really Fix PHP Notice: Undefined offset: 1 in imagify/inc/functions/formatting.php on line 17
* Bug Fix: Double animation in Progress Bar

= 1.0.3 =
* Bug Fix: Fix PHP Notice: Undefined offset: 1 in ../inc/functions/formatting.php on line 16

= 1.0.2 =
* Improvement: Add error descriptions on the Bulk Optimization results
* Improvement: Add a notice to switch to the list view in the media library page

= 1.0.1 =
* New: Add Intercom Live Chat on Imagify Settings and Bulk Optimization pages
* Improvement: Better user informations
* Bug Fix: PHP 5.2+ compatibility

= 1.0 =
* Initial release.
