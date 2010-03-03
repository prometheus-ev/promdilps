function viewDetail( sessionid, imageid, remoteCollectionId) {
   props = 'toolbar=no,location=no,directories=no,status=yes,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no';
   win = window.open( 'view_detail.php?PHPSESSID='+sessionid+'&newwin=1&query[id]='+imageid+'&query[remoteCollection]='+remoteCollectionId+'&view[detail][id]='+imageid, 'imageView', props + ',width=700,height=720' );
   win.focus();
}


function editElement( sessionid, imageid, element, val ) {
   props = 'toolbar=no,location=no,directories=no,status=yes,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no';
   win = window.open( 'edit_element.php?PHPSESSID='+sessionid+'&query[id]='+imageid+'&query[element]='+element+'&query[value]='+val+'&query[remoteCollection]=0', 'elementView', props + ',width=900,height=500' );
   win.focus();
}


function editNameElement( sessionid, imageid, element, docelements ) {
   props = 'toolbar=no,location=no,directories=no,status=yes,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no';
   win = window.open( 'edit_element.php?PHPSESSID='+sessionid+'&query[id]='+imageid+'&query[element]='+element+'&query[name1id]='+docelements["edit[name1id]"].value+'&query[name1text]='+docelements["edit[name1text]"].value+'&query[name2id]='+docelements["edit[name2id]"].value+'&query[name2text]='+docelements["edit[name2text]"].value, 'elementView', props + ',width=700,height=720' );
   win.focus();
}


function editLocElement( sessionid, imageid, element, docelements ) {
   props = 'toolbar=no,location=no,directories=no,status=yes,scrollbars=yes,resizable=yes,menubar=no,copyhistory=no';
   win = window.open( 'edit_element.php?PHPSESSID='+sessionid+'&query[id]='+imageid+'&query[element]='+element+'&query[city]='+docelements["edit[locationid]"].value+'&query[citysearch]='+docelements["edit[city]"].value+'&query[cityname]='+docelements["edit[city]"].value+'&query[institution]='+docelements["edit[institution]"].value, 'elementView', props + ',width=900,height=500' );
   win.focus();
}


function Adjust() {
  if ((document.forms["Main"].elements["view[detail][id]"].value==""))
  if (document.forms["Main"].elements["view[edit][id]"].value=="")
      document.getElementById('navigation').colSpan = "1";
}


function changeView( view )
{
   switch( view )
   {
	case 'detail':
       document.forms["Main"].elements["query[cols]"].value=1;
       document.forms["Main"].elements["query[rows]"].value=8;
	break;
	case 'liste':
       document.forms["Main"].elements["query[cols]"].value=1;
       document.forms["Main"].elements["query[rows]"].value=8;
		break;
	case 'grid':
       document.forms["Main"].elements["query[rows]"].value=8;
       document.forms["Main"].elements["query[cols]"].value=6;
		break;
	case 'grid_detail':
       document.forms["Main"].elements["query[rows]"].value=8;
       document.forms["Main"].elements["query[cols]"].value=3;
		break;
	default:
       document.forms["Main"].elements["query[rows]"].value=8;
       document.forms["Main"].elements["query[cols]"].value=3;
		break;
	}
   document.forms["Main"].elements["view[type]"].value=view;
   document.forms["Main"].elements["query[page]"].value="1";
   document.forms["Main"].elements["view[detail][id]"].value="";
   document.forms["Main"].elements["view[edit][id]"].value="";
   document.forms["Main"].submit();
}


function changepage(page )
{
  document.forms["Main"].elements["query[page]"].value= page;
  document.forms["Main"].submit();
}


function showDetail( sessionid, id, remoteCollectionId)
{
	document.forms["Main"].elements["view[edit][id]"].value="";
	document.forms["Main"].elements["view[detail][id]"].value=id;
	document.forms["Main"].elements["query[remoteCollection]"].value=remoteCollectionId;
	document.forms["Main"].submit();
}


function editDetail( id )
{
	document.forms["Main"].elements["view[detail][id]"].value="";
	document.forms["Main"].elements["view[edit][id]"].value=id;
	document.forms["Main"].submit();
}


function changeType()
{
	document.forms["Main"].elements["edit[loadtype]"].value="changetype";
	document.forms["Main"].submit();
}

function saveImage()
{
	document.forms["Main"].elements["view[detail][id]"].value=document.forms["Main"].elements["view[edit][id]"].value;
	document.forms["Main"].elements["view[edit][id]"].value="";
	
	
	if (document.forms["Main"].elements["edit[currentuser]"].value != "")
	{
		document.forms["Main"].elements["edit[metaeditor]"].value = document.forms["Main"].elements["edit[currentuser]"].value;
	}
	document.forms["Main"].elements["edit[loadtype]"].value="save";
	
	document.forms["Main"].submit();
}

function setStatus( status )
{
	document.forms["Main"].elements["edit[status]"].value=status;
	saveImage();
}

// function ChangeQuery()
//
// Takes the value of the queryrows selection and sets query[cols]
// and query[rows] accordingly.

