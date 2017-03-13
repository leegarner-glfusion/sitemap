 /*  Updates submission form fields based on changes in the category
 *  dropdown.
 */
var SMAP_xmlHttp;

function SMAP_toggleEnabled(cbox, id, type, fld)
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
  url=url+"&fld="+fld;
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
    spanid = "togena_" + jsonObj.id

    if (jsonObj.newval == 1) {
        document.getElementById(spanid).checked = true;
    } else {
        document.getElementById(spanid).checked = false;
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
  SMAP_xmlHttp.open("GET",url,true);
  SMAP_xmlHttp.send(null);
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
