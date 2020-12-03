$(document).ready(function () {
    $('.value').maskMoney({
        prefix: "R$",
        decimal: ",",
        thousands: ""
    });
    $('.date').mask('00/00/0000');
});
