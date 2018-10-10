/**
 * @type {Object}
 */
il.HelpMe = {
	/**
	 * @type {Array}
	 */
	IDS: [
		"ilMMSearch",
		"userlog"/*,
		"mm_lang_sel"*/
	],

	/**
	 * @type {string}
	 */
	MODAL_TEMPLATE: "",

	/**
	 * @type {string}
	 */
	SUPPORT_BUTTON_TEMPLATE: "",

	/**
	 * @type {jQuery|null}
	 */
	button: null,

	/**
	 * @type {jQuery|null}
	 */
	li: null,

	/**
	 * @type {jQuery|null}
	 */
	modal: null,

	/**
	 * @type {Blob|null}
	 */
	page_screenshot: null,

	/**
	 * @returns {boolean}
	 */
	cancel: function () {
		this.modal.modal("hide");

		return false;
	},

	/**
	 * @returns {boolean}
	 */
	click: function () {
		var get_url = this.button.attr("href");

		$.get(get_url, this.show.bind(this));

		return false;
	},

	/**
	 *
	 */
	init: function () {
		$(document).ready(function () {
			this.initButton();
			this.initModal();
		}.bind(this));
	},

	/**
	 *
	 */
	initButton: function () {
		this.li = $(this.SUPPORT_BUTTON_TEMPLATE);

		this.button = this.li.children(0);

		this.button.click(this.click.bind(this));

		var test = this.IDS.every(function (id) {
			if (document.getElementById(id) !== null) {
				$("#" + id).before(this.li);

				return false;
			}

			return true;
		}.bind(this));

		if (test) {
			$("#ilTopBarNav").append(this.li);
		}
	},

	/**
	 *
	 */
	initModal: function () {
		this.modal = $(this.MODAL_TEMPLATE);

		$("body").append(this.modal);
	},


	/**
	 * @param {jQuery} $screenshot
	 *
	 * @returns {boolean}
	 */
	pageScreenshot: function ($screenshot) {
		// Hide modal on the screenshot
		this.modal.css("visibility", "hidden");
		$(".modal-backdrop").css("visibility", "hidden");
		$("body").css("overflow", "visible"); // Fix transparent not visible area from modal

		html2canvas($("html")[0]).then(function (canvas) {

			// Restore modal
			this.modal.css("visibility", "");
			$(".modal-backdrop").css("visibility", "");
			$("body").css("overflow", "");

			// Convert canvas screenshot to png blob for file upload
			canvas.toBlob(function (blob) {
				this.page_screenshot = blob;

				$screenshot.val(""); // Remove selected file

				$screenshot.parent().parent().next().val("Screenshot.png"); // Custom file select label
			}.bind(this), "image/png");
		}.bind(this));

		return false;
	},

	/**
	 * @param {jQuery} $screenshot
	 */
	pageScreenshotClear: function ($screenshot) {
		this.page_screenshot = null; // Remove page screenshot if a file is selected

		if ($screenshot.val() === "") {
			$("#srsu_screenshot").parent().parent().next().val(""); // Custom file select label
		}
	},

	/**
	 *
	 * @param {string} html
	 */
	show: function (html) {
		this.modal.find(".modal-body").html(html);

		var $form = $("#form_helpme_form");
		var $cancel = $("#helpme_cancel");
		var $screenshot = $("#srsu_screenshot");
		var $page_screenshot = $("#helpme_page_screenshot");

		$form.submit(this.submit.bind(this));
		$cancel.click(this.cancel.bind(this));
		$screenshot.change(this.pageScreenshotClear.bind(this, $screenshot));
		$page_screenshot.click(this.pageScreenshot.bind(this, $screenshot));

		il.Form.init(); // TODO: Fix may multiple listeners set

		this.modal.modal("show");
	},

	/**
	 * @returns {boolean}
	 */
	submit: function () {
		var $form = $("#form_helpme_form");
		var $submit = $("#helpme_submit");

		var post_url = $form.attr("action");

		var data = new FormData($form[0]); // Supports file upload
		data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

		if (this.page_screenshot !== null) {
			// Manually add page screenshot
			data.append("srsu_screenshot", this.page_screenshot, "Screenshot.png");
		}

		$.ajax({
			type: "post",
			url: post_url,
			contentType: false,
			processData: false,
			data: data,
			success: this.show.bind(this)
		});

		return false;
	}
};
