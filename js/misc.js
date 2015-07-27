$(document).ready(function(){
    if($.browser.mac) {
        $('body').addClass('mac')
    }
    if($.browser.win) {
        $('body').addClass('win')
    }
    if($.browser.iphone) {
        $('body').addClass('iphone');
    }
    if($.browser.webkit) {
        $('body').addClass('webkit')
    }
    if($.browser.chrome) {
        $('body').addClass('chrome')
    }
    if($.browser.mozilla) {
        $('body').addClass('mozilla')
    }
    if($.browser.safari) {
        $('body').addClass('safari')
    }
    if($.browser.msie) {
        $('body').addClass('ie')
    }
    if($.browser.msie && $.browser.version >= 11) {
        $('body').addClass('ie11plus')
    }

	if($('.switcher-box .butt2').length) {
		//Switcher
		$('.switcher-box .butt2').on('click', function(){
			var switchedbox = this.id;
			
			//проверяем, если браузер поддерживает html5
			var test_canvas = document.createElement("canvas") //try and create sample canvas element
			if (test_canvas.getContext) {
				var rel = $(this).attr('rel');
				var stateObj = { switchedbox: switchedbox };
				History.pushState(stateObj, 'Возможности', rel);
			}
		});
		
		// Bind to State Change
		if(undefined!=History&&History.enabled) {
			History.Adapter.bind(window,'statechange', function(){
				var State=History.getState();
				var switchedbox = State.data.switchedbox;
				if(!switchedbox || undefined==switchedbox) {
					switchedbox = 'switch1';
				}
				$('#'+switchedbox).addClass('curr-it').siblings().removeClass('curr-it');
				$('.'+ switchedbox +'').show().siblings('.switch').hide();
			});
		}
	}
	
    //Styles
    $('.switcher-box .butt2:last-of-type').addClass('last-it');
    $('.capabilities-box .faq-box:last-child').addClass('last-it');

    //Placeholder Action

    $('.placeholder-over-box').on('click', function(){
        $('#subdomain').focus();
    });
    $('.placeholder-over-box').on('dblclick', function(){
        $('#subdomain').focus().select();
    });

    var $plhText = $("#subdomain");

    $plhText.data("value", $plhText.val());

    setInterval(function() {
        var data = $plhText.data("value"),
            val = $plhText.val();

        if (data !== val) {
            $plhText.data("value", val);
            $('.placeholder-value').text($plhText.val()).addClass('opacity-fld');
        }

    }, 5);

    $('#subdomain').on('focus', function(){
        if ($('.placeholder-value').text() == 'Название') {
            $('.placeholder-value').empty();
        }
        else {
            $('.placeholder-value').addClass('opacity-fld');
        }
    });
    $('#subdomain').on('blur', function(){
        if ($('.placeholder-value').text().length == 0) {
            $('.placeholder-value').text('Название').removeClass('opacity-fld');
        }
    });

    $(window).on('load', function(){

        var data = $plhText.data("value"),
            val = $plhText.val();

        if($('#subdomain').val()) {
			if ($('#subdomain').val().length > 0) {
				$('.placeholder-value').text($('#subdomain').val()).addClass('opacity-fld');

			}
		}
    });

    //Placeholders
    if($.browser.msie && $.browser.version <= 9) {
        $('input[placeholder]').placeholder({ color: '#a9a9a9' });
    }
	
	$('.form-it input').focus(function() {
		$(this).closest('.form-it').addClass('focus-fld');
	});
	
	$('.form-it input').blur(function() {
		$(this).closest('.form-it').removeClass('focus-fld');
	});
	
	if($('.modal-youtube').length) {
		$('.modal-youtube').magnificPopup({
			type:'iframe',
			midClick: true,
			closeBtnInside: true,
			closeOnBgClick: false,
			tClose: 'Закрыть (Esc)',
			closeMarkup: '<div title="%title%" class="modal-close-youtube mfp-close"><img src="/bundles/acmesite/img/close.png" width="16px"></i></div>',
		});
		
		$(document).on('click', '.modal-close-youtube', function (e) {
			remove_id = '';
			e.preventDefault();
			$.magnificPopup.close();
		});
	}
});


function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateSite(site) { 
	 return /^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(site);
}

function ReloadPage() {
	location.reload();
}

function GoPage(url) {
	document.location.href = url;
}