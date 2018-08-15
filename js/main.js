(function($) {
	var waitPeriod = 100;
	var haventExistedYet = true;
	var currentlyExist = false;
	var disappeared = false;

	addListenersAfterCheckoutFinishedLoading();

	// When the checkout page loads, WooCommerce does some crazy
	// stuff with JavaScript to grab the correct checkout form
	// data. In the process, it removes the HTML and replaces it,
	// which kills any event listeners that we've added in the meantime.
	//
	// This function executes recursively until it detects that WooCommerce
	// has removed the HTML and re-added it, at which point we add the appropriate
	// event listeners to the custom buttons to remove items from the cart.
	function addListenersAfterCheckoutFinishedLoading() {
		var elements = $(".blockUI.blockOverlay");
		var elementsExist = $(elements).length > 0;

		if (elementsExist && haventExistedYet) {
			haventExistedYet = false;
			currentlyExist = true;
			setTimeout(addListenersAfterCheckoutFinishedLoading, waitPeriod);
		} else if (!elementsExist && currentlyExist) {
			currentlyExist = false;
			disappeared = true;
			setTimeout(addListenersAfterCheckoutFinishedLoading, waitPeriod);
		} else if (disappeared) {
			disappeared = false;
			$(".remove-from-cart").on("click", removeFromCart);
		} else {
			setTimeout(addListenersAfterCheckoutFinishedLoading, waitPeriod);
		}
	}

	function removeFromCart(e) {
		e.preventDefault();

		$.post(
			ajax_object.ajax_url,
			{
				action: "remove_item_from_cart",
				key: e.target.dataset.key
			},
			refreshPage
		);
	}

	function refreshPage() {
		window.location.reload(true);
	}
})(jQuery);
