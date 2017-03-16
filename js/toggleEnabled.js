 /*  Updates submission form fields based on changes in the category
 *  dropdown.
 */
var SMAP_xmlHttp;

function SMAP_toggleEnabled(cbox, id, type)
{
  oldval = cbox.checked ? 0 : 1;
  SMAP_xmlHttp=SMAP_GetXmlHttpObject();
  if (SMAP_xmlHttp==null) {
    alert ("Browser does not support HTTP Request")
    return
  }
  var url=site_admin_url + "/plugins/sitemap/ajax.php?action=toggleEnabled";
  url=url+"&id="+id;
  url=url+"&type="+type;
  url=url+"&oldval="+oldval;
  url=url+"&sid="+Math.random();
  SMAP_xmlHttp.onreadystatechange=SMAP_sc_toggleEnabled;
  SMAP_xmlHttp.open("GET",url,true);
  SMAP_xmlHttp.send(null);
}

function SMAP_sc_toggleEnabled()
{
  var newstate;

  if (SMAP_xmlHttp.readyState==4 || SMAP_xmlHttp.readyState=="complete") {
    jsonObj = JSON.parse(SMAP_xmlHttp.responseText)

    // Set the ID of the updated checkbox
    spanid = jsonObj.type + "_ena_" + jsonObj.id;

    if (jsonObj.newval == 1) {
        document.getElementById(spanid).checked = true;
    } else {
        document.getElementById(spanid).checked = false;
    }
    try {
        $.UIkit.notify("<i class='uk-icon-check'></i>&nbsp;" + jsonObj.statusMessage, {timeout: 1000,pos:'top-center'});
    }
    catch(err) {
        alert(jsonObj.statusMessage);
    }
  }
}

function SMAP_updateFreq(id, newfreq)
{
  SMAP_xmlHttp=SMAP_GetXmlHttpObject();
  if (SMAP_xmlHttp==null) {
    alert ("Browser does not support HTTP Request")
    return
  }
  var url=site_admin_url + "/plugins/sitemap/ajax.php?action=updatefreq";
  url=url+"&id="+id;
  url=url+"&newfreq="+newfreq;
  url=url+"&sid="+Math.random();
  SMAP_xmlHttp.onreadystatechange=SMAP_sc_noAction;
  SMAP_xmlHttp.open("GET",url,true);
  SMAP_xmlHttp.send(null);
}

function SMAP_updatePriority(id, newpriority)
{
  SMAP_xmlHttp=SMAP_GetXmlHttpObject();
  if (SMAP_xmlHttp==null) {
    alert ("Browser does not support HTTP Request")
    return
  }
  var url=site_admin_url + "/plugins/sitemap/ajax.php?action=updatepriority";
  url=url+"&id="+id;
  url=url+"&newpriority="+newpriority;
  url=url+"&sid="+Math.random();
  SMAP_xmlHttp.onreadystatechange=SMAP_sc_noAction;
  SMAP_xmlHttp.open("GET",url,true);
  SMAP_xmlHttp.send(null);
}

// Display a status message only, no change to form content.
function SMAP_sc_noAction()
{
  var newstate;

  if (SMAP_xmlHttp.readyState==4 || SMAP_xmlHttp.readyState=="complete") {
    jsonObj = JSON.parse(SMAP_xmlHttp.responseText)

    try {
        $.UIkit.notify("<i class='uk-icon-check'></i>&nbsp;" + jsonObj.statusMessage, {timeout: 1000,pos:'top-center'});
    }
    catch(err) {
        alert(jsonObj.statusMessage);
    }
  }
}


function SMAP_GetXmlHttpObject()
{
  var objXMLHttp=null
  if (window.XMLHttpRequest)
  {
    objXMLHttp=new XMLHttpRequest()
  }
  else if (window.ActiveXObject)
  {
    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
  return objXMLHttp
}

var smap_toggle = function() {
    var dataS = {
        "action" :  "toggleEnabled",
    };
    data = $("form").serialize() + "&" + $.param(dataS);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_admin_url + "/sitemap/ajax.php",
        data: data,
        success: function(data) {
            var result = $.parseJSON(data["json"]);

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
