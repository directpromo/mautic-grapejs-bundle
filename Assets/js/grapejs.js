
Mautic.lanunchBuilderCore = Mautic.launchBuilder;
Mautic.launchBuilderCustom = function (formName, actionName) {
    var currentActiveTemplate = mQuery('.theme-selected').find('.select-theme-link').attr('data-theme');
    var builderUrl = (mQuery('#builder_url').val()).replace('s/emails/','s/grapejs/')+'?template=' + currentActiveTemplate;

    Mautic.loadNewWindow({
        "windowUrl": builderUrl+"&t="+new Date().getTime()
    });
}

Mautic.launchBuilder = function (formName, actionName) {

    if (actionName !== 'email' ||  mQuery('.theme-selected').find('.select-theme-link').attr('data-theme') === 'mautic_code_mode') {
        Mautic.lanunchBuilderCore(formName, actionName);
    }else{
        Mautic.launchBuilderCustom(formName, actionName);
    }
};