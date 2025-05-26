/*!
 * CI Skeleton - Common JS (https://github.com/bkader/skeleton)
 * Copyright 2024 Kader Bouyakoub (http://bit.ly/KaderGhb)
 */
(function($, window, document, undefined) {
	"use strict";

	$(document).ready(function() {
		// See if forms have confirmation messages.
		$("form").each(function() {
			var $form = $(this),
				btn = $form.find(":submit"),
				msg = $form.data("confirm");

			$form.submit(function() {
				if (typeof btn === "object") {
					$(btn).prop("disabled", true).addClass("disabled");
				}
				if (typeof msg === "string") {
					if (confirm(msg)) {
						return true;
					} else if (typeof btn === "object") {
						$(btn).prop("disabled", false).removeClass("disabled");
					}
					return false;
				}
				setTimeout(function() {
					$(btn).prop("disabled", false).removeClass("disabled");
				}, 3000);
				return true;
			})
		});

		// select2
		if (typeof $.fn.select2 !== "undefined") {
			$("select.select2").each(function() {
				var $that = $(this),
					config = {
						width: "100%"
					};
				if ($that.hasClass("select2-modal")) {
					config.dropdownParent = $(".modal");
				}
				$that.select2(config);
			});
		}
	});
})(window.jQuery || window.Zepto, window, document);
