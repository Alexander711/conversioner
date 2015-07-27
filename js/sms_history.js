$(function () {
    $("#datepicker_beginning").datepicker({
        showOn: "button",
        buttonImage: "../images/calendar.gif",
        buttonImageOnly: true,
        buttonText: "Select date",
        dateFormat: "dd-mm-yy"
    });

    $("#datepicker_end").datepicker({
        showOn: "button",
        buttonImage: "../images/calendar.gif",
        buttonImageOnly: true,
        buttonText: "Select date",
        dateFormat: "dd-mm-yy"
    });
});