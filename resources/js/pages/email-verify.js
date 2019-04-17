var fn = {
    
    send: {
        init: function() {
            $('#btn_resend').click(function(e){
                e.preventDefault();
                $('body').overlay();

                var url = $(this).parent().attr('href');
                ajaxGet(url, function(){
                    notify.success('Verification email sent! Please check your inbox.');
                }, function(){
                    notify.success('Verification email sent! Please check your inbox.');
                });
            });
        }
    },

    init: function() {
        fn.send.init();
    }
}

fn.init();



