$(document).ready(function() {
    admin.init();
});
var admin = {
    init: function() {
        admin.events.initFocusLogin();
        admin.events.initActiveMenu();
        admin.events.initSaveToApi();
    },
    events: {
        // set focus
        initFocusLogin: function() {
            $('form[name=boot-admin-login] input[name=username]').focus();
        },
        //submission of boot api data
        initSaveToApi: function() {
            $('.boot-api-data').on('submit', function(e) {
                e.preventDefault();
                var opts = {
                    data: util.serializeForm(this),
                    cmd: $(this).data('cmd'),
                    model: $(this).data('model')
                };
                $.post('/api/data', opts, function(res) {
                    if (res.status.code == 200) {
                        switch (opts.cmd) {
                            case 'update':
                                history.back();
                                break;
                        }
                    }
                });
            });
        },
        // set menu active
        initActiveMenu: function() {
            $('.boot-mainmenu li a').each(function() {
                var cur_url = window.location.pathname;
                var menu_url = $(this).attr('href');
                if (cur_url.indexOf(menu_url)) {
                    $(this).parent().removeClass('active');
                } else {
                    $(this).parent().addClass('active');
                }
            });
        }
    }
};
var util = {
    serializeForm: function(target) {
        var post = {};
        var form = $(target).serializeArray();
        $.each(form, function(k, v) {
            if (v.value) {
                post[v.name] = v.value;
            } else {
                post[v.name] = '';
            }
        });
        return post;
    }
}