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
	 * @type {il.Screenshots|null}
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

		il.Screenshots.removeInstance(this.screenshots);
		this.screenshots = null;
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

		$("body").append(this.modal);

		this.modal.on("hide.bs.modal", this.hide.bind(this));
	},

	/**
	 * @param {string} html
	 */
	show: function (html) {
		this.modal.find(".modal-body").html(html);

		// Apply last screenshots
		var screenshots = [];
		if (this.screenshots !== null) {
			screenshots = this.screenshots.screenshots;

		}
		this.screenshots = il.Screenshots.lastInstance();
		this.screenshots.screenshots = screenshots;
		this.screenshots.modal = this.modal;
		this.screenshots.updateScreenshots();

		var $form = $("#form_helpme_form");
		var $cancel = $("#helpme_cancel");

		$form.submit(this.submit.bind(this));
		$cancel.click(this.cancel.bind(this));

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

		this.screenshots.screenshots.forEach(function (screenshot) {
			data.append(this.screenshots.post_var + "[]", screenshot);
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
	}
};
