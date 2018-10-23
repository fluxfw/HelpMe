/**
 * @param {string} post_var
 *
 * @constructor
 */
il.ScreenshotsInputGUI = function (post_var) {
	this.post_var = post_var;

	this.screenshots = [];

	this.init();
};

/**
 * @type {il.ScreenshotsInputGUI[]}
 *
 * @private
 */
il.ScreenshotsInputGUI.INSTANCES = [];

/**
 * @type {string}
 */
il.ScreenshotsInputGUI.PAGE_SCREENSHOT_NAME = "";

/**
 * @type {string}
 */
il.ScreenshotsInputGUI.SCREENSHOT_TEMPLATE = "";

/**
 * @returns {il.ScreenshotsInputGUI|undefined}
 */
il.ScreenshotsInputGUI.lastInstance = function () {
	return this.INSTANCES[this.INSTANCES.length - 1];
};

/**
 * @param {string} post_var
 */
il.ScreenshotsInputGUI.newInstance = function (post_var) {
	this.INSTANCES.push(new this(post_var));
};

/**
 * @param {il.ScreenshotsInputGUI} screenshots
 */
il.ScreenshotsInputGUI.removeInstance = function (screenshots) {
	screenshots.removePreviewURLCache();

	var i = this.INSTANCES.indexOf(screenshots);

	this.INSTANCES.splice(i, 1);
};

/**
 * @type {Object}
 */
il.ScreenshotsInputGUI.prototype = {
	constructor: il.ScreenshotsInputGUI,

	/**
	 * @type {jQuery|null}
	 */
	$add_page_screenshot: null,

	/**
	 * @type {jQuery|null}
	 */
	$add_screenshot: null,

	/**
	 * @type {jQuery|null}
	 */
	$form: null,

	/**
	 * @type {jQuery|null}
	 */
	$screenshot_file_input: null,

	/**
	 * @type {jQuery|null}
	 */
	$screenshots: null,


	/**
	 * @type {jQuery|null}
	 */
	element: null,

	/**
	 * @type {jQuery|null}
	 */
	modal: null,

	/**
	 * @type {string}
	 */
	post_var: "",

	/**
	 * @type {string[]}
	 */
	previewURLCache: [],

	/**
	 * @type {File[]}
	 */
	screenshots: [],

	/**
	 * @type {string}
	 */
	submitButtonID: [],

	/**
	 *
	 */
	addPageScreenshot: function () {
		// Hide modal on the screenshot
		this.hideModal();

		html2canvas($("html")[0]).then(this.addPageScreenshot2.bind(this)).catch(function (err) {
			// Restore modal
			this.restoreModal();

			//console.log(err);
			alert(err);
		}.bind(this));
	},

	/**
	 *
	 * @param {HTMLCanvasElement} canvas
	 */
	addPageScreenshot2: function (canvas) {
		// Restore modal
		this.restoreModal();

		// Convert canvas screenshot to png blob for file upload
		canvas.toBlob(this.addPageScreenshot3.bind(this), "image/png");
	},

	/**
	 *
	 * @param {Blob} blob
	 */
	addPageScreenshot3: function (blob) {
		var screenshot;
		try {
			screenshot = new File([blob], this.constructor.PAGE_SCREENSHOT_NAME + ".png", {type: blob.type});
		} catch (err) {
			screenshot = blob;
		}

		this.screenshots.push(screenshot);

		this.updateScreenshots();
	},

	/**
	 *
	 */
	addScreenshot: function () {
		this.$screenshot_file_input.click();
	},

	/**
	 *
	 */
	addScreenshotOnChange: function () {
		var screenshot_file_input = $(".screenshot_file_input", this.element)[0];

		if (screenshot_file_input.value !== "") {
			Array.prototype.forEach.call(screenshot_file_input.files, function (screenshot) {
				this.screenshots.push(screenshot);
			}, this);

			screenshot_file_input.value = "";

			this.updateScreenshots();
		}
	},

	/**
	 * @var {FormData} formData
	 */
	addScreenshotsToUpload: function (formData) {
		this.screenshots.forEach(function (screenshot) {
			formData.append(this.post_var + "[]", screenshot);
		}, this);

		this.removePreviewURLCache();
	},

	/**
	 *
	 */
	hideModal: function () {
		if (this.modal !== null) {
			this.modal.css("visibility", "hidden");
			$(".modal-backdrop").css("visibility", "hidden");
			$("body").css("overflow", "visible"); // Fix transparent not visible area from modal
		}
	},

	/**
	 *
	 */
	init: function () {
		this.element = $('input[type="file"][name="' + this.post_var + '"]').parent();

		this.$add_screenshot = $(".add_screenshot", this.element);
		this.$add_page_screenshot = $(".add_page_screenshot", this.element);
		this.$screenshot_file_input = $(".screenshot_file_input", this.element);
		this.$form = this.$screenshot_file_input.parents("form");
		this.$screenshots = $(".screenshots", this.element);

		this.$add_screenshot.click(this.addScreenshot.bind(this));
		this.$add_page_screenshot.click(this.addPageScreenshot.bind(this));
		this.$screenshot_file_input.change(this.addScreenshotOnChange.bind(this));
		this.$form.submit(this.submit.bind(this));
	},

	/**
	 *
	 */
	removePreviewURLCache: function () {
		this.previewURLCache.forEach(function (preview_url) {
			URL.revokeObjectURL(preview_url);
		});
		this.previewURLCache = [];
	},

	/**
	 * @param {File|Blob} screenshot
	 */
	removeScreenshot: function (screenshot) {
		var i = this.screenshots.indexOf(screenshot);

		this.screenshots.splice(i, 1);

		this.updateScreenshots();
	},

	/**
	 *
	 */
	restoreModal: function () {
		if (this.modal !== null) {
			this.modal.css("visibility", "");
			$(".modal-backdrop").css("visibility", "");
			$("body").css("overflow", "");
		}
	},


	/**
	 * @returns {boolean}
	 */
	submit: function () {
		var $submit = $("#" + this.submitButtonID);

		var post_url = this.$form.attr("action");

		var data = new FormData(this.$form[0]); // Supports files upload
		data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

		this.addScreenshotsToUpload(data);

		$.ajax({
			type: "post",
			url: post_url,
			contentType: false,
			processData: false,
			data: data,
			success: this.submitFunction.bind(this)
		});

		return false;
	},

	/**
	 *
	 */
	submitFunction: function () {

	},

	/**
	 *
	 */
	updateScreenshots: function () {
		this.$screenshots.empty();
		this.removePreviewURLCache();

		this.screenshots.forEach(this.updateScreenshot, this);
	},

	/**
	 * @param {File} screenshot
	 */
	updateScreenshot: function (screenshot) {
		var $screenshot = $(this.constructor.SCREENSHOT_TEMPLATE);
		var $screenshot_name = $(".screenshot_name", $screenshot);
		var $screenshot_remove = $(".screenshot_remove", $screenshot);
		var $screenshot_preview_link = $(".screenshot_preview_link", $screenshot);
		var $screenshot_preview = $(".screenshot_preview", $screenshot);

		var preview_url = URL.createObjectURL(screenshot);

		$screenshot_name.text(screenshot.name);

		$screenshot_remove.click(this.removeScreenshot.bind(this, screenshot));

		$screenshot_preview_link.prop("href", preview_url);
		$screenshot_preview.prop("src", preview_url);
		$screenshot_preview.prop("alt", screenshot.name);

		this.$screenshots.append($screenshot);

		this.previewURLCache.push(preview_url);
	}
};
