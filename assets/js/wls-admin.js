(function($){
    $( window ).resize(function() {
        HeightResizeWlsPreview();
    });

    $(function(){
        $(".rt-tab-nav li:first-child a").trigger('click');
        renderWlsPreview();
    });
    if($(".rt-color").length){
        $(".rt-color").wpColorPicker();
    }
    if($("#sc-wls-style .rt-color").length){
        var cOptions = {
            defaultColor: false,
            change: function(event, ui){
                renderWlsPreview();
            },
            clear: function() {
                renderWlsPreview();
            },
            hide: true,
            palettes: true
        };
        $("#sc-wls-style .rt-color").wpColorPicker(cOptions);
    }
    if($(".rt-select2").length){
        $(".rt-select2").select2({
            theme: "classic",
            minimumResultsForSearch: Infinity
        });
    }

    var fixHelper = function(e, ui) {
        ui.children().children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    if($('.post-type-wlshowcase table.posts #the-list').length) {
        $('.post-type-wlshowcase table.posts #the-list').sortable({
            'items': 'tr',
            'axis': 'y',
            'helper': fixHelper,
            'update': function (e, ui) {
                var order = $('#the-list').sortable('serialize');
                jQuery.ajax({
                    type: "post",
                    url: ajaxurl,
                    data: order + "&action=wls-logo-update-menu-order",
                    beforeSend: function () {
                        $('body').append($("<div id='wls-loading'><span class='wls-loading'>Updating ...</span></div>"));
                    },
                    success: function (data) {
                        $("#wls-loading").remove();
                    }
                });
            }
        });
    }

    $("#wpl_spsc_sc_settings_meta").on('change', 'select,input', function(){
        renderWlsPreview();
    });
    $("#wpl_spsc_sc_settings_meta").on("input propertychange",function(){
        renderWlsPreview();
    });

    function renderWlsPreview(){
        if($("#wpl_spsc_sc_settings_meta").length) {
            var data = $("#wpl_spsc_sc_settings_meta").find('input[name],select[name],textarea[name]').serialize();
            $(".rt-loading").remove();
            $(".rt-response").addClass('loading');
            $(".rt-response").html('<span>Loading...</span>');
            wlsAjaxCall(null, 'loadWlsPreview', data, function (data) {
                console.log(data);
                if (!data.error) {
                    $("#wls-sc-preview").html(data.data);
                }
                $(".rt-response").removeClass('loading');
                $(".rt-response").html('');
            });
        }
    }


    $("#wls_image_resize").on('change', function(){
            wlsImageResizeOption();
    });
    $("#wls_layout").on('change', function(){
        wlsCarouselOption();
    });

    wlsImageResizeOption();
    wlsCarouselOption();


    $(".rt-tab-nav li").on('click', 'a', function(e){
        e.preventDefault();
        var container = $(this).parents('.rt-tab-container');
        var nav = container.children('.rt-tab-nav');
        var content = container.children(".rt-tab-content");
        var $this, $id;
        $this = $(this);
        $id = $this.attr('href');
        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });

    $(window).scroll(function() {
        var height = $(window).scrollTop();
        if(height  > 50) {
            $('.post-type-wlshowcasesc div#submitdiv').addClass('sticky');
        }else{
            $('.post-type-wlshowcasesc div#submitdiv').removeClass('sticky');
        }
    });

    function wlsImageResizeOption(){
        if($("#wls_image_resize").is(":checked")){
            $(".wls_image_resize_field").show();
        }else{
            $(".wls_image_resize_field").hide();
        }
    }
    function wlsCarouselOption(){
        if($("#wls_layout").length ) {
            var layout = $("#wls_layout").val(),
                isCarousel = layout.match(/^carousel/i);
            if (isCarousel) {
                $(".wls_carousel_options_holder").show();
            } else {
                $(".wls_carousel_options_holder").hide();
            }
        }
    }



})(jQuery);

function wlsLoadPreviewLayout(){
    HeightResizeWlsPreview();
    var carousel = jQuery("#wpls-carousel");
    if(carousel.length){
        var options = carousel.data('slider');
        carousel.imagesLoaded( function() {
            HeightResizeWlsPreview();
            carousel.owlCarousel({
                slideBy: options.slidesToScroll ? options.slidesToScroll : 1,
                nav: options.nav,
                dots: options.dots,
                autoplay: options.autoplay,
                autoplayHoverPause: options.pauseOnHover,
                loop: options.loop,
                autoHeight: options.adaptiveHeight,
                lazyLoad: options.lazyLoad,
                center: options.centerMode,
                rtl: options.rtl,
                navText: ["<span class=\"dashicons dashicons-arrow-left-alt2\"></span>", "<span class=\"dashicons dashicons-arrow-right-alt2\"></span>"],
                responsiveClass: true,
                autoplayTimeout: options.autoPlayTimeOut,
                smartSpeed: options.speed,
                responsive: {
                    0: {
                        items: options.slidesToShowMobile ? options.slidesToShowMobile : 1
                    },
                    767: {
                        items: options.slidesToShowTab ? options.slidesToShowTab : 2
                    },
                    991: {
                        items: options.slidesToDesk ? options.slidesToDesk : 3
                    }
                }
            });
        });
    }
    var $isotope = jQuery('#wls-isotope');
    if($isotope.length){
        var isotope = $isotope.imagesLoaded( function() {
            HeightResizeWlsPreview();
            isotope.isotope({
                itemSelector: '.isotope-item',
            });
        });
        jQuery('#wls-iso-button').on( 'click', 'button', function(e) {
            e.preventDefault();
            var filterValue = jQuery(this).attr('data-filter');
            isotope.isotope({ filter: filterValue });
            jQuery(this).parent().find('.selected').removeClass('selected');
            jQuery(this).addClass('selected');
        });
    }
}
function HeightResizeWlsPreview(){

    var rtMaxH = 0;
    jQuery("#wls-sc-preview").find(".rt-equal-height").height("auto");
    jQuery("#wls-sc-preview").find('.rt-equal-height').each(function(){
        var $thisH = jQuery(this).actual( 'outerHeight' );
        if($thisH > rtMaxH){
            rtMaxH = $thisH;
        }
    });
    jQuery("#wls-sc-preview").find(".rt-equal-height").css('height', rtMaxH + "px");

    jQuery(document).ready(function(){
        jQuery('.wls-tooltip').hover(
            function() {
                var $this = jQuery( this );
                var $title = $this.attr('data-title');
                $tooltip = '<div class="rt-tooltip">' +
                    '<div class="rt-tooltip-content">'+$title+'</div>'+
                    '<div class="rt-tooltip-bottom"></div>'+
                    '</div>';
                jQuery('body').append($tooltip);
                var $tooltip = jQuery('body > .rt-tooltip');
                var tHeight = $tooltip.outerHeight();
                var tBottomHeight = $tooltip.find('.rt-tooltip-bottom').outerHeight();
                var tWidth = $tooltip.outerWidth();
                var tHolderWidth = $this.outerWidth();
                var top = $this.offset().top - (tHeight + tBottomHeight);
                var left = $this.offset().left;
                $tooltip.css('top', top + 'px');
                $tooltip.css('left', left + 'px');
                $tooltip.css('opacity', 1);
                $tooltip.show();
                if (tWidth <= tHolderWidth) {
                    var itemLeft = (tHolderWidth - tWidth) / 2;
                    left = left + itemLeft;
                    $tooltip.css('left', left + 'px');
                } else {
                    var itemLeft = (tWidth - tHolderWidth) / 2;
                    left = left - itemLeft;
                    if (left < 0) {
                        left = 0;
                    }
                    $tooltip.css('left', left + 'px');
                }
            }, function() {
                jQuery( "body > .rt-tooltip" ).remove();
            }
        );
    });
}



( function( global, $ ) {
    var editor,
        syncCSS = function() {
            wlsSyncCss();
        },
        loadAce = function() {
            $('.rt-custom-css').each(function(){
                var id = $(this).find('.custom-css').attr('id');
                editor = ace.edit( id );
                global.safecss_editor = editor;
                editor.getSession().setUseWrapMode( true );
                editor.setShowPrintMargin( false );
                editor.getSession().setValue( $(this).find('.custom_css_textarea').val() );
                editor.getSession().setMode( "ace/mode/css" );
            });

            jQuery.fn.spin&&$( '.custom_css_container' ).spin( false );
            $( '#post' ).submit( syncCSS );
        };
    if ( $.browser.msie&&parseInt( $.browser.version, 10 ) <= 7 ) {
        $( '.custom_css_container' ).hide();
        $( '.custom_css_textarea' ).show();
        return false;
    } else {
        $( global ).load( loadAce );
    }
    global.aceSyncCSS = syncCSS;
} )( this, jQuery );

function wlsSyncCss(){
    jQuery('.rt-custom-css').each(function(){
        var e = ace.edit( jQuery(this).find('.custom-css').attr('id') );
        jQuery(this).find('.custom_css_textarea').val( e.getSession().getValue() );
    });
}
function rtWLSSettings(e){
    wlsSyncCss();
    jQuery('rt-response').hide();
    var arg = jQuery( e ).serialize();
    var bindElement = jQuery('.rtSaveButton');
    wlsAjaxCall( bindElement, 'rtWLSSettings', arg, function(data){
        if(data.error){
            jQuery('.rt-response').addClass('updated');
            jQuery('.rt-response').removeClass('error');
            jQuery('.rt-response').show('slow').text(data.msg);
        }else{
            jQuery('.rt-response').addClass('error');
            jQuery('.rt-response').show('slow').text(data.msg);
        }
    });

}


function wlsAjaxCall( element, action, arg, handle){
    var data;
    if(action) data = "action=" + action;
    if(arg)    data = arg + "&action=" + action;
    if(arg && !action) data = arg;

    var n = data.search(wls.nonceID);
    if(n<0){
        data = data + "&"+ wls.nonceID + "=" + wls.nonce;
    }
    jQuery.ajax({
        type: "post",
        url: wls.ajaxurl,
        data: data,
        beforeSend: function() { jQuery("<span class='rt-loading'></span>").insertAfter(element); },
        success: function( data ){
            jQuery(".rt-loading").remove();
            handle(data);
        }
    });
}
