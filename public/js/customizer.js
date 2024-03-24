(function (jQuery) {
    "use strict";
    // data-mode="click" for using event
    // data-dark="false" for property
    // icon class // la-sun // la-moon
    const storageDark = localStorage.getItem('dark')
    if($('body').hasClass('dark')){
        changeMode('true');
    } else {
        changeMode('false');
    }
    if (storageDark !== 'null') {
        changeMode(storageDark)
    }
    jQuery(document).on("change", '.change-mode input[type="checkbox"]' ,function (e) {
        const dark = $(this).attr('data-active');
        if (dark === 'true') {
            $(this).attr('data-active','false')
        } else {
            $(this).attr('data-active','true')
        }
        changeMode(dark)
    })
    function changeMode (dark) {
        const body = jQuery('body')

        if (dark === 'true') {
            $('#dark-mode').prop('checked', true).attr('data-active', 'false')
            $('.darkmode-logo').removeClass('d-none')
            $('.light-logo').addClass('d-none')
            if (document.getElementsByClassName("toggle-mode").length > 0) {
                $('.toggle-mode').removeClass('light-mode-trashed')
                $('.toggle-mode').addClass('dark-mode-trashed')
            }
            body.addClass('dark')
            dark = true
        } else {
            $('.toggle-mode').addClass('light-mode-trashed')
            $('#dark-mode').prop('checked', false).attr('data-active', 'true');
            $('.light-logo').removeClass('d-none')
            $('.darkmode-logo').addClass('d-none')
            body.removeClass('dark')
            if(body.hasClass('toggle-light-mode')){
                if (document.getElementsByClassName("toggle-mode").length > 0) {
                    $('.toggle-mode').removeClass('dark-mode-trashed')
                    $('.toggle-mode').addClass('light-mode-trashed')
                }
            }
            dark = false
        }
        updateLocalStorage(dark)
        const event = new CustomEvent("ChangeColorMode", {detail: {dark: dark} });
        document.dispatchEvent(event);
    }
    function updateLocalStorage(dark) {
        localStorage.setItem('dark', dark)
    }
    
})(jQuery)