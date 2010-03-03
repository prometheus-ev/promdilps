/** returns a string representation of the structure of the object
 *
 * The default nl and indent parameters are suitable for outputting html.
 * showInfo() is handy for outputting the result of this function.
 *
 * @param object/array obj to be converted to a string
 * @param string name the display name for obj
 * @param int maxDepth - if dom elements are involved, set this low enough to avoid infinite recursion
 * @param string nl = "<br/>"
 * @param string indent = "&nbsp;&nbsp;&nbsp;&nbsp;"
 * @param int Curdepth: for internal use only
 * @author brian@mediagonal.ch
**/

function toString(obj, name, maxDepth, nl, indent, curDepth)
{

   // set default values
   if (maxDepth == undefined) {
       maxDepth=2;
   }
   if (curDepth == undefined) {
       curDepth = 0;
   }
   if (curDepth > maxDepth) {
       return '';
   }
   if (name == undefined) {
       name = '<obj>';
   }
   if (nl == undefined) {
       nl = '<br/>';
   }
   if (indent == undefined) {
       indent = '&nbsp;&nbsp;&nbsp;&nbsp;';
   }
   var i;
   var str;
   var children;
   var ind = '';
   for (i = 0; i <= curDepth; i++) {
      ind += indent;
   }

   // recursively build the string
   str = curDepth == 0 ? nl+name+nl : '';
   i = 0;
   for (prop in obj)
   {
       i++;
       try {
             if (typeof(obj[prop]) == "object")
             {
                 if (obj[prop] && obj[prop].length != undefined) {
                     str += ind + name+"."+prop + "=[probably array, length " + obj[prop].length + "]"+nl;
                 } else {
                     str += ind + name+"."+prop + "=[" + typeof(obj[prop]) + "]"+nl;
                 }
                 children = toString(obj[prop], name+"."+prop, maxDepth, nl, indent, curDepth+1);
                 if (children != '') {
                     str += children;
                 }
             } else if (typeof(obj[prop]) == "function") {
                 str += ind + name+"."+prop + "=[function]"+nl;
             } else {
                 str += ind + name+"."+prop + "=" + obj[prop] +nl;
             }
       } catch (e) {
             str += ind + "!!! " + name+"."+prop + ":[exception caught: "+e+"]"+nl;
       }
   }
   if (!i) {
       str += ind + name + " is empty"+nl;
   }
   return str;
}


/** outputs a string to the html document & highlights the output area
 *
 * If no targetElementId is given, the output will be written to the beginning 
 *  of the html document.
 * If a targetElementId is given, the output will written to that element.
 *
 * @param string info the text to display
 * @param string targetElementId = null
 * @author brian@mediagonal.ch
**/

function showInfo(info, targetElementId) {
    var el;
    if (targetElementId == null) {
        el = document.createElement('div');
        el.innerHTML = info;
        document.body.insertBefore(el, document.body.firstChild);
    } else {
        el = document.getElementById(targetElementId);
        el.innerHTML = info;
    }
    el.style.border = "2px dotted red";
    el.style.padding = "4px";
}
