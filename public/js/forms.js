$(document).ready(function () {
	$('.tabulable').keypress(function (event) {
		if ( event.which == 13 ) {
			event.preventDefault();
			if (event.keyCode == 13) {
				/* FOCUS ELEMENT */
				var inputs = $(this).parents("form").eq(0).find(":input");
				var idx = inputs.index(this);

				if (idx == inputs.length - 1) {
					inputs[0].select()
				} else {
					inputs[idx + 1].focus(); //  handles submit buttons
					inputs[idx + 1].select();
				}
				return false;
			}
		}
	});
});