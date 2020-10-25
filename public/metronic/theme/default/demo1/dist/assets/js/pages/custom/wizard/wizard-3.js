"use strict";
var KTWizard3 = (function () {
    var e, r, t;
    return {
        init: function () {
            var i;
            KTUtil.get("kt_wizard_v3"),
                (e = $("#kt_form")),
                (t = new KTWizard("kt_wizard_v3", {
                    startStep: 1,
                    clickableSteps: !0,
                })).on("beforeNext", function (e) {
                    !0 !== r.form() && e.stop();
                }),
                t.on("beforePrev", function (e) {
                    !0 !== r.form() && e.stop();
                }),
                t.on("change", function (e) {
                    KTUtil.scrollTop();
                }),
                (r = e.validate({
                    ignore: ":hidden",
                    rules: {},
                    invalidHandler: function (e, r) {
                        KTUtil.scrollTop(),
                            swal.fire({
                                title: "",
                                text:
                                    "There are some errors in your submission. Please correct them.",
                                type: "error",
                                confirmButtonClass: "btn btn-secondary",
                            });
                    },
                    submitHandler: function (e) {},
                })),
                (i = e.find('[data-ktwizard-type="action-submit"]')).on(
                    "click",
                    function (t) {
                        console.log("Saefg");
                        $("#kt_form").submit();
                        // t.preventDefault(),
                        // r.form() &&
                        //     (KTApp.progress(i),
                        //     e.ajaxSubmit({
                        //         success: function () {
                        //             KTApp.unprogress(i),
                        //                 swal.fire({
                        //                     title: "",
                        //                     text:
                        //                         "The application has been successfully submitted!",
                        //                     type: "success",
                        //                     confirmButtonClass:
                        //                         "btn btn-secondary",
                        //                 });
                        //         },
                        //     }));
                    }
                );
        },
    };
})();
// jQuery(document).ready(function () {
//     // KTWizard3.init();
//     wizard = new KTWizard("kt_wizard_v3", {
//         startStep: 1, // Initial active step number
//         clickableSteps: true, // Allow step clicking
//     }).on("beforeNext", function (e) {
//         if (e.currentStep == 1) {
//             console.log(e);
//             $.ajax({
//                 type: "POST",
//                 url: "/lightspeed-auth-api/settings",
//                 data: {
//                     api_key: $('[name="lightspeed[api_key]"]').val(),
//                     api_secret: $('[name="lightspeed[api_secret]"]').val(),
//                 },
//                 success: function (data) {
//                     goNext();
//                 },
//                 error: function (data) {
//                     goNext();
//                 },
//             });
//         }
//     });
// });
