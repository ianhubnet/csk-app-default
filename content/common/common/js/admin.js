/*!
 * Skeleton Dashboard - Admin JS (https://github.com/ianhubnet/skeleton)
 * Copyright 2024 Kader Bouyakoub (http://bit.ly/KaderGhb)
 */
(function($, window, document, undefined) {
	"use strict";

	/** Prepare Skeleton globals. */
	var csk = window.csk = window.csk || {};
	csk.i18n = csk.i18n || {};

	/**
	 * BootBox default configuration.
	 * @since 	1.20
	 */
	if (typeof bootbox !== "undefined") {
		bootbox.setDefaults({
			backdrop: true,
			closeButton: false,
			locale: csk.config.lang.code,
			size: "small"
		});
		bootbox.setLocale(csk.config.lang.code || "en");
	}

	/**
	 * Skeleton UI module.
	 * @since 	1.20
	 */
	csk.ui = {
		/**
		 * Confirmation alert using either bootbox or default alert.
		 * @since 	1.20
		 * @param 	string  message
		 * @param 	trueCallback 	The callback to use once confirmed.
		 * @param 	falseCallback 	The callback to use once canceled.
		 * @return  void
		 */
		confirm: function(message, trueCallback, falseCallback, elem) {
			if ((message.startsWith("lang:") || message.startsWith("i18n:")) && typeof csk.i18n !== "undefined") {
				message = eval("csk.i18n." + message.substring(5)) || message;
			}

			// Attempt to use sprintf
			if (typeof elem === "object") {
				var name = elem.data("name") || elem.attr("aria-label");
				if (typeof name !== "undefined" && name.length) {
					message = sprintf(message, name);
				}
			}
			if (typeof bootbox !== "undefined") {
				bootbox.confirm({
					message: message,
					buttons: {
						confirm: {
							className: "btn btn-primary btn-sm"
						},
						cancel: {
							className: "btn btn-default btn-sm"
						}
					},
					callback: function(result) {
						bootbox.hideAll();
						if (result === true && typeof trueCallback === "function") {
							trueCallback(true);
						} else if (typeof falseCallback === "function") {
							falseCallback(true);
						}
					}
				});
			} else if (confirm(message) && typeof trueCallback === "function") {
				trueCallback(true);
			} else if (typeof falseCallback === "function") {
				falseCallback(true);
			}
		},
		/** Alert (Notification). */
		alert: function(message, type) {
			if (!message.length) {
				return false;
			}
			type = type || 'info';
			if (type === "error") {
				type = "danger";
			}

			if (typeof toastr !== "undefined") {
				switch (type) {
					case "success":
						toastr.success(message);
						break;
					case "error":
					case "danger":
						toastr.error(message);
						break;
					case "warning":
						toastr.warning(message);
						break;
					case "info":
					default:
						toastr.info(message);
						break;
				}
				return;
			}

			/**
			 * If Handlebars is loaded, we already have an alert template
			 * stored within the dashboard default layout. So we use it.
			 */
			if (typeof Handlebars === "object") {
				/** We store any old alert so we can remove it later. */
				var oldAlert = $("#csk-alert"),
					alertSource = document.getElementById("csk-alert-template").innerHTML,
					alertTemplate = Handlebars.compile(alertSource);

				/** Compile the alert. */
				var alertCompiled = alertTemplate({
					message: message,
					type: type
				});

				/** If we have an old alert, remove it first. */
				if (oldAlert.length) {
					oldAlert.fadeOut(function() {
						$(this).remove();
						$(alertCompiled).prependTo("#wrapper > .container");
					});
				} else {
					$(alertCompiled).prependTo("#wrapper > .container");
				}

				return;
			}

			/**
			 * Otherwise, we make sure to strip any HTML tags from the message
			 * and simply use browser's default alert.
			 */
			alert(message.replace(/(<([^>]+)>)/ig, ""));
		},
		/** Reload main page parts. */
		reload: function(el, navbar, callback) {
			/** If no element is provided, we use "#wrapper". */
			el = el || "#wrapper";
			if (!el.length) {
				return false;
			}
			if (navbar !== false) {
				$("#navbar-admin").load(csk.config.currentURL + " #navbar-admin > *");
			}
			$(el).load(csk.config.currentURL + " " + el + " > *", callback);
		},
		/**
		 * Check if an element is in viewport.
		 * @since 	2.0
		 */
		inViewport: function(el) {
			var $that = el.getBoundingClientRect();
			return $that.bottom >= 0 && $that.right >= 0 && $that.top <= (window.innerHeight || document.documentElement.clientHeight) && $that.left <= (window.innerWidth || document.documentElement.clientWidth);
		},
		/**
		 * Function to add an event listener.
		 * @since 	2.0
		 */
		addListener: function(event, callback) {
			if (window.addEventListener) {
				window.addEventListener(event, callback);
			} else {
				window.attachEvent("on" + event, callback);
			}
		},
		/**
		 * Lazy load images.
		 * @since 	2.0
		 */
		lazyLoad: function() {
			var lazyImages = $("[data-src]");
			for (var i = 0; i < lazyImages.length; i++) {
				var img = lazyImages[i];
				if (!csk.ui.inViewport(img) || typeof img !== "object") {
					return;
				} else if ($(img).is('img')) {
					img.src = img.getAttribute("data-src") || img.src;
					img.onload = function() {
						this.removeAttribute("data-src");
					};
				} else if (typeof $(img).css === "function") {
					$(img).css("background-image", "url("+img.getAttribute("data-src")+")");
					img.removeAttribute("data-src");
				}
			}
		},
		/**
		 * Splits given string into an array.
		 * @since 	2.16
		 */
		prepFields: function(input) {
			var fields = {},
				hash;
			var hashes = input.split(';');
			for (var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split(':');
				fields[hash[0]] = hash[1];
			}
			return fields;
		},
		/**
		 * Returns an array of GET parameters.
		 * @since 	2.16
		 */
		getUrlParams: function(href) {
			href = href || window.location.href;
			var params = {},
				hash;
			var hashes = href.slice(href.indexOf('?') + 1).split('&');
			for (var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				params[hash[0]] = hash[1];
			}
			return params;
		},
		/**
		 * Returns a GET parameter.
		 * @since 	2.16
		 */
		getUrlParam: function(param) {
			var pageURL = window.location.search.substring(1),
				varsURL = pageURL.split("&"),
				paramName,
				i;
			for (i = 0; i < varsURL.length; i++) {
				paramName = varsURL[i].split("=");
				if (paramName[0] === param) {
					return paramName[1] === undefined ? true : decodeURIComponent(paramName[1]);
				}
			}
			return false;
		},
		/**
		 * Copy to clipboard.
		 * @since 	2.18
		 */
		clipboard: function(el) {
			var copyText = el.getAttribute("data-text");
			if (typeof copyText === "undefined" || !copyText.length) {
				return false;
			}
			navigator.clipboard.writeText(copyText);
			csk.ui.alert(csk.i18n.media.copied, "success");
		}
	};

	/**
	 * Register our custom Lazy Load function.
	 * @since 	2.0
	 */
	csk.ui.addListener("load", csk.ui.lazyLoad);
	csk.ui.addListener("scroll", csk.ui.lazyLoad);

	/**
	 * Skeleton AJAX handler.
	 * @since 	1.40
	 */
	csk.ajax = {
		/**
		 * Array of queued AJAX requests.
		 * @type {Array}
		 */
		requests: [],
		/**
		 * Flag to check whether a request is being processed.
		 * @type {Boolean}
		 */
		requesting: false,
		/**
		 * The last context that was set by the request.
		 * @type {object}
		 */
		context: undefined,
		/**
		 * Queues an AJAX request and fires if needed.
		 * @param  {string} 	url 	The URL to send AJAX to.
		 * @param  {array} 		params 	Objec of AJAX settings.
		 */
		request: function(url, params) {
			params = params || {};
			var context = params.context || this,
				type = params.type || "GET";

			/** Merge parameters with default ones. */
			params = $.extend({
				url: url,
				method: type,
				type: type,
				// Backward compatibility.
				async: true,
				cache: false,
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					csk.ajax._response(data);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					var response = jqXHR.responseJSON || undefined;
					if (typeof response !== "undefined" && response.message !== "undefined" && response.message.length) {
						csk.ui.alert(response.message, "error");
					}
				}
			}, params);
			this.requests.push(params);
			this._execute();
		},
		/** Stops AJAX requests and dequeue them all. */
		stop: function() {
			this.requests = [];
		},
		/** Handles the queue an run AJAX requests. */
		_execute: function() {
			if (this.requests.length == 0) {
				return;
			}
			if (this.requesting == true) {
				return;
			}
			var request = this.requests.splice(0, 1)[0],
				complete = request.complete;
			var self = this;
			if (request._execute) {
				request._execute(request);
			}
			request.complete = function() {
				if (complete) {
					complete.apply(this, arguments);
				}
				self.requesting = false;
				self._execute();
			};
			this.requesting = true;
			$.ajax(request);
		},
		/**
		 * Handle JSON data response sent by csk.ajax.request
		 * @param  {strng} data Normally, it should be a JSON encoded response.
		 */
		_response: function(data) {
			var data = data || {},
				context = this;

			/** Cache the used element. */
			csk.ajax.context = context;

			/** Did we receive a message? */
			if (typeof data.message !== "undefined" && data.message.length) {
				csk.ui.alert(data.message, "success");
			}

			/** No scripts passed? Nothing to do. */
			if (typeof data.scripts === "undefined") {
				return;
			}

			/** Perform receive scripts. */
			var _scripts = data.scripts.length;
			for (var i = 0; i < _scripts; i++) {
				try {
					new Function(data.scripts[i]).call(context);
				} catch (e) {
					console.log(e);
				}
			}
		}
	};

	/**
	 * Upload file handler.
	 * @since 	2.16
	 */
	csk.sendFile = function(file, that) {
		var data = new FormData(),
			href = $(that).attr("data-upload");
		if (typeof href === "undefined" || !href.length) {
			return false;
		}
		data.append("file", file);
		csk.ajax.request(href, {
			type: "POST",
			data: data,
			processData: false,
			contentType: false,
			complete: function(jqXHR, textStatus) {
				if (textStatus === "success") {
					var response = jqXHR.responseJSON;
					if ($(that).hasClass('summernote')) {
						$(that).summernote("insertImage", response.results.url);
					}

					// Append hidden media input.
					$("<input>").attr({
						type: "hidden",
						name: "medias[]",
						value: response.results.id
					}).appendTo(that);

					// Enable submit button abck
					$(that).closest('form').find("[type=submit]").prop("disabled", false).removeClass("disabled");
				}
			}
		});
	};

	/**
	 * Fake form submit handler.
	 * @since 	2.16
	 */
	csk.submit = function(url, fields) {
		var $form = $("<form>", {
			action: url,
			method: 'post'
		});
		$.each(fields, function(key, val) {
			$("<input>").attr({
				type: "hidden",
				name: key,
				value: val
			}).appendTo($form);
		});
		$form.appendTo('body').submit();
	};

	/**
	 * SKeleton Ping.
	 * @since 	2.100
	 */

	csk.ping = {
		init : function(options) {
			var defaults = {
				url: "keep-alive",
				timer: 600000 // 10min
			};
			this.options = $.extend(defaults, options);
			csk.ping._ping.apply(this);
			return this;
		},
		stop : function() {
			if(this.nextPing) {
				clearTimeout(this.nextPing);
			}
		},
		_ping : function() {
			var self = this;
			this.nextPing = setTimeout(function() {
				csk.ajax.request(self.options.url, {type: "POST"});
				csk.ping._ping.apply(self);
			}, self.options.timer);
		}
	};

	/** Things to do when the page is ready! */
	$(document).ready(function() {
		/**
		 * Extend jQuery.
		 * @since 	2.100
		 */
		$.fn.ping = function(method) {
			if (csk.ping[method]) {
				return csk.ping[method].apply(this, Array.prototype.slice.call(arguments, 1));
			} else if (typeof method === "object" || !method) {
				return csk.ping.init.apply(this, arguments);
			} else {
				$.error("The method " +  method + " doesn't exist in $.fn.ping");
			}
		};

		/**
		 * Start Keep Alive (30min).
		 * @since 	2.100
		 */
		if (typeof csk.config !== "undefined") {
			$.fn.ping({url: csk.config.baseURL+'keep-alive', timer: 1800000});
		}

		/**
		 * Automatic alert hiding.
		 * @since 	2.16
		 */
		$(".alert-dismissible").each(function() {
			let $alert = $(this);

			// Skip 'alert-keep', 'alert-danger' and 'alert-warning'
			if ($alert.hasClass("alert-keep") || $alert.hasClass("alert-danger") || $alert.hasClass("alert-warning")) {
				return;
			}

			// Calculate display time based on content length
			let len = $alert.text().trim().length;
			let after = Math.min(10000, 4000 + (len * 50)); // Max 10 sec.

			// Auto-close after calculated delay
			$alert.delay(after).slideUp(500, function() {
				$alert.alert("close").remove();
			});
		});

		/**
		 * Toastr default configuration.
		 * @since 	1.20
		 */
		if (typeof toastr !== "undefined") {
			toastr.options = {
				"rtl": csk.config.lang.direction === 'rtl',
				"closeButton": true,
				"positionClass": "toast-top-center",
				"hideDuration": "300",
				"timeOut": "2500",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
		}

		/**
		 * Bootstrap toasts.
		 * @since 	2.95
		 */
		else if (typeof $.fn.toast !== "undefined") {
			$(".toast").each(function() {
				$(this).toast("show").on("hidden.bs.toast", function() {
					$(this).remove();
				});
			});
		}

		/**
		 * Select2.
		 * @since 	2.16
		 */
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

		/**
		 * Datatables.
		 * @since 	2.16
		 */
		if (typeof $.fn.DataTable !== "undefined") {
			$(".datatables").each(function() {
				$(this).DataTable({
					columnDefs: [{
						targets: 'no-sort',
						orderable: false
					}]
				});
			});
		}

		/**
		 * Bootstrap 4 File Upload.
		 * @since 	2.18
		 */
		$(".custom-file-input").on("change", function() {
			var filename = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(filename);
		});

		/**
		 * Things we do if jQuery UI is detected.
		 * @since 	2.16
		 */
		if (typeof $.ui === "object") {
			/**
			 * jQuery Sortable.
			 * @since 2.16
			 */
			$(".sortable").each(function() {
				var $that = $(this);
				$that.sortable({
					containment: ".sortable",
					cursor: "move",
					tolrance: "pointer",
					scroll: true,
					revert: 100,
					opacity: .65,
					axis: $that.data("axis") || "y",
					update: function(event, ui) {
						var target = $(".sortable-form");
						if (typeof target === "object") {
							var data = $(this).sortable("toArray", {
								attribute: "data-id"
							});
							target.attr("data-fields", "id:" + data.join(","));
						}
					}
				});
			});
		}

		/**
		 * Holds an array of checkbox.
		 * @since 2.16
		 */
		var multiSelect = [];

		/**
		 * Check all feature.
		 * @since 	2.0
		 */
		$(document).on("change", ".check-all", function() {
			$(this).closest("table").find(".check-this:not(:disabled)").prop("checked", this.checked).trigger("change").closest("tr").toggleClass("selected", this.checked);
		});
		$(document).on("change", ".check-this", function() {
			var $that = $(this),
				row = $that.closest("[data-id]") || $that.parents("tr"),
				table = $that.parents("#list") || $that.parents("table"),
				target = $('.bulk-action');
			row.toggleClass("selected", this.checked);
			if (this.checked) {
				row.addClass("selected");
				multiSelect.push(row.data("id"));
				target.attr("data-fields", "id:" + multiSelect.join(","));
			} else {
				row.removeClass("selected");
				multiSelect.splice(multiSelect.indexOf(row.data("id")), 1);
				target.attr("data-fields", "id:" + multiSelect.join(","));
			}
			table.attr("data-selected", multiSelect);
			if (multiSelect.length === 0) {
				$(".bulk-action").prop("disabled", true).addClass("disabled");
			} else {
				$(".bulk-action").prop("disabled", false).removeClass("disabled");
			}
		});

		/**
		 * Bult action for check all feature.
		 * @since 	2.16
		 */
		$(document).on("click", ".bulk-action", function(e) {
			e.preventDefault();
			var $that = $(this),
				href = $that.attr("ajaxify") || $that.attr("href"),
				message = $that.data("message");
			if ($that.attr("data-form")) {
				return false;
			}
			if (typeof href === "undefined" || !href.length) {
				return false;
			}
			if (!multiSelect.length) {
				csk.ui.alert(csk.i18n.default.make_selection, "info");
				return false;
			}
			if (typeof message !== "undefined") {
				return csk.ui.confirm(message, function() {
					csk.ajax.request(href, {
						el: this,
						type: $that.data("request") || "POST",
						data: {
							"bulk": multiSelect,
							"url": href
						},
						beforeSend: function() {
							if (!$that.prop("disabled")) {
								$that.prop("disabled", true).addClass("disabled");
							}
						},
						success: function(data, textStatus, jqXHR) {
							csk.ajax._response(data);
							$that.removeProp("disabled").removeClass("disabled");
							setTimeout(location.reload.bind(location), 1500);
						}
					});
				}, null, $that);
			}
			csk.ajax.request(href, {
				el: this,
				type: "POST",
				data: {
					"bulk": multiSelect,
					"url": href
				},
				beforeSend: function() {
					if (!$that.prop("disabled")) {
						$that.prop("disabled", true).addClass("disabled");
					}
				},
				complete: function() {
					$that.removeProp("disabled").removeClass("disabled");
					window.location.href = location.href;
				}
			});
		});

		/** Bootstrap tooltip and popover. */
		if (typeof $.fn.tooltip !== "undefined") {
			$("body").tooltip({
				selector: "[data-bs-toggle=tooltip], [rel=tooltip]"
			});
		}
		if (typeof $.fn.popover !== "undefined") {
			$("body").tooltip({
				selector: "[data-bs-toggle=popover], [rel=popover]"
			});
		}

		/**
		 * To avoid multiple form submission, we make sure to
		 * disable submit buttons once hit.
		 * @since 	1.20
		 */
		$(document).on("submit", "form", function(e) {
			var $form = $(this);
			$("[type=submit]").prop("disabled", true).addClass("disabled");

			/** Disable the submit event. */
			$form.submit(function() {
				return false;
			});
			return true;
		});

		/** Make form inputs keep values even after refresh. */
		if (typeof $.fn.garlic !== "undefined") {
			$("[rel=persist]").garlic();
		}

		/**
		 * AJAXify anchors with attribute rel="async".
		 * @since 	1.33
		 */
		$(document).on("click", "a:not([data-confirm])[rel]", function(e) {
			var $that = $(this),
				rel = $that.attr("rel"),
				href = $that.attr("ajaxify") || $that.attr("href");
			if (typeof href === "undefined" || !href.length) {
				e.preventDefault();
				return false;
			}

			/** Not valid rel attribute? Proceed by default. */
			if (!rel.length || rel !== "async" && rel !== "async-post") {
				return;
			}
			e.preventDefault();
			csk.ajax.request(href, {
				el: this,
				type: rel === "async" ? "GET" : "POST",
				beforeSend: function() {
					if ($that.prop("disabled")) {
						return;
					}

					/** We disable the element before proceeding. */
					$that.prop("disabled", true).addClass("disabled");
				},
				success: function(data, textStatus, jqXHR) {
					/** let _response handle response/ */
					csk.ajax._response(data);
					/** remove disabled property and reload page. */
					$that.removeProp("disabled").removeClass("disabled");
					setTimeout(location.reload.bind(location), 1500);
				}
			});
			return false;
		});

		/**
		 * We ajaxify forms with attribute rel="async".
		 * @since 	1.30
		 */
		$(document).on("submit", "form[rel]", function(e) {
			var $that = $(this),
				rel = $that.attr("rel"),
				href = $that.attr("action");

			/** No action provided? Nothing to do... */
			if (typeof href === "undefined" || !href.length) {
				e.preventDefault();
				return false;
			}
			switch (rel) {
				/** In case of an asynchronous use. */
				case "async":
					e.preventDefault();
					csk.ajax.request(href, {
						el: this,
						type: "POST",
						data: $that.serializeArray(),
						beforeSend: function() {
							if ($that.prop("disabled")) {
								return;
							}
							$that.find("[type=submit]").prop("disabled", true).addClass("disabled");
						},
						complete: function() {
							$that.find("[type=submit]").prop("disabled", false).removeClass("disabled");
						}
					});
					return false;
					break;
			}
		});

		/**
		 * If there is a modal within the page, we make sure to display it
		 * @since 	1.0.0
		 * @todo 	FIXME: problem with Summernote JS.
		 */
		if (typeof $.fn.modal !== "undefined") {
			var bsModal = $(".modal.modal-land");
			if (bsModal.length) {
				bsModal.modal("show");
			}
		}

		// We make sure to completely remove the modal when closed.
		$(document).on("hidden.bs.modal", ".modal:not(.modal-keep)", function(e) {
			$(this).remove();
		});

		/**
		 * Another way to add a confirmation message before proceeding is
		 * to add the "data-confirm" tag with a required message.
		 * @example:
		 * <a href="..." data-confirm="Are you sure?">...</a>
		 */
		$(document).on("click", "[data-confirm]:not([data-form])", function(e) {
			e.preventDefault();
			var $that = $(this),
				rel = $that.attr("rel"),
				href = $that.attr("ajaxify") || $that.attr("href"),
				message = $that.data("confirm");

			/** No URL provided? Nothing to do... */
			if (typeof href === "undefined" || !href.length) {
				return false;
			}

			/** No URL provided? Nothing to do...No message provided? Just proceed. */
			if (typeof message === "undefined" || !message.length) {
				message = "Are you sure?";
			}

			/** Display the confirmation box before proceeding. */
			return csk.ui.confirm(message, function() {
				/** ajax request. */
				if (typeof rel !== "undefined" && rel.length) {
					csk.ajax.request(href, {
						el: this,
						type: rel === "async" ? "GET" : "POST",
						beforeSend: function() {
							if ($that.prop("disabled")) {
								return;
							}

							/** We disable the element before proceeding. */
							$that.prop("disabled", true).addClass("disabled");
						},
						success: function(data, textStatus, jqXHR) {
							/** let _response handle response/ */
							csk.ajax._response(data);
							/** remove disabled property and reload page. */
							$that.removeProp("disabled").removeClass("disabled");
							setTimeout(location.reload.bind(location), 1500);
						}
					});
				} else {
					window.location.href = href;
				}
			}, null, $that);
		});

		/**
		 * Generic buttons/anchors with action.
		 * @since 	2.10
		 *
		 * In order to user this feature, make sure all required parameters
		 * are correctly set:
		 * 1. [data-action] 	Defines the action to perform.
		 * 2. [data-target] 	Determines targeted element (csk.element).
		 * 3. [data-name] 		The name of the element.
		 * 4. [ajaxify] or [href]
		 */
		$(document).on("click", "[data-action]", function(e) {
			e.preventDefault();

			/** We collect data about the clicked element. */
			var $that = $(this),
				action = $that.data("action") || -1,
				target = $that.data("target") || undefined,
				name = $that.data("name") || $that.attr("aria-label") || "this",
				href = $that.attr("ajaxify") || $that.attr("href") || undefined;

			/** No action? Nothing to do... */
			if (action <= 0) {
				console.log("error action: " + action);
				return false;
			}

			/** No target? Nothing to do... */
			if (typeof target === "undefined" || !target.length || typeof csk[target] === "undefined") {
				console.log("error target: " + target);
				return false;
			}

			/** No href? Nothing to do... */
			if (typeof href === "undefined" || !href.length) {
				console.log("error href: " + href);
				return false;
			}

			/** We define the confirmation message. */
			var message = csk.i18n[target][action] || undefined;
			if (typeof message === "undefined") {
				message = csk.i18n[action] || undefined;
				if (typeof message === "undefined") {
					message = "Are you sure you want to " + action + " %s?";
				}
			}

			/** We see if the element is within a table. */
			var row = $that.closest("tr");

			/** If so, apply the opacity class "op-2". */
			if (row.length) {
				row.siblings("tr").addClass("op-2");
			}

			/** We display a confirmation message if defined. */
			if (typeof message !== "undefined" && message.length) {
				csk.ui.confirm(sprintf(message, name), function() {
					window.location.href = href;
				}, function() {
					/** We put back siblings opacity to initial state. */
					if (row.length) {
						row.siblings("tr").removeClass("op-2");
					}
				});
				return;
			}

			/** At this point, there was no confirmation message. */
			window.location.href = href;
			return;
		});

		/**
		 * submits the targeted form.
		 * @since 	2.16
		 */
		$(document).on("click", "[data-submit]", function(e) {
			e.preventDefault();
			var $that = $(this),
				target = $($that.data("submit")),
				message = $that.data("confirm");
			if (typeof target === "undefined") {
				return false;
			}
			if (typeof message === "undefined" || !message.length) {
				target.submit();
				return;
			}
			return csk.ui.confirm(message, function() {
				target.submit();
			}, null, $that);
		});

		/**
		 * Fake form submission.
		 * @since 	2.16
		 */
		$(document).on("click", "[data-form]", function(e) {
			e.preventDefault();
			var $that = $(this),
				href = $that.data("form"),
				data = $that.data("fields"),
				message = $that.data("confirm");

			// href url is required!
			if (typeof href === "undefined" || !href.length) {
				return false;
			}

			// data are required.
			if (typeof data !== "undefined" && data.length) {
				data = csk.ui.prepFields(data);
			}
			if (typeof message === "undefined" || !message.length) {
				csk.submit(href, data);
				return;
			}
			return csk.ui.confirm(message, function() {
				csk.submit(href, data);
			}, null, $that);
		});

		/**
		 * Themes actions.
		 * @since 	2.11
		 */

		/** Put back URL when modal is closed. */
		$(document).on("hidden.bs.modal", "#theme-modal", function(e) {
			var redirURL = csk.config.currentURL;
			if (redirURL.indexOf("?") > 0) {
				redirURL = redirURL.substring(0, redirURL.indexOf("?"));
			}
			window.history.pushState({
				href: redirURL
			}, "", redirURL);
			$(this).remove();
		});

		/**
		 * Dropzone.
		 * @since 	2.18
		 */
		if (typeof $.fn.dropzone === "function") {
			$(".dropZone").each(function() {
				var $that = $(this),
					href = $that.data("upload");
				if (typeof href === "undefined" || !href.length) {
					return;
				}
				$that.dropzone({
					url: href,
					// previewContainer: ".attachment",
					clickable: "#upload",
					init: function() {
						// Remove drag and drop message.
						this.on("addedfile", function() {
							$(".dz-message").remove();
						});

						// Successful?
						this.on("success", function(file, response) {
							var file = response.results;
							if (typeof file !== "object") {
								return false;
							}
							var source = document.getElementById("attachment-template").innerHTML,
								template = Handlebars.compile(source),
								html = template(file);
							$(html).hide().prependTo(".dropZone").fadeIn("slow");
						});

						// An error?
						this.on("error", function(file, response) {
							csk.ui.alert((response === null || response === void 0 ? void 0 : response.message) || response, "error");
						});

						// Complete?
						this.on("complete", function(file) {
							this.removeFile(file);
						});
					}
				});
			});
			$(document).on("hidden.bs.modal", "#media-modal", function(e) {
				var redirURL = csk.config.currentURL;
				if (redirURL.indexOf("?") > 0) {
					redirURL = redirURL.substring(0, redirURL.indexOf("?"));
				}
				window.history.pushState({
					href: redirURL
				}, "", redirURL);
				$(this).remove();
			});
		}
	});
})(window.jQuery || window.Zepto, window, document);
