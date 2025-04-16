<?php
/**
 * Provide a admin-facing view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ipgeomaster.icodes.rocks
 * @since      1.0.0
 *
 * @package    IP_Geomaster
 * @subpackage IP_Geomaster/admin/partials
 */

function ip_geomaster_admin_display() {

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Geomaster Plugin</title>
</head>
<body>
    <div class="container-fluid mt-5">
    
      <div class="plugin-header">

      <div class="logo-container" style="text-align: left;">

      <img src="<?php echo esc_url(IP_GEOMASTER_ROOT_URL . 'admin/images/ip_geomaster_logo-1.png'); ?>" alt="IP Geomaster Logo" class="img-fluid" style="max-width: 200px;">
    
    </div>

      <h1 style="color: #007bff" class="text-center">IP Geomaster</h1>
            <p class="text-left">
            <a href="https://ipgeomaster.icodes.rocks/"> IP Geomaster </a> is a <strong>lightweight yet powerful</strong> WordPress plugin designed to block access to your website from specific countries, IP addresses, and malicious bot traffic.
                With this plugin, you can effortlessly manage which countries, IP addresses, and bots are allowed or banned from accessing your site, providing you with full control over your website's security, performance, and overall accessibility.
                Having control over malicious bots from different countries and specific IP addresses is crucial for maintaining a safe and efficient website. By blocking unwanted traffic, you can significantly reduce the risk of attacks, ensure faster loading speeds, and monitor threats in real-time. This enhances not only your website's security but also its performance and user experience.
                Stay protected, optimize your site's performance, and gain peace of mind with comprehensive monitoring and control through IP Geomaster.
            </p>

            <div>

                <p id="donate-text">If you like this plugin, consider supporting its development:</p>
             <a id="paypall-donate" href="https://www.paypal.me/cybersyntax" class="button button-secondary" target="_blank"><img src="<?php echo esc_url(IP_GEOMASTER_ROOT_URL . 'admin/images/button-donate-paypal.png'); ?>" alt="Donate With Paypall" class="img-fluid" style="max-width: 200px;"></a>
            </div>

        </div>

        <div class="mt-5 mb-5">
             <h1>Countries Settings</h1>
            <p>Manage the Countries that are allowed or banned from accessing your site.</p>
        </div>
        
        <div class="mt-5 mb-5">
        <div class="form-outline">
             <input type="text" id="banned_countries_msg" class="form-control" placeholder=" " required />
             <label class="form-label" for="banned_countries_msg">Message</label>
        </div>
        </div>

        <div class="row mt-4">
            <!-- Available Countries -->
            <div class="col-md-6">
                <div class="box-header">
                    <h3>Available Countries</h3>
                    <input type="text" id="available-search" class="search-input" placeholder="Search available countries...">
                </div>
                <div class="box" id="available-countries">
                    <div id="country-list">
                        <!-- Countries will be added dinamically -->
                    </div>
                </div>
            </div>

            <!-- Banned Countries -->
            <div class="col-md-6">
                <div class="box-header">
                    <h3>Banned Countries</h3>
                    <input type="text" id="banned-search" class="search-input" placeholder="Search banned countries...">
                </div>
                <div class="box" id="banned-countries">
                    <div id="banned-list">
                        <!-- Banned countries will be dinamically populated here -->
                    </div>
                </div>
            </div>
        </div>
        <?php echo wp_nonce_field( esc_attr( "ip_geomaster_nonce_action" ), esc_attr( "ip_geomaster_nonce_field" ), true, false ); ?>

    </div>

    <div class="container-fluid mt-5">
    <div class="form-group row justify-content-start">
        <div class="col-lg-10">
            <a href="#" onclick="ipGeomasterBlockCountries(event)" id="ipGeomasterBlockCountries" class="btn btn-raised btn-info save-countries">Save Changes<div class="ripple-container"></div></a>
        </div>
    </div>
    </div>


</body>
</html>

    <?php
}