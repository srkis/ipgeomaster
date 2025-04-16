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

function ip_geomaster_ban_ip_settings_page() {

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

    
    <div class="col-md-5 mt-5 mb-5">
         
    <div class="container-fluid mt-5">
        <h1>IP Address Settings</h1>
        <p>Manage the IP addresses that are allowed or banned from accessing your site.</p>
        <div class="form-group col-md-5 mt-5 mb-5">
             <label for="botBlockingSwitch" class="d-block">Turn On / Off Blocking IPs</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="ipsBlockingSwitch">
            <label class="custom-control-label" for="ipsBlockingSwitch">Blocking IPs: <span id="switchStatus">Off</span></label>
        </div>

        <div class="mt-5 mb-5">
        <div class="form-outline">
             <input type="text" id="banned_ips_msg" class="form-control" placeholder=" " required />
             <label class="form-label" for="banned_ips_msg">Message</label>
        </div>
        </div>
    </div>

        <div class="ip-geomaster-panel p-3" id="ip-geomaster-mainPanel">
        <div class="ip-geomaster-panel-header">Banned IPs</div>
        <div class="ip-geomaster-panel-filters">
            <select class="form-control">
                <option disabled value="all">All</option>
                <option disabled value="temporary">Temporary</option>
                <option value="permanent">Permanent</option>
            </select>
            <div class="ip-geomaster-search-container">
                <i class="fas fa-search"></i>
                <input id="ip-geomaster-search" type="text" class="form-control" placeholder="Search...">
            </div>
        </div>
        <div class="ip-geomaster-panel-subheader">
            <div>IP</div>
            <div>NOTES</div>
        </div>
        <div class="ip-geomaster-panel-body">

        </div>
        <div class="ip-geomaster-panel-footer">
            <button class="btn btn-primary" id="ip-geomaster-addBanBtn">Add Ban</button>
            <button class="btn btn-secondary" id="ip-geomaster-addManyBtn">Add Many</button>
        </div>


        <div class="container mt-4">
            <p class="alert alert-warning">
                <span class="glyphicon glyphicon-alert"></span>
                <b>Total IP Address Ban:</b>
                <b><span id="totalIpBan">0</span></b>
            </p>
        </div>

    </div>

    <!-- Add Ban Panel -->
    <div class="ip-geomaster-panel p-3 ip-geomaster-hidden" id="ip-geomaster-addBanPanel">
        <div class="ip-geomaster-panel-header">Add Banned IP</div>
        <div class="form-group">
            <label>IP Address</label>
            <input type="text" class="form-control" id="ip-geomaster-newIp" placeholder="The IP address to ban.">
        </div>
        <div class="form-group">
            <label>Notes</label>
            <textarea id="ip-geomaster-newNotes" class="form-control custom-textarea" placeholder="A comment describing the ban." rows="3"></textarea>
            
        </div>
        <div class="ip-geomaster-panel-footer">
            <button class="btn btn-secondary" id="ip-geomaster-cancelBtn">Cancel</button>
            <a href="#" onclick="ipGeomasterBanIp(event)" id="ip-geomaster-saveBtn" class="btn btn-raised btn-info save-bots">Save<div class="ripple-container"></div></a>
           
        </div>
    </div>


      <!-- Add Ban Panel -->
      <div class="ip-geomaster-panel p-3 ip-geomaster-hidden" id="ip-geomaster-addManyPanel">
        <div class="ip-geomaster-panel-header">Add Many IPs to Ban</div>
        <div class="form-group">
            <small>Enter one IP address per-line. Optionally, include a note by ending the line with a # sign.</small>
        </div>
        <div class="form-group">
            <label>IPs</label>
            <textarea id="ip-geomaster-banmanyips" class="form-control custom-textarea" placeholder="127.0.0.1 #This is my note" rows="5"></textarea>
        </div>
        <div class="ip-geomaster-panel-footer">
            <button class="btn btn-secondary" id="ip-geomaster-cancelManyBtn">Cancel</button>
            <a href="#" onclick="ipGeomasterBanManyIps(event)" id="ip-geomaster-saveManyBtn" class="btn btn-raised btn-info save-bots">Save<div class="ripple-container"></div></a>
           
        </div>
    </div>


    <!-- IP Details Panel -->
<div class="ip-geomaster-panel p-3 ip-geomaster-hidden" id="ip-geomaster-ipDetailsPanel">
    <div class="ip-geomaster-panel-header">IP Details</div>

    <div class="form-group">
        <label>IP Address</label>
        <div id="ip-geomaster-detail-ip" class="form-control"></div>
    </div>

    <div class="form-group">
        <label>Time</label>
        <div id="ip-geomaster-detail-time" class="form-control"></div>
    </div>

    <div class="form-group">
        <label>Source</label>
        <div id="ip-geomaster-detail-source" class="form-control"></div>
    </div>

    <div class="ip-geomaster-panel-footer">
        <button class="btn btn-secondary" id="ip-geomaster-backBtn">Back</button>
       
        <a href="#" onclick="ipGeoMasterRemoveBannedIp(document.getElementById('ip-geomaster-detail-ip').innerText)" 
   class="btn btn-danger" id="ip-geomaster-removeBanBtn">Remove Ban</a>
        
        <div>
          <a href="#" id="ip-geomaster-viewActivityBtn" class="view-activity-btn">View Activity</a>
        </div>
    </div>
</div>

    <div class="col-lg-10 mt-5" style="padding-left:0px">
            <a href="#" onclick="ipGeomasterIpsMode(event)" id="ipGeomasterIpsMode" class="btn btn-raised btn-info save-bots">Save Changes<div class="ripple-container"></div></a>
        </div>

    </div>
    <?php echo wp_nonce_field( esc_attr( "ip_geomaster_nonce_action" ), esc_attr( "ip_geomaster_nonce_field" ), true, false ); ?>
</div>


    </div>

</body>
</html>

<script>
        
  jQuery(document).ready(function() {
    jQuery("#ip-geomaster-addBanBtn").click(function() {
        jQuery("#ip-geomaster-addBanPanel").removeClass("ip-geomaster-hidden"); 
        jQuery("#ip-geomaster-mainPanel").addClass("ip-geomaster-hidden"); 
    });

    jQuery("#ip-geomaster-cancelBtn").click(function() {
        jQuery("#ip-geomaster-addBanPanel").addClass("ip-geomaster-hidden"); 
        jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden"); 
    });

    jQuery("#ip-geomaster-saveBtn").click(function() {
        var newIp = jQuery("#ip-geomaster-newIp").val().trim();
        var newNotes = jQuery("#ip-geomaster-newNotes").val().trim();
        
        if (newIp !== "" && newNotes !== "") {
            jQuery(".ip-geomaster-panel-body").append(
                `<div class="ip-geomaster-ip-row">
                    <div class="ip-geomaster-ip">${newIp}<br><small>${new Date().toLocaleString()}</small></div>
                    <div class="ip-geomaster-notes">${newNotes}</div>
                </div>`
            );
            
            jQuery("#ip-geomaster-addBanPanel").addClass("ip-geomaster-hidden"); 
            jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden"); 
            jQuery("#ip-geomaster-newIp, #ip-geomaster-newNotes").val(""); 
        }
    }); 


    jQuery("#ip-geomaster-addManyBtn").click(function() {
        jQuery("#ip-geomaster-addManyPanel").removeClass("ip-geomaster-hidden");
        jQuery("#ip-geomaster-mainPanel").addClass("ip-geomaster-hidden"); 
    });


    jQuery("#ip-geomaster-cancelManyBtn").click(function() {
        jQuery("#ip-geomaster-addManyPanel").addClass("ip-geomaster-hidden");
        jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden"); 
    });

  
});



jQuery(document).ready(function() {
    // Klik na IP adresu da otvori detalje
    jQuery(".ip-geomaster-panel-body").on("click", ".ip-geomaster-ip-row", function() {
        let ipAddress = jQuery(this).find(".ip-geomaster-ip").contents().first().text().trim();
        let time = jQuery(this).find(".ip-geomaster-ip small").text().trim();
        let source = "ipgeomaster"; 

        jQuery("#ip-geomaster-detail-ip").text(ipAddress);
        jQuery("#ip-geomaster-detail-time").text(time);
        jQuery("#ip-geomaster-detail-source").text(source);

        jQuery("#ip-geomaster-mainPanel").addClass("ip-geomaster-hidden");
        jQuery("#ip-geomaster-ipDetailsPanel").removeClass("ip-geomaster-hidden");
    });

    // Dugme "Back" vraća na glavni panel
    jQuery("#ip-geomaster-backBtn").click(function() {
        jQuery("#ip-geomaster-ipDetailsPanel").addClass("ip-geomaster-hidden");
        jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden");
    });

    // Dugme "Remove Ban" briše IP iz liste
    jQuery("#ip-geomaster-removeBanBtn").click(function() {
        let ipToRemove = jQuery("#ip-geomaster-detail-ip").text();

        jQuery(".ip-geomaster-ip-row").each(function() {
            if (jQuery(this).find(".ip-geomaster-ip").contents().first().text().trim() === ipToRemove) {
                jQuery(this).remove();
            }
        });

        jQuery("#ip-geomaster-ipDetailsPanel").addClass("ip-geomaster-hidden");
        jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden");
    });
});



    </script>

    <?php
}
