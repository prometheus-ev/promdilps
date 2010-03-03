function copy1 (groupid, groupname)
{
	opener.document.forms["Main"].elements["query[group1]"].value = groupname;
	opener.document.forms["Main"].elements["query[group1id]"].value = groupid;
}

function copy2 (groupid, groupname)
{
	opener.document.forms["Main"].elements["query[group2]"].value = groupname;
	opener.document.forms["Main"].elements["query[group2id]"].value = groupid;
}

function copy3 (groupid, groupname)
{
	opener.document.forms["Main"].elements["query[group3]"].value = groupname;
	opener.document.forms["Main"].elements["query[group3id]"].value = groupid;
}