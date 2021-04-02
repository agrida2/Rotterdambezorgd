$(function(){
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var osTitle = $('.restaurant-title').offset().top;
        var htTitle = $('.restaurant-title').height();
        if(scroll > osTitle - htTitle){
            $('#cart').addClass('fixed-cart');
        }
        if(scroll < osTitle - htTitle){
            $('#cart').removeClass('fixed-cart');
        }

        var htBanner = $('.restaurant-banner').height();
        if(scroll > htBanner){
            $('#category-menu').addClass('category-menu-fixed');
            $('#restaurant-banner').addClass('restaurant-banner-margin');
        }
        if(scroll < htBanner){
            $('#category-menu').removeClass('category-menu-fixed');
            $('#restaurant-banner').removeClass('restaurant-banner-margin');
        }
    });
});
