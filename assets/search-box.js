$(document).ready(function () {
	$(".satu").select2({
		//dropdownParent: $('#modal_form')
	});
	$(".select2modal").select2({
		dropdownParent: $('#modal_form')
	});
	$(".banyak").select2({
		theme: "bootstrap"
	});

	$('.tag').select2({
		multiple: true,
		placeholder: ".:: Tag ::.",
		tags: true,
		tokenSeparators: [",", " "],
	});

});

