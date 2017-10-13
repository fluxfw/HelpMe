if (typeof il === "undefined") {
	var il = {};
}

il.HelpMe = (function () {
	var $button, modal;

	return {
		init: function () {
			$button = $("#il_help_me_button");

			$button.click(onClick);
		}
	};

	function onClick(e) {
		// Prevent link
		e.preventDefault();

		modal = il.Modal.dialogue({
			id: "il_help_me_modal",
			show: true,
			header: il.Language.txt("srsu_support"),
			body: "Text",
			buttons: {
				"submit": {
					id: "",
					type: "button",
					label: il.Language.txt("srsu_submit"),
					callback: onSubmit
				},
				"cancel": {
					id: "",
					type: "button",
					label: il.Language.txt("srsu_cancel"),
					callback: function () {
						modal.hide();
					}
				}
			}
		});
	}

	function onSubmit(e) {
		alert();
	}
})();
