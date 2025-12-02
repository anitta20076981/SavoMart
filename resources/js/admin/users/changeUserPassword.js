"use strict";
require('../layouts/app');

var SavoMartUsersChangePassword = function () {
    return {
        init: function () { }
    }
}();

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartUsersChangePassword.init();
});
// $(function () {
//     app.options.validation.rules = {
//         current: {
//             required: true,
//         },
//         password: {
//             required: true,
//             minlength: 6,
//         },
//         password_confirm: {
//             equalTo: "#password",
//         },
//     };
//     app.options.validation.messages = {
//         current: {
//             required: "Please enter current password",
//         },
//         password: {
//             required: "Please enter password",
//             minlength: "Password must be minimum of 6 charecters",
//         },
//         password_confirm: {
//             equalTo: "Confirm password does not match",
//         },
//     };
//     $("#userPasswordForm").validate(app.options.validation);
// });