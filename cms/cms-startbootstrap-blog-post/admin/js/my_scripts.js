tinymce.init({ selector:'textarea' });

$(document).ready(function() {
	$('#bulk_selection').click(function() {
		selection = this.checked
		$('.checkbox').each(function () {
			this.checked = selection;
		});
	});
});
