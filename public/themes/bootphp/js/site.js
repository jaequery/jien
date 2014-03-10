$(document).ready(function(){

    $('.wysiwyg').summernote({
	onImageUpload: function(files, editor, welEditable) {
	    console.log('image upload:', files, editor, welEditable);
	}
    });

});
