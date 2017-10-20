$(document).ready(function () {
	var $button = $("#il_help_me_button");
	var $form;
	var $modal = $("#il_help_me_modal");
	var $submit;

	$button.click(click);

	function click() {
		var get_url = $button.attr("href");

		$.get(get_url, show);

		return false;
	}

	function show(html) {
		$modal.find(".modal-body").html(html);

		$form = $("#form_il_help_me_form");
		$submit = $("#il_help_me_submit");
		var $cancel = $("#il_help_me_cancel");

		$form.submit(submit);
		$cancel.click(cancel);

		il.Form.init(); // TODO: Fix multiple listeners set

		$modal.modal("show");
	}

	function submit() {
		var post_url = $form.attr("action");

		var data = new FormData($form[0]); // Supports file upload
		data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

		$.ajax({
			type: "post",
			url: post_url,
			contentType: false,
			processData: false,
			data: data,
			success: show
		});

		return false;
	}

	function cancel() {
		$modal.modal("hide");

		return false;
	}
});
