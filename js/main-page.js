// JavaScript Document

(function($){
	
	var number = 0;
	
	$(function(){
		number = $('.main_page_comments .faces img').length - 1;
	});
	
	var arr = [0]
	arr.push(number);
	
	function genRnd () {
		var rnd = parseInt(Math.random() * number);
		if ((rnd == arr[0])||(rnd == arr[1])) {
			return genRnd();
		} else {
			arr.shift();
			arr.push(rnd);
			return rnd;
		}
	}
	
	function setComments () {
		
		var position = genRnd();
		
		$('.main_page_comments .faces img').each(function(index, element) {
            $(this).removeClass('active');
        });
		$('.main_page_comments .faces img:eq(' + position + ')').addClass('active');
		$('.main_page_comments .all_comments .user_comment').each(function(index, element) {
            $(this).removeClass('active');
        });
		$('.main_page_comments .all_comments .user_comment:eq(' + position + ')').addClass('active');
	}
	
	$(function(){
		setComments();
		var showComments = setInterval( function() {
			setComments();
		}, 10000);

		$('.main_page_comments .faces img').on('mouseenter', function(){
			clearInterval(showComments);
			$('.main_page_comments .faces img').each(function(index, element) {
	            $(this).removeClass('active');
    	    });
			$('.main_page_comments .all_comments .user_comment').each(function(index, element) {
    	        $(this).removeClass('active');
        	});
			$(this).addClass('active');
			var place = $(this).index();
			$('.main_page_comments .all_comments .user_comment:eq(' + place + ')').addClass('active');
		});

		$('.main_page_comments .faces img').on('mouseleave', function(){
			showComments = setInterval( function() {
				setComments();
			}, 10000);
		});
		
	});
	
})(jQuery)