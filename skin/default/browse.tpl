{php}

  $lastpage = $this->_tpl_vars['result']['lastpage'];
  $curpage  = $this->_tpl_vars['result']['page']; 
  $out = '';
  $pfirstpage = false;
  $pLastpage  = false;


  for($i=$curpage-1;$i>($curpage-5) && $i>0; $i--) {
     $out = '<a class="navigationlink" href="javascript:changepage('.$i.')">'.$i.' </a>'.$out; 
     if ($i==1) $pfirstpage = true;
     if ($i==$lastpage) $pLastpage  = true;
  }



  for($i=$curpage;$i<($curpage+5) && $i<=$lastpage && $curpage>=1; $i++) {
     if ($i==$curpage) $out.='<b>';
     $out .= '<a class="navigationlink" href="javascript:changepage('.$i.')">'.$i.' </a>'; 
     if ($i==$curpage) $out.='</b>';
     if ($i==1) $pfirstpage = true;
     if ($i==$lastpage) $pLastpage  = true;
  }


  if (!$pfirstpage) 
    if (($curpage-4) > 2)
     $out = '<a class="navigationlink" href="javascript:changepage(1)">1</a> ... '.$out;
    else
     $out = '<a class="navigationlink" href="javascript:changepage(1)">1 </a>'.$out;

  if ((!$pLastpage) && ($lastpage >= 2))
    if (($curpage+4) != ($lastpage-1))
       $out .= '... <a class="navigationlink" href="javascript:changepage('.$lastpage.')">'.$lastpage.'</a>';
    else
       $out .= ' <a class="navigationlink" href="javascript:changepage('.$lastpage.')">'.$lastpage.'</a>';
 

  $out = " < ".$out." > ";

  echo $out;

{/php}
