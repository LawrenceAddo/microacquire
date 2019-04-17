window.fn = {
    
    $frm: null,
    $list: null,
    $listWrap: null,
    $pager: null,

    lblNA: '&mdash;',

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


        actionBarUser: null,
        actionBarProfile: null,
        chk: null,
        inner: null,
        
        statusLbl: ['drafted', 'approved', 'requested', '', 'rejected'],
        typeLbl: ['seller', 'buyer', 'admin'],

        init: function() {

            fn.search.actionBarUser = `<div class="dropdown dropdown-action-user">
                              <button class="btn btn-secondary dropdown-toggle tight" type="button" id="dropdownMenuButtonUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonUser">
                                <a class="dropdown-item lnk-do-verif" href="#">Set Email Verified</a>
                                <a class="dropdown-item lnk-undo-verif" href="#">Set Email Un-Verified</a>
                                <a class="dropdown-item lnk-send-verif" href="#">Send Verification Email</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item lnk-login" href="#">Login</a>
                                <a class="dropdown-item lnk-delete" href="#">Delete</a>
                              </div>
                            </div>`;
            fn.search.actionBarProfile = `<div class="dropdown dropdown-action-profile">
                          <button class="btn btn-secondary dropdown-toggle tight" type="button" id="dropdownMenuButtonProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonProfile">
                            <a class="dropdown-item lnk-approve" href="#">Approve Profile</a>
                            <a class="dropdown-item lnk-reject" href="#">Reject Profile</a>
                          </div>
                        </div>`;
            fn.search.chk = `<div class="checkbox">
                      <label>
                           <input type="checkbox" value="" class="form-control" name="item">
                           <span class="cr"><i class="cr-icon cr-icon-checked fa fa-check"></i></span>
                       </label>
                    </div>`;
            fn.search.inner_profile = `<td class="td-company"></td>
                    <td class="text-center td-state"></td>
                    <td class="td-datetime td-profile-modifed"></td>
                    <td class="td-action td-action-profile"></td>`;
            fn.search.inner = `<td class="td-no">` + fn.search.chk + `</td>
                    <td class="td-name"></td>
                    <td class="td-email"></td>
                    <td class="td-usertype"></td>
                    <td class="td-datetime td-created"></td>
                    <td class="td-action td-action-user">` + fn.search.actionBarUser + `</td>
                    ` + fn.search.inner_profile;
            fn.search.inner_multi = `<td class="td-no">` + fn.search.chk + `</td>
                    <td class="td-name"></td>
                    <td class="td-email"></td>
                    <td class="td-usertype"></td>
                    <td class="td-datetime td-created"></td>
                    <td class="td-action td-action-user">` + fn.search.actionBarUser + `</td>
                    <td colspan="4" class="td-profile-set">
                      <table></table>
                    </td>
                    `;
            

            helper.initSelectbox({selector: $('#_t')});
            helper.initSelectbox({selector: $('#_s')});
            helper.initSelectbox({selector: $('#_f')});
            helper.initSelectbox({selector: $('#_es')});

            fn.search.do();
        },

        do: function() {

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
                        var user = res.data[i];

                        var $row = $('<tr>');
                        $row.attr('data-ref', user.id)
                            .addClass('user-type-' + fn.search.typeLbl[user.type])
                            .addClass(user.email_verified_at ? 'user-email-verified' : 'user-email-unverified')
                        ;
                        if (user.type == 0) {
                            if (user.sellings.length > 1) {
                                // $row.html(fn.search.inner_multi).addClass('state-multiple');
                                $row.html(fn.search.inner).addClass('state-multiple');
                            } else {
                                $row.html(fn.search.inner).addClass('state-' + fn.search.statusLbl[user.seller.status]);
                            }
                        } else if (user.profile) {
                            $row.html(fn.search.inner).addClass('state-' + fn.search.statusLbl[user.profile.status]);
                        } else {
                            $row.html(fn.search.inner).addClass('no-profile');
                        }
                        
                        $row.find('.td-no label').append(ind);
                        $row.find('.td-name').html(user.name);
                        $row.find('.td-email').html(user.email);
                        if (!user.email_verified_at) {
                            $row.find('.td-email').append('<span class="ticker ticker-error">Unverified</span>');
                        } else {
                            $row.find('.td-email').append('<span class="ticker ticker-okay">Verified</span>');
                        }
                        $row.find('.td-usertype').html('<span class="user-type-' + fn.search.typeLbl[user.type] + '">' + fn.search.typeLbl[user.type] + '</span>');
                        $row.find('.td-created').html(user.created_at);

                        if (user.type == 0) {
                            if (user.sellings.length > 1) {
                                for (var j=0; j<user.sellings.length; j++) {

                                    fn.search.fillTd($row, user.sellings[j], 0);

                                    /*
                                    var $innerRow = $('<tr>').html(fn.search.inner_profile);
                                    fn.search.fillTd($innerRow, user.sellings[j]);
                                    $innerRow.appendTo($row.find('.td-profile-set table'));
                                    */
                                }
                            } else {
                                fn.search.fillTd($row, user.seller, 0);
                            }
                        } else {
                            if (user.profile) {
                                user.profile.name = user.profile.company_name;
                                user.profile.description = user.profile.company_description;
                                fn.search.fillTd($row, user.profile, 1);
                            }
                        }

                        if (user.is_me) {
                            $row.find('.td-action').html('');
                        }

                        $row.appendTo(fn.$list);
                    }

                    helper.renderPagination(fn.$pager, res.last_page, res.current_page);

                    fn.$listWrap.removeClass('without-data').addClass('with-data');
                }
            });
        },

        fillTd: function($row, data, type) {

            var $namet = $('<span class="sub-row-td text-ellipsis" data-toggle="tooltip" data-placement="bottom" title=""></span>');
            $namet.html(data.name).attr('title', data.name + '<hr>' + nl2br(data.description)).fancyTooltip()
                    .appendTo($row.find('.td-company'));

            var $stt = $('<span class="signal-light" data-toggle="tooltip" data-placement="bottom" title=""></span>');
            if (data.status == '1') {
                $stt.addClass('light-okay').attr('title', 'Approved');
            } else if (data.status == '2') {
                $stt.addClass('light-info').attr('title', 'Requested')
            } else if (data.status == '4') {
                $stt.addClass('light-error').attr('title', 'Rejected<hr>' + nl2br(data.reason))
            } else {
                $stt.addClass('light-off').attr('title', 'Drafted');
            }
            $stt.fancyTooltip();
            $('<span class="sub-row-td"></span>').append($stt).appendTo($row.find('.td-state'));

            $row.find('.td-profile-modifed').append('<span class="sub-row-td">' + (data.updated_at ? data.updated_at : fn.lblNA) + '</span>'); 

            var urlSlug = (type == 0) ? 'sellers' : 'buyers';
            var vieEditLinks = `
                            <a target="_blank" class="dropdown-item lnk-view" href="/` + urlSlug + `/` + data.id + `">View Profile</a>
                            <a target="_blank" class="dropdown-item lnk-edit" href="/` + urlSlug + `/` + data.id + `/edit">Edit Profile</a>
                          `;

            $row.find('.td-action-profile')
                .append('<span data-sid="' + data.id + '" class="sub-row-td state-' + fn.search.statusLbl[data.status] + '">' + fn.search.actionBarProfile + '</span>')
                .find('[data-sid=' + data.id + '] .dropdown-action-profile .dropdown-menu')
                .append(vieEditLinks)
            ; 

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

            fn.$listWrap.on('click', '.lnk-do-verif', function(e){
                e.preventDefault();
                fn.actions.beforeVerifyEmail($(this));
            });

            fn.$listWrap.on('click', '.lnk-undo-verif', function(e){
                e.preventDefault();
                fn.actions.beforeUnVerifyEmail($(this));
            });

            fn.$listWrap.on('click', '.lnk-send-verif', function(e){
                e.preventDefault();
                fn.actions.beforeSendVerifyEmail($(this));
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
                    +   '<a class="btn btn-danger form-control" href="#" onclick="fn.actions.delete(' + rid + ');">Delete</a>'
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
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.login(' + rid + ');">Login</a>'
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

        beforeVerifyEmail: function ($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.verify(this);">Set Verified</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Set email verified', {uid: rid, 'target': $obj, 'wrap': $tr});
        },

        verify: function(me) {

            var $modal = $(me).closest('.modal-ma');
            var data = $modal.data('data');
            var id = data.uid;

            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id, {email_verified: 1}, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        },

        beforeUnVerifyEmail: function ($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-danger form-control" href="#" onclick="fn.actions.unverify(this);">Set Un-Verified</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Set email un-verified', {uid: rid, 'target': $obj, 'wrap': $tr});
        },

        unverify: function(me) {

            var $modal = $(me).closest('.modal-ma');
            var data = $modal.data('data');
            var id = data.uid;

            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id, {email_verified: 0}, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        },

        beforeSendVerifyEmail: function ($obj) {
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.sendVerifEmail(this);">Send</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Send verification email', {uid: rid, 'target': $obj, 'wrap': $tr});
        },

        sendVerifEmail: function(me) {

            var $modal = $(me).closest('.modal-ma');
            var data = $modal.data('data');
            var id = data.uid;

            $('body').overlay();
            ajaxPost($('#_url').val() + '/' + id + '/email/verif', {}, function() {
                $('body').overlayDone();
                notify.success('Verification email has been sent.');
            });
        },

        beforeApproveProfile: function($obj) {
            var $sbr = $obj.closest('.sub-row-td');
            var $tr = $obj.closest('tr');
            var rid = parseInt($tr.attr('data-ref'));
            if (isNaN(rid)) return;

            var html_content = "<div>You are about to approve the profile. <br>Are you sure?</div>",
                html_footer = '<div class="row">'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-default form-control">No</a>'
                    + '</div>'
                    + '<div class="col-sm-6 form-group">'
                    +   '<a data-dismiss="modal" class="btn btn-primary form-control" href="#" onclick="fn.actions.approveProfile(this);">Approve</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('alert', html_content, html_footer, 'Approve profile', {
                uid: rid,
                pid: $sbr.attr('data-sid'),
                'target': $obj, 
                'wrap': $tr
            });
        },

        approveProfile: function(me) {
            var $modal = $(me).closest('.modal-ma');
            var data = $modal.data('data');
            var id = data.uid;

            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id + '/profile', {status: 1, pid: data.pid}, function() {
                $('body').overlayDone();
                fn.search.do();
            });
        },

        beforeRejectProfile: function($obj) {
            var $sbr = $obj.closest('.sub-row-td');
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
                    +   '<a data-dismiss="modal" class="btn btn-danger form-control" href="#" onclick="fn.actions.rejectProfile(this);">Reject</a>'
                    + '</div>'
                    + '</div>';
            $.instantModal('info', html_content, html_footer, 'Approve profile', {
                uid: rid,
                pid: $sbr.attr('data-sid'),
                'target': $obj, 
                'wrap': $tr
            });
            helper.initLimitedTyper($('#txt_reject_reason'));
        },

        rejectProfile: function(me) {

            var $modal = $(me).closest('.modal-ma');
            var data = $modal.data('data');
            var id = data.uid;

            $('body').overlay();
            ajaxPatch($('#_url').val() + '/' + id + '/profile', {status: 4, reason: $('#txt_reject_reason').val(), pid: data.pid}, function() {
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



