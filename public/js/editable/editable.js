var editable = {
	show: function(){
		$('.editable_menu').remove();
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

			switch(type){
				case "text":
					$("<div class='editable_menu trig_editable_edit' data-editable-type='"+type+"' data-editable='"+name+"' data-editable-for='"+target+"'>edit</div>").css({top: pos.top+'px', left: pos.left+'px'}).appendTo('body');
				break;
				case "image":
					$("<div class='editable_menu trig_editable_edit' data-editable-type='"+type+"' data-editable='"+name+"' data-editable-for='"+target+"'>change image</div>").css({top: pos.top+'px', left: pos.left+'px'}).appendTo('body');
				break;
			}
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
				switch(type){
					case "text":
						var content = el.html();
						var html = "<textarea id='editable_content'>"+content+"</textarea> <button class='trig_editable_save' data-editable='"+name+"' data-editable-type='"+type+"'>Save</button>";
						$.colorbox({html:html, width: '670', height: '420px', scrolling: true});
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