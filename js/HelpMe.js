/**
 * @type {Object}
 */
il.HelpMe = {
	/**
	 * @type {Array}
	 */
	IDS: [
		"ilMMSearch",
		"userlog",
		"mm_lang_sel"
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
	 * @type {boolean}
	 */
	autoOpen: false,

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
	 * @type {boolean}
	 */
	requestAgain: true,

	/**
	 * @type {il.ScreenshotsInputGUI|null}
	 */
	screenshots: null,

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
	hide: function () {
		this.modal.find(".modal-body").html("");

		if (this.screenshots !== null) {
			il.ScreenshotsInputGUI.removeInstance(this.screenshots);
			this.screenshots = null;
		}
	},

	/**
	 *
	 */
	init: function () {
		$(document).ready(function () {
			this.initButton();

			this.initModal();

			if (this.autoOpen) {
				this.click();
			}
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
			var beforeLi = document.getElementById(id);

			if (beforeLi !== null) {
				if (beforeLi.tagName.toLowerCase() !== "li") {
					beforeLi = beforeLi.parentElement;
				}

				$(beforeLi).before(this.li);

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

		$("body").append(this.modal);

		this.modal.on("hide.bs.modal", this.hide.bind(this));
	},

	/**
	 * @param {string} html
	 */
	show: function (html) {
		if (html.indexOf("form_helpme_form") === -1) {
			// Fix login page
			if (this.requestAgain) {
				this.requestAgain = false;

				this.click();

				return;
			}
		}

		this.requestAgain = false;

		this.modal.find(".modal-body").html(html);

		// Apply last screenshots
		var screenshots = [];
		if (this.screenshots !== null) {
			screenshots = this.screenshots.screenshots;

		}
		this.screenshots = il.ScreenshotsInputGUI.lastInstance();
		if (this.screenshots !== undefined) {
			this.screenshots.screenshots = screenshots;
			this.screenshots.modal = this.modal;
			this.screenshots.updateScreenshots();
			this.screenshots.submitButtonID = "helpme_submit";
			this.screenshots.submitFunction = this.show.bind(this);
		} else {
			this.screenshots = null;
		}

		var $cancel = $("#helpme_cancel");

		$cancel.click(this.cancel.bind(this));

		this.modal.modal("show");
	}
};
