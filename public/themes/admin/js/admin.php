<?php
session_start();
header("content-type: application/javascript");
?>
/** admin vars **/
var admin = {};
admin.params = {};
admin.filters = {};

(function($) {
    var re = /([^&=]+)=?([^&]*)/g;
    var decode = function(str) {
        return decodeURIComponent(str.replace(/\+/g, ' '));
    };
    $.parseParams = function(query) {
        var params = {}, e;
        if (query) {
            if (query.substr(0, 1) == '?') {
                query = query.substr(1);
            }

            while (e = re.exec(query)) {
                var k = decode(e[1]);
                var v = decode(e[2]);
                if (params[k] !== undefined) {
                    if (!$.isArray(params[k])) {
                        params[k] = [params[k]];
                    }
                    params[k].push(v);
                } else {
                    params[k] = v;
                }
            }
        }
        return params;
    };
})(jQuery);

// adds get params to admin.params
(function () {
    var e,
        a = /\+/g,  // Regex for replacing addition symbol with a space
        r = /([^&=]+)=?([^&]*)/g,
        d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
        q = window.location.search.substring(1);

    while (e = r.exec(q))
       admin.params[d(e[1])] = d(e[2]);
})();

/** onready events **/

$(document).ready(function(){

  	// enable active menu highlighting
  	$('#main_menu li').removeClass('active');

  	// login event
  	$('#login_form').submit(function(e){
		e.preventDefault();
		var form = jien.util.serializeForm($(this));
		$.post("/api/login", form, function(res){
            console.log(res);
			if(res.status.code == 200){
				$().toastmessage('showSuccessToast', 'Success');
				window.location.href = form.redir;
			}else if(res.status.code == 402){
                $().toastmessage('showWarningToast', 'Two-Factor Login code required');
                $('.login.auth_code').show();
            }else{
				$().toastmessage('showErrorToast', res.result.msg);
				$('#login_form input[name='+res.result.focus+']').focus();
			}
		});
	});

	$('#register_form').submit(function(e){
		e.preventDefault();
		var form = jien.util.serializeForm($(this));
		$.post("/api/register", form, function(res){
			if(res.status.code == 200){
				$().toastmessage('showSuccessToast', 'Success');
				if(form.redir){
					window.location.href = form.redir;
				}
			}else{
				$().toastmessage('showErrorToast', res.result.msg);
				$('#register_form input[name='+res.result.focus+']').focus();
			}
		});
	});

	$('#user_login_show').click(function(e){
		$('.login_register_show').hide();
		$('.login_register').hide();
		$('#user_register_show').show();
		$('#user_login').show();
	});

	$('#user_register_show').click(function(e){
		$('.login_register_show').hide();
		$('.login_register').hide();
		$('#user_login_show').show();
		$('#user_register').show();
	});

  	// logout event
  	$('.trig_logout').click(function(e){
  		e.preventDefault();
  		$.post("/api/logout", function(res){
  			window.location.href = '/admin';
  		});
  	});

  	// save form event
    $('.trig_form').submit(function(e){
        e.preventDefault();
        var form = $(this).attr('rel') || this;
        var data = jien.util.serializeForm(form);
        $.post("/admin/data", data, function(res){
            if(res.status.code == 200){
                jien.ui.growl('Saved!');
                if(!data.id){
                    var page = data.model.pluralize().toLowerCase();
                    window.location = '/admin/' + page;
                }
            }else{
              console.log(res);
                jien.ui.growl(res.status.text, 'error');
            }
        });
    });

  	// delete event
  	$('.trig_delete').click(function(e){
  		e.preventDefault();
  		var c = confirm('Are you sure?');
  		if(c){
	  		var opts = jien.util.parseRel($(this).attr('rel'));
	  		opts.cmd = 'delete';
	  		var self = this;
	  		$.post("/admin/data", opts, function(res){
	  			if(res.status.code == 200){
	  				jien.ui.growl('Deleted');
                    var page = opts.model.pluralize().toLowerCase();
                    window.location = '/admin/' + page;
                    //history.go(-1);
	  				//$(self).parent().parent().slideUp();
	  			}else{
	  				jien.ui.growl(res.status.text, 'error');
	  			}
	  		});
  		}
  	});

  	// go back
  	$('.trig_back').click(function(e){
      e.preventDefault();
      history.go(-1);
    });

    // go url
    $('.trig_go').click(function(e){
      e.preventDefault();
      window.location = $(this).attr('rel');
    });

    // forms
    $(".datepicker").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true, showAnim: 'fadeIn'});


    admin.filterRedir = function(filters, params){
      var url = window.location.href;
      var q = url.split('?');
      var pairs = [];

      url = q[0];

      $.each(filters, function(k, filter){
        if(!filter.value){
          delete(params[filter.key]);
        }else{
          params[filter.key] = filter.value;
        }
      });
      $.each(params, function(k, v){
        pairs.push(k + '=' + v);
      });
      var query_string = '';
      query_string = pairs.join('&');
      if(query_string != ''){
        url += "?" + pairs.join('&');
      }
      window.location.href = url;
    };

    /* admin lists events */
    $('.filter.dropdown').change(function(e){
      e.preventDefault();
      var filter = $(this).attr('rel');
      var value = $(this).attr('value');
      admin.filterRedir([{key: filter, value: value}], admin.params);
    });

    // table sorter
    if(admin.params.order_by){
      var order_by = admin.params.order_by;
      var sort_by = admin.params.sort_by;
      var sort_class = '';
      if(sort_by == 'desc'){
        sort_class = 'headerSortUp';
      }else{
        sort_class = 'headerSortDown';
      }
      $(".header[rel='"+order_by+"']").addClass(sort_class);
    }

    $('.header').click(function(e){
      e.preventDefault();
      var field = $(this).attr('rel');
      var sort = '';
      if( $(this).hasClass('headerSortUp') ){
        sort = 'asc';
      }else{
        sort = 'desc';
      }
      admin.filterRedir([{key: 'order_by', value: field},{key: 'sort_by', value: sort}], admin.params);
    });
    

    // admin v2 //

    // Initialize tooltips
    $('.ttip').tooltip();

    // Initalize select2 boxes
    $(".select2").select2({
        allowClear: true
    });

    // Sticky upper right buttons
    if ($(document).scrollTop() > 0 && !$('#header-btns').hasClass('fixed')) {
        $('#header-btns').hide().addClass('fixed').fadeIn('fast');
    }    
    $(document).scroll(function(){
        if (!$('#header-btns .inner').is(':empty')) {
            if ($(document).scrollTop() > 0 && !$('#header-btns').hasClass('fixed')) {
                $('#header-btns').hide().addClass('fixed').fadeIn('fast');
            } else if (!$('#header-btns').hasClass('fixed') || !$(document).scrollTop() > 0) {
                $('#header-btns').removeClass('fixed');
            }
        }
    });




});