$(function () {
	$("#il_help_me_button").click(function () {
		var $elem = $(this);
		var url = $elem.attr("href");

		$.get(url, function (response) {
			var $modal = $("#il_help_me_modal");

			$modal.find(".modal-body").html(response);
			$modal.modal("show");
		});

		return false;
	});
});
