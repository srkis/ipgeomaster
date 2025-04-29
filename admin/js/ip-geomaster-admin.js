(function( $ ) {
	'use strict';

	$(document).ready(function() {
	
		// Drag and drop functionality
		function handleDragStart(event) {
			event.originalEvent.dataTransfer.setData('text/plain', event.target.getAttribute('data-country'));
		}
	
		function handleDragOver(event) {
			event.preventDefault();
		}
	
		function handleDrop(event, targetList) {
			event.preventDefault();
			var countryCode = event.originalEvent.dataTransfer.getData('text/plain');
			var countryItem = $(`[data-country="${countryCode}"]`).detach();
			$(targetList).append(countryItem);
		}

		$('.country-item').on('dragstart', handleDragStart);
	
		$('#available-countries').on('dragover', handleDragOver);
		$('#banned-countries').on('dragover', handleDragOver);
	
		$('#available-countries').on('drop', function(event) {
			handleDrop(event, '#country-list');
		});
	
		$('#banned-countries').on('drop', function(event) {
			handleDrop(event, '#banned-list');
		});
	
		// Click functionality
		$('.box').on('click', '.country-item', function() {
			var countryItem = $(this); 
			var parentId = countryItem.parent().attr('id'); 
	
			if (parentId === 'country-list') {
				$('#banned-list').append(countryItem);
			} else if (parentId === 'banned-list') {
				$('#country-list').append(countryItem);
			}
		});
		
		// Search functionality
		$('#available-search').on('input', function() {
			var searchTerm = $(this).val().toLowerCase();
			$('#country-list .country-item').each(function() {
				var countryName = $(this).text().toLowerCase();
				if (countryName.includes(searchTerm)) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		});
	
		$('#banned-search').on('input', function() {
			var searchTerm = $(this).val().toLowerCase();
			$('#banned-list .country-item').each(function() {
				var countryName = $(this).text().toLowerCase();
				if (countryName.includes(searchTerm)) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		});


		// Search functionality for banned IPs
	$('#ip-geomaster-search').on('input', function() {
		var searchTerm = $(this).val().toLowerCase();
		$('.ip-geomaster-ip-row').each(function() {
			var ipText = $(this).find('.ip-geomaster-ip').text().toLowerCase();
			var notesText = $(this).find('.ip-geomaster-notes').text().toLowerCase();
			
			if (ipText.includes(searchTerm) || notesText.includes(searchTerm)) {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});



	let selectedBots = [];
	let lastClickedParent = null;

	// Check if bot is in list
	function isBotInList(botName, listId) {
		return $(`#${listId} .bot-item[data-bot="${botName}"]`).length > 0;
	}

	// Mark bot as selected
	$('.box').on('click', '.bot-item', function () {
		const botItem = $(this);
		const botName = botItem.attr('data-bot');
		lastClickedParent = botItem.closest('.box');

		if (botItem.hasClass('selected')) {
			botItem.removeClass('selected');
			selectedBots = selectedBots.filter(bot => bot !== botName);
		} else {
			botItem.addClass('selected');
			selectedBots.push(botName);
		}
	});

	// Function to count bots in each list
	function countBots() {
		const allowedCount = $('#bots-list .bot-item').length;
		const bannedCount = $('#banned-list .bot-item').length;

		$('#number_good_bots').text(allowedCount);
		$('#number_bad_bots').text(bannedCount);
	}

	// Transfer selected bots to the other list
	$('#move-bots').on('click', function () {
		selectedBots.forEach(botName => {
			const botItem = $(`[data-bot="${botName}"]`);
			const parentId = botItem.parent().attr('id');

			if (parentId === 'bots-list') {
				botItem.detach().prependTo('#banned-list').addClass('newly-added');
			} else if (parentId === 'banned-list') {
				botItem.detach().prependTo('#bots-list').addClass('newly-added');
			}

			botItem.removeClass('selected');
		});
		selectedBots = [];
		countBots();
	});

	// Adding bots in allowed list
	$('#add-allowed-bot').on('click', function () {
		$('#bots-list .bot-item').css('display', 'block');

		const botName = $('#allowed-bot-name').val().trim();
		if (botName) {
			if (isBotInList(botName, 'bots-list')) {
				showToast('Bot already exists in the Allowed list.', '', 'info');
			} else if (isBotInList(botName, 'banned-list')) {
				showToast('Bot already exists in the Banned list. Do you want to move it to Allowed?', '', 'info');
			} else {
				const newBot = $('<div class="bot-item"></div>').text(botName).attr('data-bot', botName);
				$('#bots-list').prepend(newBot);
				$('#allowed-bot-name').val('');
				countBots();
			}
		}
	});

	// Adding bots in banned list
	$('#add-banned-bot').on('click', function () {
		$('#banned-list .bot-item').css('display', 'block');

		const botName = $('#banned-bot-name').val().trim();
		if (botName) {
			if (isBotInList(botName, 'banned-list')) {
				showToast('Bot already exists in the Banned list.', '', 'error');
			} else if (isBotInList(botName, 'bots-list')) {
				showToast('Bot already exists in the Allowed list. Do you want to move it to Banned?', '', 'info');
			} else {
				const newBot = $('<div class="bot-item"></div>').text(botName).attr('data-bot', botName);
				$('#banned-list').prepend(newBot);
				$('#banned-bot-name').val('');
				countBots();
			}
		}
	});

	// Deleting selected bots from Allowed list
	$('#remove-allowed-bot').on('click', function () {
		selectedBots.forEach(botName => {
			$('#bots-list').find(`[data-bot="${botName}"]`).remove();
		});
		selectedBots = [];
		countBots();
	});

	// Deleting selected bots from Banned list
	$('#remove-banned-bot').on('click', function () {
		selectedBots.forEach(botName => {
			$('#banned-list').find(`[data-bot="${botName}"]`).remove();
		});
		selectedBots = [];
		countBots();
	});
		

	// Search functionality for allowed bots
	$('#allowed-bot-name').on('input', function() {
		var searchTerm = $(this).val().toLowerCase();
		$('#bots-list .bot-item').each(function() {
			var botName = $(this).text().toLowerCase();
			$(this).toggle(botName.includes(searchTerm));
		});
	});

	// Search functionality for banned bots
	$('#banned-bot-name').on('input', function() {
		var searchTerm = $(this).val().toLowerCase();
		$('#banned-list .bot-item').each(function() {
			var botName = $(this).text().toLowerCase();
			$(this).toggle(botName.includes(searchTerm));
		});
	});


	let params = {};
	let currentUrl = window.location.href;
	let url = new URL(currentUrl);
	let pageParam = url.searchParams.get('page');

	if (pageParam == 'ip-geomaster') {

		let params = {};
		let ip_geomaster_nonce_field = ip_geomaster_ajax.nonce;
		params.action = 'ip_geomaster_fetch_countries';
		params.ip_geomaster_nonce_field = ip_geomaster_nonce_field;
	
		// Fetch countries via AJAX
		jQuery.ajax({
			type: 'GET',
			dataType: "json",
			url: ip_geomaster_ajax.ajax_url,
			data: params,
		
			success: function(response) {
				if (response) {
		
					if (typeof response === 'object' && !Array.isArray(response)) {

						jQuery('#bots-list').empty();
						jQuery('#banned-list').empty();
						jQuery('#banned_countries_msg').val(response.country_msg);

						// Fill available countries
						Object.entries(response.available_countries).forEach(([code, name]) => {
							const countryItem = jQuery('<div>')
								.addClass('country-item')
								.attr('data-country', code)
								.text(name);
							jQuery('#country-list').append(countryItem);
						});
		
						// Fill banned countries
						Object.entries(response.banned_countries).forEach(([code, name]) => {
							const countryItem = jQuery('<div>')
								.addClass('country-item')
								.attr('data-country', code)
								.text(name);
							jQuery('#banned-list').append(countryItem);
						});

					} else {
						showToast('Error fetching data:' , response.data, 'error');
						
					}
				} else {
					showToast('Invalid response format.', 'Expected an object.', 'error');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				jQuery('#results-error').html('<p>Error fetching data: ' + error + '</p>');
			},
			complete: function() {
				console.log('Completed');
			}
		});

		}else if (pageParam === 'ip-geomaster-bots') {  // AJAX for ip-geomaster-bots page

			jQuery('#botBlockingSwitch').on('change', function() {
				updateSwitchState(this.checked ? "on" : "off", this.id);
			});

				// Fetch bots
				let ip_geomaster_nonce_field = ip_geomaster_ajax.nonce;
				params.action = 'ip_geomaster_fetch_bots';
				params.ip_geomaster_nonce_field = ip_geomaster_nonce_field;

				// Fetch countries via AJAX
				jQuery.ajax({
					type: 'GET',
					dataType: "json",
					url: ip_geomaster_ajax.ajax_url,
					data: params,
				
					success: function(response) {
						if (response) {

							let totalBots = response.allowed_bots.length + response.banned_bots.length;

							jQuery('#number_good_bots').text(response.allowed_bots.length);
							jQuery('#number_bad_bots').text(response.banned_bots.length);
							jQuery('#number_total_bots').text(totalBots);
							jQuery('#banned_bots_msg').val(response.bots_msg);
				
							if (typeof response === 'object' && !Array.isArray(response)) {
								// Clear existing lists
								jQuery('#bots-list').empty();
								jQuery('#banned-list').empty();

								let botsMode = response.bots_mode; 

								   updateSwitchState(botsMode, 'botBlockingSwitch');
														
								// Fill available bots
								response.allowed_bots.forEach((name) => {
									const botItem = jQuery('<div>')
										.addClass('bot-item')
										.attr('data-bot', name)
										.text(name);
									jQuery('#bots-list').append(botItem);
								});
							
								// Fill banned bots
								response.banned_bots.forEach((name) => {
									const botItem = jQuery('<div>')
										.addClass('bot-item')
										.attr('data-bot', name)
										.text(name);
									jQuery('#banned-list').append(botItem);
								});
							} else {
								showToast('Invalid response format.' ,'Expected an object.', 'error');
							}
						} else {
							showToast('Error fetching data:' , response.data, 'error');
						}
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', status, error);
						jQuery('#results-error').html('<p>Error fetching data: ' + error + '</p>');
					},
					complete: function() {
						console.log('Completed');
					}
				});

		}else if(pageParam === 'ip-geomaster-ban-ip') {

			let ip_geomaster_nonce_field = ip_geomaster_ajax.nonce;
				params.action = 'ip_geomaster_get_banned_ips';
				params.ip_geomaster_nonce_field = ip_geomaster_nonce_field;

				jQuery('#ipsBlockingSwitch').on('change', function() {
					updateSwitchState(this.checked ? "on" : "off", this.id);
				});
		

				jQuery.ajax({
					type: 'GET',
					dataType: "json",
					url: ip_geomaster_ajax.ajax_url,
					data: params,
				
					success: function(response) {

						jQuery('#banned_ips_msg').val(response.ips_msg);

						updateSwitchState(response.ips_mode, 'ipsBlockingSwitch');

						if (response.banned_ips) {

							let bannedCount = response.banned_ips.length;
							jQuery('#totalIpBan').text(bannedCount);

							jQuery(".ip-geomaster-panel-body").html( 
								response.banned_ips.map(ipData => `
									<div class="ip-geomaster-ip-row">
										<div class="ip-geomaster-ip">
											${ipData.ip}<br>
											<small>${ipData.date} ${ipData.time}</small>
										</div>
										<div class="ip-geomaster-notes">${ipData.notes}</div>
									</div>
								`).join('') 
							);
						} else {
							showToast('Error fetching data:',  response.data,  'error');
						}
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', status, error);
						jQuery('#results-error').html('<p>Error fetching data: ' + error + '</p>');
					},
					complete: function() {
						console.log('Completed');
					}
				});
			}

		});  

	})( jQuery );


	function ipGeomasterBlockBots(e) {

		e.preventDefault();

		let bannedBots = [];
		let allowed_bots = [];
		let isChecked = document.getElementById("botBlockingSwitch").checked;
		
    	let botsMode = isChecked ? "on" : "off";

		jQuery('#banned-list .bot-item').each(function() {
			bannedBots.push(jQuery(this).attr('data-bot'));
		});

		jQuery('#bots-list .bot-item').each(function() {
			allowed_bots.push(jQuery(this).attr('data-bot'));
		});
	
		if (bannedBots.length === 0) {
			return;
		}

		if (allowed_bots.length === 0) {
			return;
		}

		let totalBots = bannedBots.length + allowed_bots.length;

		if (totalBots > 1000) {
			showToast('Error saving setting!',  'You can only block up to 1000 bots.', 'error');
		}

		
		let params = {
			action: 'ip_geomaster_ban_bots',
			ip_geomaster_nonce_field: ip_geomaster_ajax.nonce,
			banned_bots: bannedBots,
			allowed_bots: allowed_bots,
			bots_mode: botsMode,
			bots_msg: jQuery('#banned_bots_msg').val()
		};

		jQuery.ajax({
			type: 'POST',
			dataType: "json",
			url: ip_geomaster_ajax.ajax_url,
			data: params,
			success: function(response) {
				if (response.success) {
					showToast('Settings saved! You have successfully updated the bots lists.', '', 'success');
				} else {
					showToast(response.data.message, '', 'error');
					
					jQuery('#results-error').html('<p>Error blocking bots: ' + response.data + '</p>');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				jQuery('#results-error').html('<p>Error blocking bots: ' + error + '</p>');
			},
			complete: function() {
				console.log('Completed');
			}
		});

	}

	function ipGeomasterBlockCountries(e) {
		// get banned countries
		let bannedCountries = [];
		jQuery('#banned-list .country-item').each(function() {
			bannedCountries.push(jQuery(this).attr('data-country')); 
		});
	
		
		if (bannedCountries.length === 0) {
			bannedCountries = []; 
		}
	
		//set params
		let params = {
			action: 'ip_geomaster_ban_countries', // Action hook
			ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
			banned_countries: bannedCountries, // Banned countries
			country_msg: jQuery('#banned_countries_msg').val()
		};
	
		// Send AJAX request
		jQuery.ajax({
			type: 'POST',
			dataType: "json",
			url: ip_geomaster_ajax.ajax_url,
			data: params, // data object with parameters
			success: function(response) {
				if (response) {
					showToast('Settings saved! You have successfully blocked the countries.', '', 'success');

				} else {
					showToast('Error blocking countries.', '', 'error');
					jQuery('#results-error').html('<p>Error blocking countries: ' + response.data + '</p>');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				jQuery('#results-error').html('<p>Error blocking countries: ' + error + '</p>');
			},
			complete: function() {
				console.log('Completed');
			}
		});
	}


		function ipGeomasterBanIp(e) {
			e.preventDefault();	

			let params = {
				action: 'ip_geomaster_ban_ip', // Action hook
				ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
				banned_ip: jQuery('#ip-geomaster-newIp').val() ,  //The IP address to ban.
				banned_ip_note: jQuery('#ip-geomaster-newNotes').val(),  //A comment describing the ban.
				
			};

			if (!validateRequest(params)) {
				showToast('Error', 'IP address and note are required!', 'error');
				return;
			}
			
			jQuery.ajax({
				type: 'POST',
				dataType: "json",
				url: ip_geomaster_ajax.ajax_url,
				data: params, // data object with parameters
				success: function(response) {
				
					if (response.success) {
						showToast('Settings saved!', 'You have successfully blocked IP addresses.', 'success');
	
					} else {
						showToast(response.data.msg, response.data.invalid_ip, 'error');
						
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					jQuery('#results-error').html('<p>Error blocking countries: ' + error + '</p>');
				},
				complete: function() {
					console.log('Completed');
				}
			});

		}


		function ipGeoMasterRemoveBannedIp(ipAddress) {

			let params = {
				action: 'ip_geomaster_remove_ban_ip', // Action hook
				ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
				banned_ip: ipAddress
				
			};

			if (!validateRequest(params)) {
				showToast('Error', 'IP address and note are required!', 'error');
				return;
			}

			jQuery.ajax({
				type: 'POST',
				dataType: "json",
				url: ip_geomaster_ajax.ajax_url,
				data: params, // data object with parameters
				success: function(response) {
				
					if (response.success) {
						showToast('Settings saved!', 'You have successfully remove IP address from ban list.', 'success');
	
					  let bannedCount = response.data.count;
					  jQuery('#totalIpBan').text(bannedCount);

					} else {
						showToast(response.data.msg, response.data.invalid_ip, 'error');
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					jQuery('#results-error').html('<p>Error blocking countries: ' + error + '</p>');
				},
				complete: function() {
					console.log('Completed');
				}
			});
		}


		function ipGeomasterIpsMode(e) {
			e.preventDefault();

			let isChecked = document.getElementById("ipsBlockingSwitch").checked;
		
			let ipsMode = isChecked ? "on" : "off";

			let params = {
				action: 'ip_geomaster_ban_ip', // Action hook
				ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
				banned_ips_msg: jQuery('#banned_ips_msg').val(),  //Show msg to banned user IP
				ips_mode: ipsMode,
			};

			jQuery.ajax({
				type: 'POST',
				dataType: "json",
				url: ip_geomaster_ajax.ajax_url,
				data: params, // data object with parameters
				success: function(response) {
				
					if (response.success) {
						showToast('Settings saved!', 'You have successfully updated data.', 'success');
	
					} else {
						showToast(response.data.msg, response.data.invalid_ip, 'error');
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					jQuery('#results-error').html('<p>Error blocking countries: ' + error + '</p>');
				},
				complete: function() {
					console.log('Completed');
				}
			});
		}


		function ipGeomasterBanManyIps(e) {
		e.preventDefault();

		let bannedIps = $("#ip-geomaster-banmanyips").val().split("\n").map(ip => ip.trim()).filter(ip => ip !== "");
       
		//set params
		let params = {
			action: 'ip_geomaster_ban_many_ips', // Action hook
			ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
			banned_ips: bannedIps, 
			banned_ips_msg: jQuery('#banned_ips_msg').val()
		};
	
		// Send AJAX request
		jQuery.ajax({
			type: 'POST',
			dataType: "json",
			url: ip_geomaster_ajax.ajax_url,
			data: params, // data object with parameters
			success: function(response) {
				if (response.success) {
					showToast('Settings saved! You have successfully blocked multiple IP addresses.', '', 'success');
					jQuery("#ip-geomaster-addManyPanel").addClass("ip-geomaster-hidden"); // Sakrij panel za dodavanje
					jQuery("#ip-geomaster-mainPanel").removeClass("ip-geomaster-hidden");
					
					getBannedIps(); // Refresh the list of banned IPs
					

				} else {
					showToast('Error!', response.data.msg + ': ' + response.data.invalid_ip, 'error');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				jQuery('#results-error').html('<p>Error blocking countries: ' + error + '</p>');
			},
			complete: function() {
				console.log('Completed');
			}
		});
	}



	function getBannedIps() {

		let params = {
			action: 'ip_geomaster_get_banned_ips', // Action hook
			ip_geomaster_nonce_field: ip_geomaster_ajax.nonce, // Nonce field
			banned_ips_msg: jQuery('#banned_ips_msg').val()  //Show msg to banned user IP
		};


		jQuery('#ipsBlockingSwitch').on('change', function() {
			updateSwitchState(this.checked ? "on" : "off", this.id);
		});


		jQuery.ajax({
			type: 'GET',
			dataType: "json",
			url: ip_geomaster_ajax.ajax_url,
			data: params,
		
			success: function(response) {

				jQuery('#banned_ips_msg').val(response.ips_msg);

				updateSwitchState(response.ips_mode, 'ipsBlockingSwitch');

				if (response.banned_ips) {

					let bannedCount = response.banned_ips.length;
					jQuery('#totalIpBan').text(bannedCount);

					jQuery(".ip-geomaster-panel-body").html( 
						response.banned_ips.map(ipData => `
							<div class="ip-geomaster-ip-row">
								<div class="ip-geomaster-ip">
									${ipData.ip}<br>
									<small>${ipData.date} ${ipData.time}</small>
								</div>
								<div class="ip-geomaster-notes">${ipData.notes}</div>
							</div>
						`).join('') 
					);
				} else {
					showToast('Error fetching data:',  response.data,  'error');
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				jQuery('#results-error').html('<p>Error fetching data: ' + error + '</p>');
			},
			complete: function() {
				console.log('Completed');
			}
		});
		


	}

	
		// Function for showing toast messages
		function showToast(heading, message, type = 'info') {
			jQuery.toast({
				heading: heading,
				text: Array.isArray(message) ? message : [message], // Podržava string i niz
				icon: type,
				position: 'bottom-right',
				hideAfter: 5000
			});
		}


		function updateSwitchState(botsMode, elementId) {
			
			let switchElement = document.getElementById(elementId);
			let statusText = document.getElementById('switchStatus');
		
			if (!switchElement || !statusText) {
				console.error("Element not found:", elementId);
				return;
			}
		
			if (botsMode === "on") {
				switchElement.checked = true;
				statusText.innerText = "On";
			} else {
				switchElement.checked = false;
				statusText.innerText = "Off";
			}
		}

		function validateRequest(data) {
			let emptyFields = [];
		
			for (let key in data) {
				if (typeof data[key] === "string" && data[key].trim() === "") {
					emptyFields.push(key);
				}
			}
		
			if (emptyFields.length > 0) {
				//alert(`Error: The following fields are empty: ${emptyFields.join(", ")}`);
				return false;
			}
		
			return true;
		}