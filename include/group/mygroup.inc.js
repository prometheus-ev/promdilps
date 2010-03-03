function copy (groupid, groupname)
{
	opener.document.forms["Main"].elements["query[mygroup]"].value  = groupname;
	opener.document.forms["Main"].elements["query[mygroupid]"].value  = groupid;
	opener.document.forms["Main"].submit();
}