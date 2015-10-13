//ドロワー読み込み
$(document).ready(function() {
  $(".drawer").drawer();
});

$(function() {
	$('#disabled-submit').attr('disabled', 'disabled');
	$('#disabled-check').click(function() {
		if ($(this).prop('checked') == false) {
			$('#disabled-submit').attr('disabled', 'disabled');
		} else {
			$('#disabled-submit').removeAttr('disabled');
		}
	});
});
