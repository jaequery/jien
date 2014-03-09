var editable = {
	show: function(){
		$('.editable_menu').remove();
        $('.editable').hover(function(){
            $(this).addClass('editable_active');
        }, function(){
            $(this).removeClass('editable_active');
        });
		$('.editable').each(function(){
			var pos = $(this).offset();
			var name = $(this).data('editable') || '';
			var type = $(this).data('editableType') || 'text';
			var target = $(this).data('editableFor') || '';
			if(target != ''){
				var el = $('#'+target);
				name = target;
			}else{
				var el = $(".editable[data-editable='"+name+"']");
			}

            var html = '';
			switch(type){
				case "text":
                    html = "<div class='editable_menu trig_editable_edit' data-editable-type='"+type+"' data-editable='"+name+"' data-editable-for='"+target+"'>edit</div>";
				break;
				case "image":
                    html = "<div class='editable_menu trig_editable_edit' data-editable-type='"+type+"' data-editable='"+name+"' data-editable-for='"+target+"'>change image</div>";
				break;
                case "inline":
                    html += "<div class='editable_menu trig_editable_edit' data-editable-type='"+type+"' data-editable='"+name+"' data-editable-for='"+target+"'>inline edit</div>";
                    html += "<div class='editable_menu trig_editable_save hide' data-editable='"+name+"' data-editable-for='"+target+"'>save</div>";
                    html += "<div class='editable_menu trig_editable_cancel hide' data-editable='"+name+"' data-editable-for='"+target+"'>cancel</div>";

                break;
			}
            $(html).css({top: pos.top+'px', left: pos.left+'px'}).appendTo('body');
		});
	},
	save: function(data, cb){
		$.post("/editable/save", data, function(res){
			cb(res);
	    });
	}
}

$(document).ready(function(){
	$.post("/auth/info", function(res){

		if(res.status.code == 200){
			editable.show();
			$(window).scroll(function(){
				editable.show();
			});
            $(document).on('click', '.trig_editable_save', function(){

            });

            $(document).on('click', '.trig_editable_cancel', function(){

            });

            $(document).on('click', '.trig_editable_reset', function(){

            });

            $(document).on('click', '.trig_editable_edit', function(){
				var name = $(this).data('editable');
				var type = $(this).data('editableType');
				var target = $(this).data('editableFor');
				if(target){
					var el = $('#'+target);
					name = target;
				}else{
					var el = $(".editable[data-editable='"+name+"']");
				}

                // show/hide appropriate buttons


				switch(type){
					case "text":
						var content = el.html();
						var html = "<textarea id='editable_content'>"+content+"</textarea>";
                        html += "<button class='trig_editable_save btn btn-large btn-success' data-editable='"+name+"' data-editable-type='"+type+"'>Save</button>";
                        //html += "&nbsp;<button class='trig_editable_reset btn btn-large btn-warning' data-editable='"+name+"' data-editable-type='"+type+"'>Reset</button>";
						$.colorbox({html:html, width: '70%', height: '70%', scrolling: true});
						$('.trig_editable_save').click(function(){
							var content = $('#editable_content').val();
							var name = $(this).data('editable');
							var type = $(this).data('editableType');
							var el = $(".editable[data-editable='"+name+"']");
							var post = {content: content, name: name, type: type};

							editable.save(post, function(res){
								if(res.status.code == 200){
								    $(".editable[data-editable='"+name+"']").html(content);
								    $.fn.colorbox.close();
								}
						    });
						});
					break;

                    case "inline":
                        el.attr('contentEditable', 'true');

                    break;

					case "image":
						var same_size = el.data('imageSameSize');
						var extra = '';
						var w = $(el).width();
						var h = $(el).height();

						if(same_size == true){
							extra = '/convert?w='+w+'&h='+h+'&fit=crop';
						}

						filepicker.pick(function(FPFile){
							var url = FPFile.url + extra;
							$(el).attr('src', url);
							var post = {content: url, name: name, type: type};
							editable.save(post, function(res){
								if(res.status.code == 200){

								}else{
									alert('there was a problem');
								}
						    });
						});
					break;
				}
			});

		}
	});
});