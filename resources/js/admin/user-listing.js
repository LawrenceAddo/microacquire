window.fn = {
    
    $frm: null,
    $list: null,
    $listWrap: null,
    $pager: null,

    listing: {
        init: function() {
            fn.$frm = $('#frm_search');
            fn.$list = $('#listings table tbody');
            fn.$listWrap = $('#listing_wrapper');
            fn.$pager = $('#listings_pager');

            fn.$pager.on('click', 'a', function(e){
                e.preventDefault();

                var $me = $(this);
                if ($me.hasClass('disabled')) return;
                var page = parseInt($me.attr('data-page'));
                if (isNaN(page) || (page == 0)) return;

                $('#_page').val(page);
                fn.search.do();
            });


            fn.$frm.submit(function() {
                fn.search.do();
                return false;
            });

        },
        monitor: function() {
            
        }
    },

    search: {
        init: function() {

            helper.initSelectbox({selector: $('#_t')});
            helper.initSelectbox({selector: $('#_s')});
            helper.initSelectbox({selector: $('#_f')});
            helper.initSelectbox({selector: $('#_es')});

            fn.search.do();
        },

        do: function() {
            
            var actionBar = `<div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle tight" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item lnk-approve" href="#">Approve Profile</a>
                                <a class="dropdown-item lnk-reject" href="#">Reject Profile</a>
                                <div class="dropdown-divider for-profile"></div>
                                <a class="dropdown-item lnk-send-verif" href="#">Send Verification Email</a>
                                <a class="dropdown-item lnk-login" href="#">Login</a>
                                <a class="dropdown-item lnk-delete" href="#">Delete</a>
                              </div>
                            </div>`;
            var chk = `<div class="checkbox">
                          <label>
                               <input type="checkbox" value="" class="form-control" name="item">
                               <span class="cr"><i class="cr-icon cr-icon-checked fa fa-check"></i></span>
                           </label>
                        </div>`;
            var inner =  `<td class="td-no">` + chk + `</td>
                        <td class="td-name"></td>
                        <td class="td-email"></td>
                        <td class="td-usertype"></td>
                        <td class="td-datetime td-created"></td>
                        <td class="td-company"></td>
                        <td class="text-center td-state"></td>
                        <td class="td-datetime td-profile-modifed"></td>
                        <td class="td-action">` + actionBar + `</td>`;
            
            var statusLbl = ['drafted', 'approved', 'requested', '', 'rejected'];
            var typeLbl = ['seller', 'buyer', 'admin'];

            $('body').overlay();

            ajaxGet(fn.$frm.attr('action') + '?' + fn.$frm.serialize(), function(res){
                //
                $('body').overlayDone();
                if (res.data.length == 0) {
                    fn.$listWrap.removeClass('with-data').addClass('without-data');
                } else {

                    fn.$list.html('');
                    for (var i=0; i<res.data.length; i++) {
                        var ind = res.per_page * (res.current_page - 1) + i + 1;
                        var item = res.data[i];

                        var $row = $('<tr>');
                        $row.attr('data-ref', item.id)
                            .html(inner)
                            .addClass('user-type-' + typeLbl[item.type])
                        ;

                        if (item.profile) {
                            $row.addClass('state-' + statusLbl[item.profile.status]);
                        } else {
                            $row.addClass('no-profile');
                        }
                        
                        $row.find('.td-no label').append(ind);
                        $row.find('.td-name').html(item.name);
                        $row.find('.td-email').html(item.email);
                        if (!item.email_verified_at) {
                            $row.find('.td-email').append('<span class="ticker ticker-error">Unverified</span>');
                        } else {
                            $row.find('.td-email').append('<span class="ticker ticker-okay">Verified</span>');
                        }
                        $row.find('.td-usertype').html('<span class="user-type-' + typeLbl[item.type] + '">' + typeLbl[item.type] + '</span>');
                        $row.find('.td-created').html(item.created_at);

                        if (item.profile) {
                            $row.find('.td-company').html(item.profile.company_name);

                            var $stt = $('<span class="signal-light"  data-toggle="tooltip" data-placement="bottom" title=""></span>');
                            if (item.profile.status == '1') {
                                $stt.addClass('light-okay').attr('title', 'Approved').tooltip()
                                    .appendTo($row.find('.td-state'));
                            } else if (item.profile.status == '2') {
                                $stt.addClass('light-info').attr('title', 'Requested').tooltip()
                                    .appendTo($row.find('.td-state'));
                            } else if (item.profile.status == '4') {
                                $stt.addClass('light-error').attr('title', 'Reason: ' + item.profile.reason).tooltip()
                                    .appendTo($row.find('.td-state'));
                            } else {
                                $stt.addClass('light-off').attr('title', 'Drafted').tooltip()
                                    .appendTo($row.find('.td-state'));
                            }

                            $row.find('.td-profile-modifed').html(item.profile.updated_at);    
                        }
                        
                        $row.appendTo(fn.$list);
                    }

                    helper.renderPagination(fn.$pager, res.last_page, res.current_page);

                    fn.$listWrap.removeClass('without-data').addClass('with-data');
                }
            });
        }
    },

    actions: {
        init: function() {
            fn.$listWrap.on('click', '.lnk-delete', function(e){
                e.preventDefault();
                fn.actions.beforeDelete($(this));
            });

            fn.$listWrap.on('click', '.lnk-login', function(e){
                e.preventDefault();
                fn.actions.beforeLogin($(this));
            });

            fn.$listWrap.on('click', '.lnk-approve', function(e){
                e.preventDefault();
                fn.actions.beforeApproveProfile($(this));
            });

            fn.$listWrap.on('click', '.lnk-reject', function(e){
                e.preventDefault();
                fn.actions.beforeRejectProfile($(this));
            });

            
        },

        beforeDelete: function ($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a class="btn btn-danger form-control" href="#" onclick="fn.actions.delete(' + rid + ');">Yes, Delete</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Delete user', {'target': $obj, 'wrap': $tr});
        },

        delete: function(id) {
            $('body').overlay();
            ajaxDelete($('#_url').val() + '/' + id, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        },

        beforeLogin: function ($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>You will have to re-login if you want to use admin panel. <br>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a class="btn btn-primary form-control" href="#" onclick="fn.actions.login(' + rid + ');">Yes, let\'s go!</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Login as another user', {'target': $obj, 'wrap': $tr});
        },

        login: function(id) {
            $('body').overlay();
            ajaxGet($('#_url').val() + '/login/' + id, function() {
                $('body').overlayDone();
            });
        },

        beforeApproveProfile: function($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>You are about to approve the profile. <br>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.approveProfile(' + rid + ');">Approve now!</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Approve profile', {'target': $obj, 'wrap': $tr});
        },

        approveProfile: function(id) {
            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id, {status: 1}, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        },

        beforeRejectProfile: function($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = `<div>
                <textarea placeholder="Reason to be rejected" id="txt_reject_reason" class="fancy" rows="5"></textarea>
                <p class="char-counter"><span class="metre">1000</span> character left</p>
            </div>`,
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.rejectProfile(' + rid + ');">Reject</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('info', html_content, html_footer, 'Approve profile', {'target': $obj, 'wrap': $tr});
            helper.initLimitedTyper($('#txt_reject_reason'));
        },

        rejectProfile: function(id) {
            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id, {status: 4, reason: $('#txt_reject_reason').val()}, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        }
    },

    init: function() {
        fn.listing.init();
        fn.search.init();
        fn.actions.init();
    }
}

fn.init();



