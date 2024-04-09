const MAIN_CONTENT_MARGIN = $('.content-margin');
const SIDEBAR = $(sidebar);

function setMainContentMargin() {
    if (!$('.sidebar').hasClass('close')) {
        MAIN_CONTENT_MARGIN.css('margin', `1.75rem calc(4rem + 5vw - ${SIDEBAR.width()}px * 0.3)`);
    } else {
        MAIN_CONTENT_MARGIN.css('margin', '1.75rem calc(4rem + 5vw)');
    }
}


setMainContentMargin();
$(sidebarClose).on('click', function () {
    setMainContentMargin();
});




