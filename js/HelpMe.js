/**
 * @type {Object}
 */
il.HelpMe = {
    /**
     * @type {string}
     */
    BUTTON_ID: "",
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
     *
     */
    click: function () {
        var get_url = this.button.data("action");

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
        this.button = $("#" + this.BUTTON_ID);

        this.button.off("click").click(this.click.bind(this));
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
        var $old_issue_type_select = $('select[id^="field_issuetypefield_"]', this.modal);
        $old_issue_type_select.prop("disabled", true);

        var $projects_select = $('select[id^="field_projectfield_"]', this.modal);
        var project_url_key = $projects_select.val();

        var get_url = this.GET_ISSUE_TYPES_OF_PROJECT_URL;
        get_url += "&project_url_key=" + project_url_key;

        $.get(get_url, this.projectChangeIssueTypesShow.bind(this));
    },

    /**
     * @param {string} new_issue_types_select_html
     */
    projectChangeIssueTypesShow: function (new_issue_types_select_html) {
        var $old_issue_type_select = $('select[id^="field_issuetypefield_"]', this.modal);

        $old_issue_type_select.replaceWith(new_issue_types_select_html);

        $('input[type="hidden"][name^="field_issuetypefield_"]', this.modal).remove(); // ILIAS hidden field for disabled fields will cause problems and always get empty value
    },

    /**
     *
     */
    projectChangeShowTickets: function () {
        // First delete previous tickets link
        var $projects_select = $('select[id^="field_projectfield_"]', this.modal);
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
        var $projects_select = $('select[id^="field_projectfield_"]', this.modal);

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
            } else {
                this.screenshots = null;
            }

            var $form = $("#form_helpme_form > form");
            $form.submit(this.submit.bind(this));

            var $cancel = $("#helpme_cancel");
            $cancel.click(this.cancel.bind(this));

            var $projects_select = $('select[id^="field_projectfield_"]', this.modal);
            if ($projects_select.length === 1) {
                // Update project ticket issues
                if ($('select[id^="field_issuetypefield_"]', this.modal).length === 1) {
                    $projects_select.change(this.projectChangeIssueTypes.bind(this));
                }

                // Update project show tickets link
                if ($projects_select.parent(".project_select_input").length === 1) {
                    $projects_select.change(this.projectChangeShowTickets.bind(this));
                }
            }
            $('input[type="hidden"][name^="field_projectfield_"],input[type="hidden"][name^="field_issuetypefield_"]', this.modal).remove(); // ILIAS hidden field for disabled fields will cause problems and always get empty value
        }

        this.modal.modal("show");
    },

    /**
     * @returns {boolean}
     */
    submit: function () {
        var $form = $("#form_helpme_form > form");
        var $submit = $("#helpme_submit");

        $submit.prop("disabled", true);

        var post_url = $form.attr("action");

        var data = new FormData($form[0]); // Supports files upload
        data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

        if (this.screenshots !== null) {
            this.screenshots.addScreenshotsToUpload(data);
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
