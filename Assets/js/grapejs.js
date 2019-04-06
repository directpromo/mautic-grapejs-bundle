
Mautic.lanunchBuilderCore = Mautic.launchBuilder;
Mautic.launchBuilderCustom = function (formName, actionName) {
    Mautic.loadNewWindow({
        "windowUrl": mauticBasePath+"/plugins/MauticGrapeJsBundle/grapesjs-newsletter/index.html?t="+new Date().getTime()
    });
}

Mautic.launchBuilder = function (formName, actionName) {
    if (mQuery('.theme-selected').find('.select-theme-link').attr('data-theme') === 'mautic_code_mode') {
        Mautic.lanunchBuilderCore(formName, actionName);
    }else{
        Mautic.launchBuilderCustom(formName, actionName);
    }
};