function ChangeQuery()
{
	var inpstr = document.forms["Main"].elements["queryrows"].value;
	var cols   = inpstr.substr(0,inpstr.indexOf('x'));
	var rows   = inpstr.substr(inpstr.indexOf('x')+1, 10);
	
	document.forms["Main"].elements["query[cols]"].value = cols;
	document.forms["Main"].elements["query[rows]"].value = rows;
}

function editDetail( id )
{
	document.forms["Main"].elements["view[detail][id]"].value="";
	document.forms["Main"].elements["view[edit][id]"].value=id;
	document.forms["Main"].submit();
}

function changeQueryType(type) {
	if (type == 'advanced') {
		document.forms["Main"].elements["query[querytype]"].value='advanced';
	} else {
		document.forms["Main"].elements["query[querytype]"].value='simple';
	}
	document.forms["Main"].submit();
}


function UpdateSelectList(selectlist, operatorlist, field, form) {

    var i;
    var operators = operator_lists[column_operators[field.value]];
    for (i=form.elements[selectlist].options.length-1;i>=0;i--) {
        form.elements[selectlist].options[i] = null;
    }

    j = 0;
    
    if (PrototypeIncluded()) {
        // this syntax is necessary when prototype.js is included (regular "for i in obj" loops don't work as expected):
        $H(operators).each( function(val){
            form.elements[selectlist].options[j] = new Option(val.value, val.key, false, false);
            j++;        
        });
    } else {
        for (i in operators) {
            form.elements[selectlist].options[j] = new Option(operators[i], i, false, false);
            j++;        
        }
    }
    
    form.elements[operatorlist].value=column_operators[field.value];

    // some things need a drop down list instead of a free text entry field, which requires a reload
    // set the value for the field to something, so that it won't get removed in the pre-processing
    //var selectboxfields = ":type:collectionid:status:";
    var selectboxfields = ":type:collectionid:";
    var valstr = selectlist.replace('operator', 'val');
    var needsreload = false;

    if (selectboxfields.indexOf(':' + field[field.selectedIndex].value +':') != -1) {
        needsreload = true;
        form.elements[valstr].value = '-1';
    } else if (form.elements[valstr].type.indexOf('select') == 0) {
        needsreload = true;
        form.elements[valstr].options[form.elements[valstr].length] = new Option('', '', false, false);
        form.elements[valstr].value = '';
    }

    if (needsreload) {
        var changing_item = valstr.replace(/.*?(\d+).*?(\d+).*/, "$1:$2");
        form.elements["query[transforming_field]"].value = changing_item;
        form.submit();
    }
}

function showCollection( sessionid, collectionid, queryid )
{
	//document.forms["Main"].elements["view[edit][id]"].value="";
	//document.forms["Main"].elements["view[detail][id]"].value=id;
	//document.forms["Main"].elements["view[type]"].value = "soap";
	//document.forms["Main"].elements["query[remote]"].value = collectionid+":"+queryid;
	document.forms["Main"].elements["query[collectionid]"].value = collectionid;
	document.forms["Main"].elements["query[showoverviewlink]"].value="1";
	document.forms["Main"].submit();
}

function updateRemoteCollectionFields(sessionid, queryid) {
    afu = new FieldUpdater('ajax-update-field', 'remoteCount.php', 'wait.gif', sessionid, queryid);
    afu.updateAll();
}

function PrototypeIncluded() {
    var included = false;
    try {
        if (Prototype) {
            included = true;
        }
    } catch (e) {
        //it's not included
    }
    return included;
}

function queryAllCollections()
{
	document.forms["Main"].elements["query[collectionid]"].value='-1';
	editDetail('');
}

function newQuery()
{
	document.forms["Main"].elements["query[showoverviewlink]"].value="0";
	editDetail('');
}

function queryCheckbox(form, hiddenvalue, cbox) {
	if (form.elements[cbox].checked == false) {
	    form.elements[hiddenvalue].value = 0;
	} else {
	    form.elements[hiddenvalue].value = 1;
	}
}

function setAndSubmit(form, element, value) {
    form.elements[element].value = value;
    form.submit();
}


// globals necessary for the advanced query
var operator_lists;
var column_operators;

function cleargroup() {
	document.forms["Main"].elements["query[group]"].value = '';
	document.forms["Main"].elements["query[groupid]"].value = '';
	document.forms["Main"].elements["query[grouplevel]"].value = '';
	document.forms["Main"].submit();
}

function clearmygroup() {
	document.forms["Main"].elements["query[mygroup]"].value = '';
	document.forms["Main"].elements["query[mygroupid]"].value = '';
	document.forms["Main"].elements["query[mygrouplevel]"].value = '';
	document.forms["Main"].submit();
}

function updatemygroup()
{
	// check, if someone has use a group button
	if (document.forms["Main"].elements["query[mygroupid]"].value != '')
	{
		// set mygroupid as target
		document.forms["Main"].elements["action[gid]"].value = document.forms["Main"].elements["query[mygroupid]"].value
		
		// update target for next action
		document.forms["Main"].elements["action[target]"].value = 'mygroup';
		document.forms["Main"].elements["action[function]"].value = 'update';

		// submit
		document.forms["Main"].submit();
	}
	
	// otherwise: do nothing
	return;		
}

function performlogout() {
	document.forms["Main"].elements["logout"].value = 1;
	document.forms["Main"].submit();
}
