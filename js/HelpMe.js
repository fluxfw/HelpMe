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
		"mm_lang_sel"*/ // TODO:
	],

	/**
	 * @type {string}
	 */
	MODAL_TEMPLATE: "",

	/**
	 * @type {string}
	 */
	PAGE_SCREENSHOT_NAME: "",

	/**
	 * @type {string}
	 */
	SCREENSHOT_TEMPLATE: "",

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
	 * @type {Blob[]}
	 */
	screenshots: [],

	/**
	 *
	 */
	addPageScreenshot: function () {
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
			canvas.toBlob(function (screenshot) {
				screenshot.lastModifiedDate = new Date();
				screenshot.name = this.PAGE_SCREENSHOT_NAME + ".png";

				this.screenshots.push(screenshot);

				this.updateScreenshots();
			}.bind(this), "image/png");
		}.bind(this));
	},

	/**
	 *
	 */
	addScreenshot: function () {
		var $screenshot_file_input = $("#helpme_screenshot_file_input");

		$screenshot_file_input.click();
	},

	/**
	 *
	 */
	addScreenshotOnChange: function () {
		var screenshot_file_input = $("#helpme_screenshot_file_input")[0];

		if (screenshot_file_input.value !== "") {
			Array.prototype.forEach.call(screenshot_file_input.files, function (screenshot) {
				this.screenshots.push(screenshot);
			}, this);

			screenshot_file_input.value = "";

			this.updateScreenshots();
		}
	},

	/**
	 * @returns {boolean}
	 */
	cancel: function () {
		this.screenshots = [];

		this.modal.modal("hide");

		return false;
	},

	/**
	 * @returns {boolean}
	 */
	click: function () {
		this.screenshots = []; // TODO: Correct handle cancel event

		var get_url = this.button.attr("href");

		$.get(get_url, this.show.bind(this));

		return false;
	},

	/**
	 * @param {File|Blob} screenshot
	 */
	deleteScreenshot: function (screenshot) {
		var i = this.screenshots.indexOf(screenshot);

		this.screenshots.splice(i, 1);

		this.updateScreenshots();
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
		}, this);

		if (test) {
			$("#ilTopBarNav").append(this.li);
		}
	},

	/**
	 *
	 */
	initModal: function () {
		this.modal = $(this.MODAL_TEMPLATE);

		// TODO: Screenshot icons
		$("body").append(this.modal);
	},

	/**
	 *
	 * @param {string} html
	 */
	show: function (html) {
		this.modal.find(".modal-body").html(html);

		var $form = $("#form_helpme_form");
		var $cancel = $("#helpme_cancel");
		var $add_screenshot = $("#helpme_add_screenshot");
		var $add_page_screenshot = $("#helpme_add_page_screenshot");
		var $screenshot_file_input = $("#helpme_screenshot_file_input");

		$form.submit(this.submit.bind(this));
		$cancel.click(this.cancel.bind(this));
		$add_screenshot.click(this.addScreenshot.bind(this));
		$add_page_screenshot.click(this.addPageScreenshot.bind(this));
		$screenshot_file_input.change(this.addScreenshotOnChange.bind(this));

		this.updateScreenshots();

		this.modal.modal("show");
	},

	/**
	 * @returns {boolean}
	 */
	submit: function () {
		var $form = $("#form_helpme_form");
		var $submit = $("#helpme_submit");

		var post_url = $form.attr("action");

		var data = new FormData($form[0]); // Supports files upload
		data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

		this.screenshots.forEach(function (screenshot) {
			data.append("srsu_screenshots[]", screenshot);
		}, this);

		$.ajax({
			type: "post",
			url: post_url,
			contentType: false,
			processData: false,
			data: data,
			success: this.show.bind(this)
		});

		return false;
	},

	/**
	 *
	 */
	updateScreenshots: function () {
		var $screenshots = $("#helpme_screenshots");

		$screenshots.empty();

		this.screenshots.forEach(function (screenshot) {
			var $screenshot = $(this.SCREENSHOT_TEMPLATE);
			var $screenshot_name = $(".helpme_screenshot_name", $screenshot);
			var $screenshot_delete = $(".helpme_screenshot_delete", $screenshot);

			$screenshot_name.text(screenshot.name);

			$screenshot_delete.click(this.deleteScreenshot.bind(this, screenshot));

			$screenshots.append($screenshot);
		}, this);
	}
};
