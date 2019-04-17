var fn = {
    
    $socialList: null,

    firstData: null,

    buyer: {
        init: function() {
            

            $('#submit_save').click(function(e){
                $('#submit_type').val(0);
            });
            $('#submit_review').click(function(e){
                $('#submit_type').val(1);
            });

            $('#frm_buyer_save').submit(function() {
                //
                var submit_type = $('#submit_type').val();
                // console.log(submit_type);

                if (!$('#iagree').is(':checked')) {

                    var html_content = "<div>You should agree to our Terms of Service and have read and understood the Privacy Policy before submit.</div>",
                    html_footer = '<div class="row">'
                        + '<div class="col-sm-12 form-group">'
                        +   '<a data-dismiss="modal" class="btn btn-default">Okay</a>'
                        + '</div>'
                        + '</div>';
                    $.instantModal('alert', html_content, html_footer, 'Information');

                    return false;
                }


                $('body').overlay();
                ajaxPost($(this).attr('action'), $(this).serialize(), function(data) {
                    // complete handler
                    $('body').overlayDone();
                    notify.success('Successfully submitted.');
                    document.location.href = data.redirect ? data.redirect : document.location.href;
                });

                return false;

            });

            fn.firstData = $('#frm_buyer_save').serialize();
            $('input,textarea').on('keyup change', function(){
                fn.buyer.monitor();
            });

            fn.buyer.monitor();
        },
        monitor: function() {
            var changed = (fn.firstData != $('#frm_buyer_save').serialize());
            if (changed) {
                $('#submit_save,#submit_review').removeAttr('disabled');
            } else {
                $('#submit_save,#submit_review').attr('disabled', 'disabled');
            }
        }
    },

    socials: {
        
        init: function() {

            fn.$socialList = $('#social_list');

            fn.$socialList.sortable({
                handle: ".item-handler",
                update: function( event, ui ) {
                    //
                    fn.buyer.monitor();
                }
            });
            
            helper.initSelectbox({
              selector: fn.$socialList.find('.social-types')
            });

            $('#add_new_social').on('click', function(e){
                e.preventDefault();
                fn.socials.insertItem();
            });

            fn.$socialList.on('click', '.item-del', function(e){
                e.preventDefault();
                fn.socials.deleteItem($(this).closest('li'));
            });

            $('input,textarea').on('keyup change', function(){
                fn.buyer.monitor();
            });

            fn.socials.initList();
            
            fn.buyer.monitor();

        },

        initList: function() {
            if (socials.length > 0) {
                fn.$socialList.html('');
                for (var i=0; i<socials.length; i++) {
                    var item = socials[i];
                    fn.socials.insertItem({type: item.social_type, url: item.social_url});
                }
            }
        },

        insertItem: function(fileData) {
            var itemHtml = `<li class="row">
                                <div class="col-md-3 col-sm-3" style="padding-top: 24px;">
                                    <a href="#" class="command item-handler" title="Move"><i class="fa fa-arrows-alt"></i></a>
                                    <select name="social_names[]" class="social-types">
                                        <option></option>
                                        <option class="linkedin" value="linkedin">LinkedIn</option>
                                        <option class="facebook" value="facebook">Facebook</option>
                                        <option class="twitter" value="twitter">Twitter</option>
                                        <option class="youtube" value="youtube">Youtube</option>
                                        <option class="pinterest" value="pinterest">Pinterest</option>
                                        <option class="instagram" value="instagram">Instagram</option>
                                    </select>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <input type="text" class="form-control fancy input-social-url" name="social_urls[]" placeholder="Social Media">
                                    <a href="#" class="command item-del" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            </li>`;
            var $obj = $(itemHtml);
            
            if (fileData) {
                $obj.find('.social-types').val(fileData.type);
                $obj.find('.input-social-url').val(fileData.url);
            }
            
            $obj.appendTo(fn.$socialList);
            helper.initSelectbox({
              selector: $obj.find('.social-types')
            });

            fn.buyer.monitor();
        },

        deleteItem: function($obj) {
            $obj.remove();
            fn.buyer.monitor();
        }
    },

    init: function() {
        fn.socials.init();
        fn.buyer.init();
    }
}

fn.init();



