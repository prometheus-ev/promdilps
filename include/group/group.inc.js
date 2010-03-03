function copy (groupid, groupname)
{
	opener.document.forms["Main"].elements["query[group]"].value = groupname;
	opener.document.forms["Main"].elements["query[groupid]"].value  = groupid;
	opener.document.forms["Main"].submit();
}