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
	GET_ISSUE_TYPES_OF_PROJECT_URL: "",

	/**
	 * @type {string}
	 */
	GET_SHOW_TICKETS_OF_PROJECT_URL: "",

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
	 * @param {Event|null} e
	 *
	 * @returns {boolean}
	 */
	click: function (e = null) {
		if (e !== null) {
			e.preventDefault();
		}

		var get_url = this.button.attr("href");

		$.get(get_url, this.show.bind(this));
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

		if (this.li.hasClass("dropdown")) {
			this.button = $("li > a:first", this.li); // Support button is the first link  in the dropdown
		} else {
			this.button = $("> a:first", this.li)
		}

		this.button.click(this.click.bind(this));

		// Checks insert support button near a exists ID, depends which ILIAS page
		var append_at_end = this.IDS.every(function (id) {
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

		if (append_at_end) {
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
	 *
	 */
	projectChangeIssueTypes: function () {
		// First empty previous project issue types (Without "please select")
		var $old_issue_type_select = $("#issue_type");
		$old_issue_type_select.children().first().nextAll().remove();
		$old_issue_type_select.prop("disabled", true);

		var $projects_select = $("#project");
		var project_url_key = $projects_select.val();

		var get_url = this.GET_ISSUE_TYPES_OF_PROJECT_URL;
		get_url += "&project_url_key=" + project_url_key;

		$.get(get_url, this.projectChangeIssueTypesShow.bind(this));
	},

	/**
	 * @param {string} new_issue_types_select_html
	 */
	projectChangeIssueTypesShow: function (new_issue_types_select_html) {
		var $old_issue_type_select = $("#issue_type");

		$old_issue_type_select.replaceWith(new_issue_types_select_html);
	},

	/**
	 *
	 */
	projectChangeShowTickets: function () {
		// First delete previous tickets link
		var $projects_select = $("#project");
		$projects_select.next().remove();

		var project_url_key = $projects_select.val();

		var get_url = this.GET_SHOW_TICKETS_OF_PROJECT_URL;
		get_url += "&project_url_key=" + project_url_key;

		$.get(get_url, this.projectChangeShowTicketsShow.bind(this));
	},

	/**
	 * @param {string} new_tickets_link_html
	 */
	projectChangeShowTicketsShow: function (new_tickets_link_html) {
		var $projects_select = $("#project");

		$projects_select.after(new_tickets_link_html);
	},

	/**
	 * @param {string} html
	 */
	show: function (html) {
		if (html.indexOf("form_helpme_form") === -1) {
			// Fix login page (Needs a second request)
			if (this.requestAgain) {
				this.requestAgain = false;

				this.click();

				return;
			}
		}

		this.requestAgain = false;

		this.modal.find(".modal-body").html(html);

		// Check if the results html contains the support form for prevent js errors
		if (html.indexOf("form_helpme_form") !== -1) {

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
			var $projects_select = $("#project");

			$cancel.click(this.cancel.bind(this));

			// Update project ticket issues
			$projects_select.change(this.projectChangeIssueTypes.bind(this));
			$('input[type="hidden"][name="issue_type"]').remove(); // ILIAS hidden field for disabled fields will cause problems and always get empty value

			// Update project show tickets link
			if ($projects_select.parent(".project_select_input").length > 0) {
				$projects_select.change(this.projectChangeShowTickets.bind(this));
			}
		}

		this.modal.modal("show");
	}
};
