"use strict";

const form = document.getElementById('checkout-form');
const tokenLink = document.getElementById('get-token-link');


$(function () {
	const overlay = $('#overlay');
	overlay.show();

	$.get(tokenLink.href, function (data) {
		if (!data.braintree_client_token) {
			alert('Braintree payment init error');
			return;
		}

		braintree.dropin.create(
			{
				authorization: data.braintree_client_token,
				container:     document.getElementById('dropin-container')
			},
			(error, dropinInstance) => {
				overlay.hide();

				if (error) {
					console.error(error);
					return false;
				}

				form.addEventListener('submit', event => {
					event.preventDefault();
					overlay.show();

					dropinInstance.requestPaymentMethod((error, payload) => {
						if (error) {
							console.error(error);
							overlay.hide();
							return false;
						}

						document.getElementById('nonce').value = payload.nonce;
						form.submit();
					});
				});
			}
		);
	});
});