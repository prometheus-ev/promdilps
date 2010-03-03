function FieldUpdater(targetClass, url, waitImage, sessionid, queryid) {
    this.targetClass = targetClass;
    this.url = url;
    this.waitImage = waitImage;
    this.sessionid = sessionid;
    this.queryid = queryid;
    this.debug = false;  // for this to work: include debug.js 
}

FieldUpdater.prototype = {
    updateAll: function() {
        var els = document.getElementsByClassName(this.targetClass);
        var waitIndicator = '';
        if (this.waitImage != null) {
            waitIndicator = '<img src="'+this.waitImage+'" />';
        }
        var params = 'PHPSESSID='+this.sessionid+'&queryid='+this.queryid;
        var collection = new Array();
        els.each( function(field) {
                    if (this.waitImage != null) {
                        Element.update(field, waitIndicator);
                    }           
                    collection = field.id.split('_');
                    params += "&collectionid="+collection[1];
                    this.updateFieldFromServer(field, params);
                 }.bind(this));
    },
    setDebug: function(boolVal) {
        this.debug = boolVal;
    },
    updateFieldFromServer: function(field, params) {
        var updater = new Ajax.Updater(
        	{success: field.id}, 
        	this.url, 
        	{
        		method: 'get', 
        		parameters: params, 
        		onFailure: function (xhr, response){ this.ajaxUpdateFailure(field.id, xhr, response);}.bind(this),
        		onException: function (xhr, exception){ this.ajaxUpdateException(field.id, xhr, exception);}.bind(this)
        	});
        return updater;
    },
    ajaxUpdateFailure: function(elementId, xhr, response) {

        // update the field with a failure message ...
        Element.update(elementId, "? (failed)");
        
        if (this.debug) {
            var info = 'function: ajaxUpdateFailure<br/>'
                  + toString($(elementId), elementId, 1)
                  + toString(xhr, 'xhr', 1)
                  + toString(response, 'response', 1)
            showInfo(info);
        }
    },
    ajaxUpdateException: function(elementId, xhr, exception) {

        // update the field with a failure message ...
        Element.update(elementId, "? (failed:exception)");
        
        if (this.debug) {
            var info = 'function: ajaxUpdateException<br/>'
                  + toString($(elementId), elementId, 1)
                  + toString(xhr, 'xhr', 1)
                  + toString(exception, 'exception', 1)
            showInfo(info);
        }
    }
}
