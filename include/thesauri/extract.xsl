<?xml version='1.0' encoding='ISO-8859-1' ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:g="http://localhost/namespace" version="1.0" >

<xsl:output encoding="ISO-8859-1" />

<xsl:template match="//g:Subject">
    <xsl:value-of select="g:Terms/g:Preferred_Term/g:Term_Text"/>
    <xsl:value-of select="g:Place_Types/g:Preferred_Place_Type/g:Place_Type_ID"/>:::<xsl:value-of select="g:Hierarchy"/>:::<xsl:value-of select="@Subject_ID"/>:::<xsl:value-of select="g:Parent_Relationships/g:Preferred_Parent/g:Parent_Subject_ID"/>
</xsl:template>

</xsl:stylesheet>