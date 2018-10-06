import * as $ from "jquery"
import form from "./form"

$(document).ready(() => {
    $("[name=console]").on("input", function () {
        form.checkConsole($(this));
    });
    $("[name=image]").on("change", function () {
        form.fileUpload($(this));
    });
    $(".post_form_container input[type=submit]").on("click", function () {
        form.saveFormData(
            $(this).siblings("[name=card_uid]").val(),
            $(this).siblings("[name=name]").val(),
            $(this).siblings("[name=console]").val()
        );
    });
    $(".post_form_container").each(function () {
        form.checkoutFormData($(this));
    });
});