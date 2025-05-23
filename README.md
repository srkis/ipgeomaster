=== IP Geomaster ===
Contributors: srkimafia  
Donate link: https://www.paypal.me/cybersyntax  
Tags: geo block, country blocker, ip blocker, spam protection, security  
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 1.0.0 
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Block access from specific countries, IPs, and malicious bots to improve security, performance, and control over your WordPress site.

== Description ==

**IP Geomaster** is a lightweight yet powerful WordPress plugin designed to block access to your website from specific countries, IP addresses, and malicious bot traffic.

With a clean interface and advanced filtering options, you can effortlessly manage which countries, IP addresses, and bots are allowed or banned from accessing your site. This ensures better protection against spam, brute-force attacks, data scraping, and other unwanted behaviors.

Whether you want to protect your login pages, admin dashboard, or entire site, IP Geomaster gives you complete control over your site's accessibility and performance.

**Key Benefits:**

* Enhanced security by blocking known threats  
* Reduce server load by filtering malicious traffic  
* Real-time monitoring and logging of blocked attempts  
* Faster site load time by eliminating spam bots and crawlers  

== Features ==

* Block visitors by **country**  
* Block or whitelist specific **IP addresses**  
* Block known **malicious bots and crawlers**  
* Custom messages for blocked users  
* Real-time **logging** and monitoring  
* Lightweight and optimized for performance  
* GDPR-compliant, with no third-party services  
* User-friendly admin interface  
* Easily remove or add IPs/countries via dashboard  

== Installation ==

== via WordPress.org ==

For users who want to simply use the plugin, you can download and install it directly from WordPress.org without the need for Composer:

1. Visit IP Geomaster on WordPress.org.

2. Download and install the plugin as you would with any other WordPress plugin.

3. Activate the plugin through the WordPress plugin manager and configure it under
 **Settings → IP Geomaster**.

4. Start adding countries, IPs, or bots to your block/allow list.  


=== Using Composer (for developers) ===

If you want to develop or use the plugin directly from GitHub, follow these steps:

1. Clone this repository:

   git clone https://github.com/srkis/ipgeomaster.git

2. Install dependencies using Composer:

   cd ip-geomaster
   composer install

== Frequently Asked Questions ==

= Will this plugin slow down my website? =  
No, IP Geomaster is built with performance in mind and uses efficient methods to block traffic without affecting your site speed.

= Can I block only bots without affecting real users? =  
Yes, the plugin has a built-in bot detection system that allows you to block known malicious bots while allowing real visitors.

= Does it work with caching plugins? =  
Yes, it is compatible with popular caching plugins such as WP Super Cache, W3 Total Cache, and LiteSpeed Cache.

= How accurate is the country-based blocking? =  
The plugin uses up-to-date geo IP databases to ensure high accuracy in identifying user locations.

= Can I track blocked countries or bots? =  
Currently, the plugin blocks users based on their IP geolocation and bot detection in real-time, but there is no specific logging feature for blocked users. This could be added in future updates.

= Is this plugin GDPR compliant? =  
Yes. IP Geomaster does not store or transmit any personal data to third-party services. All geolocation checks happen locally.

== Screenshots ==

1. Admin interface to block countries and IP addresses.  
2. Real-time log of blocked attempts.  
3. Dashboard widget showing threat activity.  
4. Settings page for customizing block behavior.  


== Resources ==

This plugin includes the following third-party libraries:

1. **Bootstrap v5.3.5**
   - Minified file used: `admin/js/bootstrap.min.js`
   - Original uncompressed source: https://github.com/twbs/bootstrap/blob/v5.3.5/dist/js/bootstrap.js

2. **Popper.js v2.11.8**
   - Minified file used: `admin/js/popper.min.js`
   - Original uncompressed source: https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.js
   - Project repository: https://github.com/floating-ui/floating-ui

3. **jquery-toast-plugin**
   - Minified file used: `admin/js/jquery.toast.min.js`
   - Original uncompressed source: https://github.com/kamranahmedse/jquery-toast-plugin/blob/master/src/jquery.toast.js

4. **Bootstrap CSS v5.3.5**
   - Minified file used: `admin/css/bootstrap.min.css`
   - Original uncompressed source: https://github.com/twbs/bootstrap/blob/v5.3.5/dist/css/bootstrap.css

5. **jquery-toast-plugin CSS**
   - Minified file used: `admin/css/jquery.toast.min.css`
   - Original uncompressed source: https://github.com/kamranahmedse/jquery-toast-plugin/blob/master/src/jquery.toast.css



All minified files are used for performance in production, while the original uncompressed source files are included or publicly accessible for review, as per WordPress plugin repository guidelines.


== Changelog ==

= 1.0.0 - 2024-04-30 =
* Initial release.  
* Added functionality for country-based blocking.  
* Pre-loaded with 950 bad bots and 50 good bots.  
* Ability to manage bot lists and customize allowed/blocked bots.  
* Real-time blocking and optimized performance
* Optimized for speed and performance.  

== Upgrade Notice ==

= 1.0 =  
First release of IP Geomaster with full IP and geo-blocking functionality.  

== Support ==

If you need help with the plugin or have any questions, please contact us via the [support page](https://www.ipgeomaster.com/support).  

== Contact ==

For more information, visit our website: [IPGeomaster](https://www.ipgeomaster.com)
