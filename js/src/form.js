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

export default {
    checkConsole
};
