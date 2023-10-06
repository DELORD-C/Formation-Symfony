$.fn.conditionize = function(options){

    var settings = $.extend({
        hideJS: true
    }, options );

    $.fn.showOrHide = function(listenTo, listenFor, $section, isRequired) {
        if ($(listenTo).is('select') && $(listenTo).attr('multiple') === 'multiple') {
            let state = true;
            for (let value of $(listenTo).val()) {
                if (value.match(listenFor)) {
                    handleRequired($section, isRequired, true);
                    state = false;
                }
            }
            if (state) {
                handleRequired($section, isRequired, false);
            }
        }
        else if ($(listenTo).is('input[type=checkbox]') && $(listenTo + ":checked").val().match(listenFor)) {
            handleRequired($section, isRequired, true)
        }
        else if ($(listenTo).val().match(listenFor) ) {
            handleRequired($section, isRequired, true)
        }
        else {
            handleRequired($section, isRequired, false)
        }


    }

    return this.each( function() {
        var listenTo = "#" + $(this).data('cond-option');
        var listenFor = $(this).data('cond-value');
        var isRequired = $(this).data('required');
        var $section = $(this);

        //Set up event listener
        $(listenTo).on('change', function() {
            $.fn.showOrHide(listenTo, listenFor, $section, isRequired);
        });
        $(listenTo).on('keyup', function() {
            $.fn.showOrHide(listenTo, listenFor, $section, isRequired);
        });
        //If setting was chosen, hide everything first...
        if (settings.hideJS) {
            $(this).hide();
        }
        //Show based on current value on page load
        $.fn.showOrHide(listenTo, listenFor, $section, isRequired);
    });
}

function handleRequired ($section, isRequired, toggle) {
    if (isRequired)
        for (let input of $section.find('input')) {
            if (toggle) {
                $(input).attr('required', 'required');
            }
            else {
                $(input).removeAttr('required');
            }
        }
    if (toggle) {
        $section.slideDown();
    }
    else {
        $section.slideUp();
    }
}

$('.conditional').conditionize();