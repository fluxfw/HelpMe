$(document).ready(function () {
	var $button = $("#il_help_me_button");
	var $form;
	var $modal = $("#il_help_me_modal");
	var $submit;
	var $screenshot;
	var page_screenshot;

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
		$screenshot = $("#srsu_screenshot");
		var $page_screenshot = $("#il_help_me_page_screenshot");

		$form.submit(submit);
		$cancel.click(cancel);
		$screenshot.change(function () {
			page_screenshot = undefined; // Remove page screenshot if a file is selected

			if ($screenshot.val() === "") {
				$("#srsu_screenshot").parent().parent().next().val(""); // Custom file select label
			}
		});
		$page_screenshot.click(pageScreenshot);

		il.Form.init(); // TODO: Fix multiple listeners set

		$modal.modal("show");
	}

	function submit() {
		var post_url = $form.attr("action");

		var data = new FormData($form[0]); // Supports file upload
		data.append($submit.prop("name"), $submit.val()); // Send submit button with cmd

		if (page_screenshot !== undefined) {
			// Manually add page screenshot
			data.append("srsu_screenshot", page_screenshot, "Screenshot.png");
		}

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

	function pageScreenshot() {
		// Hide modal on the screenshot
		$modal.css("visibility", "hidden");
		$(".modal-backdrop").css("visibility", "hidden");

		html2canvas(document.body, {
			onrendered: function (canvas) {
				// Convert canvas screenshot to png blob for file upload
				canvas.toBlob(function (blob) {
					page_screenshot = blob;

					$screenshot.val(""); // Remove selected file

					$screenshot.parent().parent().next().val("Screenshot.png"); // Custom file select label
				}, "image/png");

				$modal.css("visibility", "");
				$(".modal-backdrop").css("visibility", "");
			}
		});

		return false;
	}
});
