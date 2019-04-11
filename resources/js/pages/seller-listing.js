var fn = {
    
    $frm: null,
    $list: null,
    $listWrap: null,
    $pager: null,

    listing: {
        init: function() {
            fn.$list = $('#listings');
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
            fn.$frm = $('#frm_search');

            helper.initSlider($("#rev_ranger"), $('#_r0'), $('#_r1'), {label_template: '{%V}'});
            helper.initSlider($("#price_ranger"), $('#_c0'), $('#_c1'), {label_template: '{%V}'});
            helper.initSelectbox({selector: $('#_f')});

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
                        var item = res.data[i];
                        var $elem = $('<div class="col-md-4 col-sm-4 item"></div>');
                        var $a = $('<a>').attr('href', item.url).attr('target', '_blank');

                        $('<img>').attr('src', item.avatar).addClass('item-img').appendTo($a);
                        $('<div>').html(item.description).addClass('item-description').appendTo($a);
                        $('<div>').html(item.name).addClass('item-name').appendTo($a);
                        
                        $a.appendTo($elem);
                        $elem.appendTo(fn.$list);
                    }

                    helper.renderPagination(fn.$pager, res.total, res.current_page);

                    fn.$listWrap.removeClass('without-data').addClass('with-data');
                }
            });
        }
    },

    init: function() {
        fn.search.init();
        fn.listing.init();
    }
}

fn.init();



