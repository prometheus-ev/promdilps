<?php

  // create a DOM document and load the XSL stylesheet
  $xsl = new DomDocument;
  $xsl->load('extract.xsl');
  
  // import the XSL styelsheet into the XSLT process
  $xp = new XsltProcessor();
  $xp->importStylesheet($xsl);


  // create a DOM document and load the XML datat
  $xml_doc = new DomDocument;
  $xml_doc->load('/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_dev2/_1_tgn2.xml');

  // transform the XML  using the XSL file
  if ($html = $xp->transformToXML($xml_doc)) {
      echo $html;
  } else {
      trigger_error('XSL transformation failed.', E_USER_ERROR);
  }
?>