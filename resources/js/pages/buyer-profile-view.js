var fn = {

    $socialList: null,

    socials: {
        init: function() {
            fn.$socialList = $('#social_slide');

            if ((fn.$socialList.find('.social-item').length % 2) == 1) {
                fn.$socialList.append('<div></div>');
            }
   
            fn.$socialList.slick({
              dots: true,
              infinite: false,
              // speed: 300,
              slidesToShow: 2,
              slidesToScroll: 2,
              centerMode: false,
              variableWidth: false
            });
        }
    },

    init: function() {
        fn.socials.init();
    }
}


fn.init();


