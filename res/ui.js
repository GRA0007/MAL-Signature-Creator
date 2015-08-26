(function() {
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
		var switchery = new Switchery(html, { color: '#2e51a2'});
	});
	document.getElementById('sig-size').addEventListener("change", function() {
		if (document.getElementById('sig-size').value == 'custom') {
			document.getElementById('custom-size').style.display = 'block';
		} else {
			document.getElementById('custom-size').style.display = 'none';
		}
	});
	$('.uploaded-image').click(function() {
		if ($(this).hasClass('selected')) {
			$('.uploaded-image').removeClass('selected');
			$(this).addClass('selected');
		} else {
			$('.uploaded-image').removeClass('selected');
		}
	});
})();