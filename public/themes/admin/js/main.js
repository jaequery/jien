$(document).ready(function(){

    $('.wysiwyg').summernote({
        onImageUpload: function(files, editor, welEditable) {
            console.log('image upload:', files, editor, welEditable);
        }
    });

    // Bootstrap hashes for tabs
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });

});