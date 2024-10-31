(function($) {
    tinymce.PluginManager.add('wls_scg', function( editor, url ) {
        var tlpsc_tag = 'logo-slider';


        //add popup
        editor.addCommand('wls_scg_popup', function(ui, v) {
            //setup defaults

            editor.windowManager.open( {
                title: 'WP Logo Slider ShortCode',
                width: jQuery( window ).width() * 0.3,
                height: (jQuery( window ).height() - 36 - 50) * 0.1,
                id: 'wls-sc-dialog',
                body: [
                    {
                        type   : 'container',
                        html   : '<span class="rt_loading">Loading...</span>'
                    },
                ],
                onsubmit: function( e ) {

                    var shortcode_str;
                    var id = jQuery("#scid").val();
                    var title = jQuery( "#scid option:selected" ).text();
                    if(id && id != 'undefined'){
                        shortcode_str = '[' + tlpsc_tag;
                            shortcode_str += ' id="'+id+'" title="'+ title +'"';
                        shortcode_str += ']';
                    }
                    if(shortcode_str) {
                        editor.insertContent(shortcode_str);
                    }else{
                        alert('No short code selected');
                    }
                }
            });

            putScList();
        });

        //add button
        editor.addButton('wls_scg', {
            icon: 'wls-scg',
            tooltip: 'Wp Logo Slider',
            cmd: 'wls_scg_popup',
        });

        function putScList(){
                var dialogBody = jQuery( '#wls-sc-dialog-body' )
                $.post( ajaxurl, {
                    action: 'wlsShortCodeList'
                }, function( response ) {
                    dialogBody.html(response);
                });

        }

    });
})(jQuery);