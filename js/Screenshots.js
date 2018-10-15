/**
 * @param {string} post_var
 *
 * @constructor
 */
il.Screenshots = function (post_var) {
	this.post_var = post_var;

	this.screenshots = [];

	this.init();
};

/**
 * @type {il.Screenshots[]}
 *
 * @private
 */
il.Screenshots.INSTANCES = [];

/**
 * @type {string}
 */
il.Screenshots.PAGE_SCREENSHOT_NAME = "";

/**
 * @type {string}
 */
il.Screenshots.SCREENSHOT_TEMPLATE = "";

/**
 * @returns {il.Screenshots|undefined}
 */
il.Screenshots.lastInstance = function () {
	return this.INSTANCES[this.INSTANCES.length - 1];
};

/**
 * @param {string} post_var
 */
il.Screenshots.newInstance = function (post_var) {
	this.INSTANCES.push(new this(post_var));
};

/**
 * @param {il.Screenshots} screenshots
 */
il.Screenshots.removeInstance = function (screenshots) {
	var i = this.INSTANCES.indexOf(screenshots);

	this.INSTANCES.splice(i, 1);
};

/**
 * @type {Object}
 */
il.Screenshots.prototype = {
	constructor: il.Screenshots,

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
	 * @type {File[]}
	 */
	screenshots: [],

	/**
	 *
	 */
	addPageScreenshot: function () {
		// Hide modal on the screenshot
		if (this.modal !== null) {
			this.modal.css("visibility", "hidden");
			$(".modal-backdrop").css("visibility", "hidden");
			$("body").css("overflow", "visible"); // Fix transparent not visible area from modal
		}

		html2canvas($("html")[0]).then(function (canvas) {

			// Restore modal
			if (this.modal !== null) {
				this.modal.css("visibility", "");
				$(".modal-backdrop").css("visibility", "");
				$("body").css("overflow", "");
			}

			// Convert canvas screenshot to png blob for file upload
			canvas.toBlob(function (blob) {
				var screenshot = new File([blob], this.constructor.PAGE_SCREENSHOT_NAME + ".png", {type: blob.type});

				this.screenshots.push(screenshot);

				this.updateScreenshots();
			}.bind(this), "image/png");
		}.bind(this));
	},

	/**
	 *
	 */
	addScreenshot: function () {
		var $screenshot_file_input = $(".screenshot_file_input", this.element);

		$screenshot_file_input.click();
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
	},

	/**
	 *
	 */
	init: function () {
		this.element = $('input[type="file"][name="' + this.post_var + '"]').parent();

		var $add_screenshot = $(".add_screenshot", this.element);
		var $add_page_screenshot = $(".add_page_screenshot", this.element);
		var $screenshot_file_input = $(".screenshot_file_input", this.element);

		$add_screenshot.click(this.addScreenshot.bind(this));
		$add_page_screenshot.click(this.addPageScreenshot.bind(this));
		$screenshot_file_input.change(this.addScreenshotOnChange.bind(this));
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
	updateScreenshots: function () {
		var $screenshots = $(".screenshots", this.element);

		$screenshots.empty();

		this.screenshots.forEach(function (screenshot) {
			var $screenshot = $(this.constructor.SCREENSHOT_TEMPLATE);
			var $screenshot_name = $(".screenshot_name", $screenshot);
			var $screenshot_remove = $(".screenshot_remove", $screenshot);

			$screenshot_name.text(screenshot.name);

			// TODO: May preview

			$screenshot_remove.click(this.removeScreenshot.bind(this, screenshot));

			$screenshots.append($screenshot);
		}, this);
	}
};
