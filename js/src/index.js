// const hello = require('./hello');
//
// $.ajax({
//     url: "/api/getWeather",
//     data: {
//         zipcode: 97201
//     },
//     success: function( result ) {
//         console.log(result);
//     },
//     complete: function (xhr, status) {
//         console.log('dead');
//     }
// });
//
// hello('world');
import * as $ from "jquery"
import form from "./form"

$(document).ready(() => {
    $("[name=console]").on("input", function () {
        form.checkConsole($(this));
    });
    $("[name=image]").on("change", function () {
        if ($(this)[0].files[0]) {
            $(this).siblings(".file_info").html($(this)[0].files[0].name);
        } else {
            $(this).siblings(".file_info").html("");
        }
    });
});