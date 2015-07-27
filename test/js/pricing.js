$(document).ready(function(){
	$('.payment-questions-blueb').click(function() {
		document.location.href='https://support.omnidesk.ru/?send=question';
		return false;
	})
	
	$('.payment-questions-greenb').click(function() {
		document.location.href=schedule_demo_url;
		return false;
	})
});