SavoMartJson.validators = [];

SavoMartJson.options.FormValidation = {
    plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        // tachyons: new FormValidation.plugins.Tachyons({
        //     rowSelector: '.fv-row',
        // }),
        declarative: new FormValidation.plugins.Declarative({}),
        bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: '.fv-row',
            eleInvalidClass: '',
            eleValidClass: ''
        }),
    }
};