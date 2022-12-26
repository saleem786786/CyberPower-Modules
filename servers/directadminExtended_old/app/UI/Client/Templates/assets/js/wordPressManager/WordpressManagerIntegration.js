/*
* Core js fw functions
* Do not edit this file
*/

/**
 * Available Actions List:
 * - AppsPreLoad
 * - ModalLoaded
 * - DatatableDataLoaded
 * - DatatableDataLoadingFailed
 * - SelectFieldValueChanged
 */

/*
 * Call
 */
var mgEventCallback = {
    objectId: null,
    eventType: null,
    callbackFunction :null,
    order: 1000,

    generateEvent: function(id, eventType, callbackFunction, order) {
        if ((id === null || typeof  id === 'string') && typeof eventType === 'string'
            && typeof callbackFunction === 'function') {
            this.objectId = id;
            this.eventType = eventType;
            this.callbackFunction = callbackFunction;
            this.order = (!order || typeof order !== 'number') ? 1000 : order;

            return this;
        } else {
            return null;
        }

    },
    runEventCallback: function(objectId, params) {
        this.callbackFunction(objectId, params);
    }
};

/*
 * Events Handler
 * collects and run all events callbacks in the app
 */
var mgEventHandler = {
    callbacks: {},

    on: function(eventType, id, callbackFunction, order) {
        var tmpCall = mgEventCallback.generateEvent(id, eventType, callbackFunction, order);
        var tempId = Object.keys(this.callbacks).length;
        this.callbacks['call_' + tempId] = Object.assign({}, tmpCall);
    },

    runCallback: function(eventType, id, callbackParams) {
        var callbackList = [];
        for (var key in this.callbacks) {
            if (!this.callbacks.hasOwnProperty(key)) {
                continue;
            }

            var tmpCallback = Object.assign({}, this.callbacks[key]);
            if (tmpCallback.eventType !== eventType || (tmpCallback.objectId !== null && tmpCallback.objectId !== id)){
                continue;
            } else if(tmpCallback.objectId !== null && tmpCallback.objectId === id) {
                callbackList.push(tmpCallback);
            } else {
                callbackList.push(tmpCallback);
            }
        }

        callbackList.sort(function(a, b){return a.order - b.order});
        $(callbackList).each(function(){
            this.runEventCallback(id, callbackParams);
        });
    }
};



//wp details
function wpSslChange(data) {
    window.location.reload();
}



function wpOnChangeDomainAjaxDone(data) {
    window.location.reload();
}

function wpInstallationdeleteAjaxDone(data) {
    window.location.href='index.php?m=WordpressManager';
}
//Change Domain
$(".mg-wrapper").on("change","select[name='domain']",function(e){
    if($(this).val()!="0" && $(".mg-wrapper input[name='newDomain']").size()){
        $(".mg-wrapper input[name='newDomain']").prop("disabled",true);
        $(".mg-wrapper input[name='newDomain']").parent('div').children('.lu-form-label').addClass("disabled");
        $(".mg-wrapper input[name='subDomain']").prop("disabled",true);
        $(".mg-wrapper input[name='subDomain']").parent('div').children('.lu-form-label').addClass("disabled");
        $(".mg-wrapper input[name='password']").prop("disabled",true);
        $(".mg-wrapper input[name='password']").parent('div').children('.lu-form-label').addClass("disabled");
    }else if( $(".mg-wrapper input[name='newDomain']").size()){
        $(".mg-wrapper input[name='newDomain']").prop("disabled",false);
        $(".mg-wrapper input[name='newDomain']").parent('div').children('.lu-form-label').removeClass("disabled");
        $(".mg-wrapper input[name='subDomain']").prop("disabled",false);
        $(".mg-wrapper input[name='subDomain']").parent('div').children('.lu-form-label').removeClass("disabled");
        $(".mg-wrapper input[name='password']").prop("disabled",false);
        $(".mg-wrapper input[name='password']").parent('div').children('.lu-form-label').removeClass("disabled");
    }
});

function wpSslChange(data) {
    window.location.reload();
}

$(".mg-wrapper").on("change","select[name='domain']",function(e){

});

$(".mg-wrapper").on("change","input[name=push_db]",function(e){
    var pushFull = $(this).is(":checked");
    if(pushFull){
        $("#pushToLiveForm .lu-form-group").eq(0).addClass('disabled');
        $("#pushToLiveForm .lu-form-group").eq(1).addClass('disabled');
    }else{
        $("#pushToLiveForm .lu-form-group").eq(0).removeClass('disabled');
        $("#pushToLiveForm .lu-form-group").eq(1).removeClass('disabled');
    }
});


//plugins
function wpOnPluginInstalledAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState = ['mg-plugins-installed'];
    mgPageControler.vueLoader.runRefreshActions();
}

mgEventHandler.on('DatatableDataLoaded', 'mg-plugin-install', function(id, params){
    if($('#mg-plugin-install #groupListFilter').val().length === 0){
        $("#mg-plugin-install .alert-info").show();
    }else{
        $("#mg-plugin-install .alert-info").hide();
    };
}, 1000);

//Themes
function wpOnThemeInstalledAjaxDone(data) {
    mgPageControler.vueLoader.refreshingState = ['mg-theme-installed'];
    mgPageControler.vueLoader.runRefreshActions();
}
mgEventHandler.on('DatatableDataLoaded', 'mg-theme-install', function(id, params){
    if($('#mg-theme-install #groupListFilter').val().length === 0){
        $("#mg-theme-install .alert-info").show();
    }else{
        $("#mg-theme-install .alert-info").hide();
    };
}, 1000);



//Push To Live WordPress
mgEventHandler.on('ModalLoaded', 'pushToLiveButton', function(id, params){

    if(id != 'pushToLiveButton'){
        return;
    }
    $(".mg-wrapper input[name=custom_push]").trigger('change');

}, 1000);
$(".mg-wrapper").on("change","input[name=custom_push]",function(e){
    var show = $(this).is(":checked");
    $("#pushToLiveForm .lu-form-group").toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(1).toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(2).toggle(show);
    $("#pushToLiveForm .lu-form-check").eq(3).toggle(show);
});

function wpOnInstallationCreated(data) {
    if(!$("#productDataTable").size()){
        return;
    }
    mgPageControler.vueLoader.refreshingState = ['productDataTable'];
    mgPageControler.vueLoader.runRefreshActions();
}

function wpIntegrationInstallationDeleteAjaxDone(data) {
    window.location.href='clientarea.php?action=productdetails&id=' + data['htmlData'] +'&modop=custom&a=management&mg-page=wordPressManager';
}