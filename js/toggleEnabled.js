/**
*   Update enabled fields for sitemap types.
*
*   @param  object  cbox    Checkbox
*   @param  string  id      Sitemap ID, e.g. plugin name
*   @param  string  type    Type of sitemap (XML or HTML)
*/
var SMAP_toggleEnabled = function(cbox, id, type) {
    oldval = cbox.checked ? 0 : 1;
     var dataS = {
        "action" : "toggleEnabled",
        "id": id,
        "type": type,
        "oldval": oldval,
    };
    data = $("form").serialize() + "&" + $.param(dataS);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_admin_url + "/plugins/sitemap/ajax.php",
        data: data,
        success: function(result) {
            // Set the ID of the updated checkbox
            spanid = result.type + "_ena_" + result.id;
            chk = result.newval == 1 ? true : false;
            document.getElementById(spanid).checked = chk;
            try {
                $.UIkit.notify("<i class='uk-icon-check'></i>&nbsp;" + result.statusMessage, {timeout: 1000,pos:'top-center'});
            }
            catch(err) {
                alert(result.statusMessage);
            }
        }
    });
    return false;
};


/**
*   Update the sitemap frequency when the selection is changed
*
*   @param  string  id      ID of sitemap, e.g. plugin name
*   @param  string  newfreq New frequency (weekly, daily, etc.)
*/
var SMAP_updateFreq= function(id, newfreq) {
     var dataS = {
        "action" : "updatefreq",
        "id": id,
        "newfreq": newfreq,
    };
    data = $("form").serialize() + "&" + $.param(dataS);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_admin_url + "/plugins/sitemap/ajax.php",
        data: data,
        success: function(result) {
            // No change to form content, just display a message
            try {
                $.UIkit.notify("<i class='uk-icon-check'></i>&nbsp;" + result.statusMessage, {timeout: 1000,pos:'top-center'});
            }
            catch(err) {
                // Form is already updated, annoying popup message not needed
                // alert(result.statusMessage);
            }
        }
    });
    return false;
};

/**
*   Update the sitemap priority when the selection is changed
*
*   @param  string  id      ID of sitemap, e.g. plugin name
*   @param  string  newfreq New priority (0.5, 0.6, etc).
*/
var SMAP_updatePriority = function(id, newpriority) {
     var dataS = {
        "action" : "updatepriority",
        "id": id,
        "newpriority": newpriority,
    };
    data = $("form").serialize() + "&" + $.param(dataS);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_admin_url + "/plugins/sitemap/ajax.php",
        data: data,
        success: function(result) {
            // No change to form content, just display a message
            try {
                $.UIkit.notify("<i class='uk-icon-check'></i>&nbsp;" + result.statusMessage, {timeout: 1000,pos:'top-center'});
            }
            catch(err) {
                // Form is already updated, annoying popup message not needed
                // alert(result.statusMessage);
            }
        }
    });
    return false;
};
