/**
 * Time Traveler plugin for Craft CMS
 *
 * TimeZone General JS
 *
 * @author    Mato Tominac
 * @copyright Copyright (c) 2021 Mato Tominac
 * @link      https://frontend.services/
 * @package   TimeTraveler
 * @since     1.0.0TimeTravelerTimeZone
 */

;(function ( $, window, document, undefined ) {

    function appendTimezoneDropdowns() {
        var dateFields = '.input > .timewrapper, .datetimewrapper > .timewrapper',
            selectOpenTag = '<div class="select js--timezone-select" style="margin-left: 5px;"><select class="js--update-timezone">',
            selectCloseTag = '</select></div>';

        $(dateFields).each(function(){
            var dateTimeWrapper = $(this).closest('.datetimewrapper');

            if (dateTimeWrapper.length) {
                // Date and Time field

                dateTimeWrapper = $(dateTimeWrapper[0]);
                if (dateTimeWrapper.find('.js--timezone-select').length === 0) {
                    var html = selectOpenTag;
                    window.tttimezones.forEach(function(e){
                        html += '<option value="'+e.timezone+'"'+(e.timezone==Craft.timezone?' selected':'')+'>'+e.label+'</option>';
                    });
                    html += selectCloseTag;

                    dateTimeWrapper.find('.timewrapper').after(html);
                }

            } else {
                // Only time field

                if ($(this).siblings('.js--timezone-select').length === 0) {
                    var html = selectOpenTag;
                    window.tttimezones.forEach(function(e){
                        html += '<option value="'+e.timezone+'"'+(e.timezone==Craft.timezone?' selected':'')+'>'+e.label+'</option>';
                    });
                    html += selectCloseTag;

                    $(this).after(html);
                }
            }

        });
    }

    appendTimezoneDropdowns();

    setInterval(function(){
        appendTimezoneDropdowns();
    }, 1500);

    $(document).on('change', '.js--update-timezone', function(){
        var val = $(this).val();

        var dateTimeWrapper = $(this).closest('.datetimewrapper');

        if (dateTimeWrapper.length) {
            dateTimeWrapper.find('[name$="[timezone]"]').val(val);
        } else {
            $(this).closest('.input').find('[name$="[timezone]"]').val(val);
        }
    });

})( jQuery, window, document );
