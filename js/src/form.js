import * as r from 'ramda'

const fromValue = (fn) => ($from) => fn($from.val());

const withSplit = (sep, fn) => (str) => fn(str.split(sep));

const setDefault = ($from) => {
    $from.parents("fieldset").removeClass("consoleType");
    $from.parents("fieldset").removeClass("monaType");
};

const setManageConsole = ($from) => {
    $from.parents("fieldset").addClass("consoleType");
};

const setMona = ($from) => {
    $from.parents("fieldset").addClass("monaType");
};

const checkConsole = r.cond([
    [fromValue(withSplit(".", r.contains("manage"))), setManageConsole],
    [fromValue(withSplit(".", r.contains("aa"))), setMona],
    [r.T, setDefault]
]);

const fileUpload = ($file) => {
    if ($file[0].files[0]) {
        $file.siblings(".file_info").html($file[0].files[0].name);
    } else {
        $file.siblings(".file_info").html("");
    }
};

const loadFormData = () => JSON.parse(localStorage.getItem("bbsFormData")) || {};

const makeFormData = (cardUID, name, cons) => {
    return {
        "cardUID": cardUID,
        "name": name,
        "console": cons
    };
};

const saveFormData = (cardUID, name, cons) => {
    localStorage.setItem("bbsFormData", JSON.stringify(
        r.compose(
            r.append(makeFormData(cardUID, name, cons)),
            r.reject(r.propEq('cardUID', cardUID)),
        )(loadFormData())
    ));
};

const checkoutFormData = ($fieldset) => {
    r.compose(
        r.when(
            (raw) => r.not(r.isEmpty(raw)),
            (formData) => {
                $fieldset.find("[name=name]").val(formData[0].name);
                $fieldset.find("[name=console]").val(formData[0].console);
            }
        ),
        r.filter(r.propEq('cardUID', $fieldset.find("[name=card_uid]").val()))
    )(loadFormData());
};

export default {
    checkConsole,
    fileUpload,
    saveFormData,
    checkoutFormData
};
