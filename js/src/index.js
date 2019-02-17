import * as $ from "jquery"
import form from "./form"
import linker from 'autolinker'

$(document).ready(() => {
    $("[name=console]").on("input", function () {
        form.checkConsole($(this));
    });
    $("[name=image]").on("change", function () {
        form.fileUpload($(this));
    });
    $("p.content").each(function () {
        $(this).html(linker.link($(this).html()));
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
        form.checkConsole($(this).find("[name=console]"));
    });
});