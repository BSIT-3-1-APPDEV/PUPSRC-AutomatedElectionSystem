
let today = new Date();
today.setDate(today.getDate());

let oneYearAhead = new Date();
oneYearAhead.setFullYear(oneYearAhead.getFullYear() + 1);
oneYearAhead.setMonth(oneYearAhead.getMonth() + 1, 0);
oneYearAhead.setHours(23, 59, 59, 999);

let fiveYearsAhead = new Date();
fiveYearsAhead.setFullYear(fiveYearsAhead.getFullYear() + 5);
fiveYearsAhead.setMonth(fiveYearsAhead.getMonth() + 1, 0);
fiveYearsAhead.setHours(23, 59, 59, 999);




function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    var fitsLeft = (rect.left >= 0 && rect.left <= $(window).width());
    var fitsTop = (rect.top >= 0 && rect.top <= $(window).height());
    var fitsRight = (rect.right >= 0 && rect.right <= $(window).width());
    var fitsBottom = (rect.bottom >= 0 && rect.bottom <= $(window).height());
    return {
        bottom: fitsBottom,
        top: fitsTop,
        left: fitsLeft,
        right: fitsRight,
        all: (fitsLeft && fitsTop && fitsRight && fitsBottom)
    };
}

let datePickerInst = $(`#year-picker`).datepicker({
    classes: 'col-10 col-md-5 col-xl-4',
    position: 'bottom center',
    language: "en",
    view: 'years',
    minView: 'years',
    minDate: today,
    maxDate: fiveYearsAhead,
    clearButton: true,
    isMobile: true,
    autoClose: false,
    dateFormat: "yyyy",
    onSelect: function (formattedDate, date, inst) {
        $(inst.el).trigger('input');
    },
    onShow: function (inst, animationComplete) {

        if (!animationComplete) {
            var iFits = false;
            // Loop through a few possible position and see which one fits
            $.each(['bottom center', 'right center', 'right bottom', 'right top', 'top center'], function (i, pos) {
                if (!iFits) {
                    inst.update('position', pos);
                    var fits = isElementInViewport(inst.$datepicker[0]);
                    if (fits.all) {
                        iFits = true;
                    }
                }
            });
        }
        let thisPicker = document.getElementsByClassName('datepicker');
        let input = document.getElementById('year-picker');
        let inputWidth = input.offsetWidth;
        for (let i = 0; i < thisPicker.length; i++) {
            let element = thisPicker[i];
            if (inputWidth > 250) {
                element.style.width = inputWidth + 'px';
            } else {
                element.style.width = 250 + 'px';
            }

        }
    },
});

