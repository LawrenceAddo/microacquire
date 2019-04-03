var fn = {
    
    $dz: null,
    $dzObj: null,
    $dzList: null,

    $dz_pdf: null,
    $dzObj_pdf: null,
    $dzList_pdf: null,

    firstData: null,

    backupData: '',
    seller: {
        init: function() {
            

            $('#submit_save').click(function(e){
                $('#submit_type').val(0);
            });
            $('#submit_review').click(function(e){
                $('#submit_type').val(1);
            });

            $('#frm_seller_save').submit(function() {
                //
                var submit_type = $('#submit_type').val();
                // console.log(submit_type);

                if (!$('#iagree').is(':checked')) {

                    var html_content = "<div>You should agree to our Terms of Service and have read and understood the Privacy Policy before submit.</div>",
                    html_footer = '<div class="row">'
                        + '<div class="col-sm-6 form-group">'
                        +   '<a data-dismiss="modal" class="btn btn-default">Okay</a>'
                        + '</div>'
                        + '</div>';
                    $.instantModal('alert', html_content, html_footer, 'Information');

                    return false;
                }


                $('body').overlay();
                ajaxPost($(this).attr('action'), $(this).serialize(), function(){
                    // complete handler
                    $('body').overlayDone();
                    notify.success('Successfully submitted.');
                    document.location.href = document.location.href;
                });

                return false;

            });

            fn.firstData = $('#frm_seller_save').serialize();
            $('input,textarea').on('keyup change', function(){
                fn.seller.monitor();
            });

            fn.seller.monitor();
        },
        monitor: function() {
            var changed = (fn.firstData != $('#frm_seller_save').serialize());
            if (changed) {
                $('#submit_save,#submit_review').removeAttr('disabled');
            } else {
                $('#submit_save,#submit_review').attr('disabled', 'disabled');
            }
        }
    },
    dz_pictures: {
        
        init: function() {

            dzOpts = { 
                url: SITE_URL + "/upload/temp?type=0",
                acceptedFiles: 'image/*',
                maxFilesize: 2,
                createImageThumbnails: false,
                paramName: 'tmpfile',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                accept: function(file, done) {
                    fn.$dzObj.overlay();
                    return done();
                },
                processing: function(file) {
                    // console.log('processing___');
                },
                drop: function () {                
                    //
                    // console.log('dropping___');
                },
                success: function (file, data) {

                    fn.$dzObj.overlayDone();

                    fn.$dz.removeFile(file);


                    var fileData = false;
                    if (typeof data == 'string') {
                        try {
                            fileData = JSON.parse(data);
                        } catch(e) {}
                    } else {
                        fileData = data;
                    }

                    // console.log(fileData);

                    if ((fileData.status == 1) && (fileData.path != '') && (fileData.url != '')) {
                        // do something
                        fn.dz_pictures.applyImage(fileData);
                        fn.dz_pictures.applyActive2Next();

                    } else {
                        if (fileData.redirect) {
                            document.location.href = fileData.redirect;
                        } else if (fileData.msg) {
                            notify.error(fileData.msg);
                        } else {
                            notify.error('File upload failed. Try another image.');
                        }
                    }

                }, complete: function (file) {
                    // console.log('complete___');
                    if (!file.accepted) {
                        fn.$dzObj.overlayDone();

                        if (file.size > 2*1024*1024) {
                            notify .error('File is too big. Try with smaller than 2Mbyte, please.');
                        } else {
                            notify .error('File is not in accepted format.');
                        }
                        
                        // console.log(fn.$dz);
                        fn.$dz.removeFile(file);
                    }
                }

            };

            fn.$dzObj = $('#dz_seller_pictures');
            fn.$dz = new Dropzone('#dz_seller_pictures', dzOpts);
            fn.$dzList = $('#dz_thumb_list');

            fn.$dzList.sortable({
                update: function( event, ui ) {
                    //
                    fn.dz_pictures.applyStatus();
                }
            });
            

            fn.$dzList.on('click', 'li', function(e){
                fn.dz_pictures.applyActive($(this));
            });

            fn.$dzList.on('click', 'li .item-del', function(e){
                e.preventDefault();
                fn.dz_pictures.deleteImage($(this));
            });


            fn.dz_pictures.initList();

            fn.dz_pictures.applyActive(fn.$dzList.find('li').eq(0));
            this.applyStatus();

        },

        applyActive: function($o) {
            $o.addClass('active').siblings().removeClass('active');
        },

        applyActive2Next: function() {
            var $li = fn.$dzList.find('li.active');
            if ($li.next()) {
                fn.dz_pictures.applyActive($li.next());
            }
        },

        initList: function() {
            if (imgs) {
                for (i=0; i<imgs.length; i++) {
                    fn.dz_pictures.applyActive(fn.$dzList.find('li').eq(i));
                    fn.dz_pictures.applyImage({
                        'path': imgs[i]['path'],
                        'url': imgs[i]['url']
                    });
                }
            }
        },

        applyImage: function(fileData) {
            var $li = fn.$dzList.find('li.active');
            if ($li.length < 1) $li = fn.$dzList.find('li').eq(0);

            $li.find('.value-file-path').val(fileData.path);
            $li.addClass('filled').find('.frame .img-showing').css('background-image', 'url(' + fileData.url + ')');

            $li.find('.item-zoomin').attr('href', fileData.url).fancybox();

            fn.dz_pictures.applyStatus();
            fn.seller.monitor();
        },

        deleteImage: function($obj) {
            var $li = $obj.closest('li');

            $li.find('.value-file-path').val('');
            $li.removeClass('filled').find('.frame .img-showing').css('background-image', 'none');

            $li.find('.item-zoomin').off("click.fb-start");

            fn.dz_pictures.applyStatus();
            fn.seller.monitor();
        },

        applyStatus: function() {

            $('#dz_thumb_list li').each(function(i,o){
                $input = $(o).find('.value-file-path');
                $(o).toggleClass('filled', ($input.val() != ''));
                if (!$(o).hasClass('filled')) {
                    $(o).appendTo($(o).parent());
                }
            });

            $('#dz_thumb_list li').each(function(i,o){
                $(o).find('label').text(i+1);
            });


        }
    },

    dz_pdf: {
        
        init: function() {

            dzOpts = { 
                url: SITE_URL + "/upload/temp?type=1",
                acceptedFiles: 'application/pdf',
                maxFilesize: 5,
                createImageThumbnails: false,
                paramName: 'tmpfile',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                accept: function(file, done) {
                    fn.$dzObj_pdf.overlay();
                    return done();
                },
                processing: function(file) {
                    // console.log('processing___');
                },
                drop: function () {                
                    //
                    // console.log('dropping___');
                },
                success: function (file, data) {

                    fn.$dzObj_pdf.overlayDone();

                    fn.$dz_pdf.removeFile(file);


                    var fileData = false;
                    if (typeof data == 'string') {
                        try {
                            fileData = JSON.parse(data);
                        } catch(e) {}
                    } else {
                        fileData = data;
                    }

                    // console.log(fileData);

                    if ((fileData.status == 1) && (fileData.path != '') && (fileData.url != '')) {
                        // do something
                        fn.dz_pdf.applyItem(fileData);

                    } else {
                        if (fileData.redirect) {
                            document.location.href = fileData.redirect;
                        } else if (fileData.msg) {
                            notify.error(fileData.msg);
                        } else {
                            notify.error('File upload failed. Try another file.');
                        }
                    }

                }, complete: function (file) {
                    // console.log('complete___');
                    if (!file.accepted) {
                        fn.$dzObj_pdf.overlayDone();

                        if (file.size > 5*1024*1024) {
                            notify .error('File is too big. Try with smaller than 5Mbyte, please.');
                        } else {
                            notify .error('File is not in accepted format.');
                        }
                        
                        // console.log(fn.$dz_pdf);
                        fn.$dz_pdf.removeFile(file);
                    }
                }

            };

            fn.$dzObj_pdf = $('#dz_seller_pdf');
            fn.$dz_pdf = new Dropzone('#dz_seller_pdf', dzOpts);
            fn.$dzList_pdf = $('#dz_pdf_list');

            fn.$dzList_pdf.sortable({
                update: function( event, ui ) {
                    //
                }
            });
            

            fn.$dzList_pdf.on('click', 'li .item-del', function(e){
                e.preventDefault();
                fn.dz_pdf.deletePdf($(this));
            });

            fn.dz_pdf.initList();

        },

        initList: function() {
            if (pdfs) {
                for (i=0; i<pdfs.length; i++) {
                    fn.dz_pdf.applyItem({
                        'name': pdfs[i]['name'],
                        'path': pdfs[i]['path'],
                        'url': pdfs[i]['url']
                    });
                }
            }
        },

        applyItem: function(fileData) {
            // 
            var li_html = `
            <li class="item">
                <input type="hidden" name="pdfs[]" value="` + fileData.path + `" class="value-file-path">
                <input type="hidden" name="names[]" value="` + fileData.name + `" class="value-file-path">
                ` + fileData.name + `
                &nbsp;&nbsp;&nbsp;
                <a href="` + fileData.url + `" class="command item-view" title="View" target="_blank"><i class="fa fa-external-link"></i></a>
                <a href="#" class="command item-del" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </li>
            `;
            fn.$dzList_pdf.append(li_html);
            fn.seller.monitor();
        },

        deletePdf: function($obj) {
            var $li = $obj.closest('li');
            $li.remove();
            fn.seller.monitor();
        }
    },

    init: function() {
        fn.dz_pictures.init();
        fn.dz_pdf.init();
        fn.seller.init();
    }
}

fn.init();