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

function ip_geomaster_bots() {

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

        <div class="plugin-header">

            Below, you will find lists of allowed (good) and banned (bad) bots. Bad bots are automatically blocked, while good bots are permitted to access your site.
            If you want to move a bot from the allowed list to the banned list, simply click on it and use the arrow to transfer it. The same applies if you wish to move a banned bot to the allowed list. You can also remove bots if needed.
            Please note that the total number of bots (both allowed and banned) cannot exceed 1,000.
        </div>

    
    <div class="form-group col-md-5 mt-5 mb-5">
             <label for="botBlockingSwitch" class="d-block">Turn On / Off Blocking Bots</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="botBlockingSwitch">
            <label class="custom-control-label" for="botBlockingSwitch">Blocking Bots: <span id="switchStatus">Off</span></label>
        </div>


        <div class="mt-5 mb-5">
        <div class="form-outline">
             <input type="text" id="banned_bots_msg" class="form-control" placeholder=" " required />
             <label class="form-label" for="banned_bots_msg">Message</label>
        </div>
        </div>
    </div>

    <div class="row mt-4 mt-5">
    <div class="col-md-5">
        <div class="box-header">
            <h3>
                Allowed (Good) Bots
            <span class="dashicons dashicons-info" 
                data-bs-toggle="popover" 
                data-bs-placement="right" 
                data-bs-trigger="focus"
                title="Good Bots"
                data-bs-content="Good bots are automated programs or web crawlers that perform beneficial and legitimate tasks on the internet. Good bots serve helpful purposes such as indexing web pages, monitoring website health, and assisting in digital marketing."
                tabindex="0" 
                style="cursor: pointer;">
            </span>

            </h3>
            
        <div id="allowed-bot-actions" class="mt-2">
            <div class="input-group" style="display: flex; gap: 10px;">
                <input type="text" id="allowed-bot-name" class="form-control" placeholder="Search / Add / Remove Good Bot">
                <div class="input-group-append" style="display: flex; gap: 10px;">
                    <button class="btn btn-success" id="add-allowed-bot">Add</button>
                    <button class="btn btn-danger" id="remove-allowed-bot">Remove</button>
                </div>
            </div>
        </div>
            
        </div>
        <div class="box" id="available-bots">
            <div id="bots-list">
                <!-- bots will be added dynamically -->
            </div>
        </div>
    </div>

    <div class="col-md-2 arrow-container">
        <button class="modern-button" id="move-bots">
        <span class="modern-button-arrow">&#8592;</span> <span> Transfer Bots </span> <span class="modern-button-arrow">&#8594;</span>
    </button>
            
    </div>

    <div class="col-md-5">
        <div class="box-header">
            <h3>Banned (Bad) Bots
            <span class="dashicons dashicons-info"
                data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-trigger="focus"
                title="Bad Bots"
                data-bs-content="Bad bots are automated programs that perform malicious or unwanted activities on websites, such as scraping content, spamming, launching DDoS attacks, or attempting unauthorized access. Unlike good bots, they violate website policies, consume server resources, and can pose security threats."
                tabindex="0"
                style="cursor: pointer;">
            </span>

            </h3>
                <div id="banned-bot-actions" class="mt-2">
                    <div class="input-group" style="display: flex; gap: 10px;">
                        <input type="text" id="banned-bot-name" class="form-control" placeholder="Search / Add / Remove Bad Bot" style="flex: 1;">
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-success" id="add-banned-bot">Add</button>
                            <button class="btn btn-danger" id="remove-banned-bot">Remove</button>
                        </div>
                    </div>
               </div>
        </div>

        <div class="box" id="banned-bots">
            <div id="banned-list">
                <!-- banned bots will be dynamically added here -->
            </div>
        </div>
    </div>
</div>
<?php wp_nonce_field( 'ip_geomaster_nonce_action', 'ip_geomaster_nonce_field', true, false ); ?>

    </div>


    <div id="total-bots-text" class="container-fluid mt-2">
    <div class="form-group row justify-content-start">
        <div class="col-md-4">
        <p>Number of good bots: <span id="number_good_bots"> 0 </span></p>
           
        </div>
        <div class="col-md-4">
        <p>Total number of bots (good + bad) can NOT be greater then 1000. Total: <span id="number_total_bots" >1000</span></p>
           
        </div>
        <div class="col-md-4">
        <p>Number of bad bots: <span id="number_bad_bots"> 0 </span> </p>
           
        </div>
    </div>
    </div>


    <div class="container-fluid mt-5">
    <div class="form-group row justify-content-start">
        <div class="col-lg-10">
            <a href="#" onclick="ipGeomasterBlockBots(event)" id="ipGeomasterBlockCountries" class="btn btn-raised btn-info save-bots">Save Changes<div class="ripple-container"></div></a>
        </div>
    </div>
    </div>


</body>
</html>

<script>

jQuery(document).ready(function(){
			  
		  });

</script>

    <?php
}