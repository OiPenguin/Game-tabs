<?php
  function paging($tableName,$targetpage,$limit,$custom_query,$custome=NULL)
  {
    $arr = array();
	
	if($custome==1)
	$query = $custom_query;
	else
    $query = "SELECT COUNT(*) as num FROM $tableName";
	    
    $total_pages = mysql_fetch_array(mysql_query($query));
    $total_pages = $total_pages[num];
    
    $stages = 3;
    $page = mysql_escape_string($_GET['p']);
    if($page){
        $start = ($page - 1) * $limit; 
    }else{
        $start = 0;    
        }    
    
    // Get page data
    //$query1 = "SELECT * FROM $tableName LIMIT $start, $limit";
    $query1 = "$custom_query LIMIT $start, $limit";    
    $result = mysql_query($query1);      
    $arr[0] = $result;
    
    // Initial page num setup
    if ($page == 0){$page = 1;}
    $prev = $page - 1;    
    $next = $page + 1;                            
    $lastpage = ceil($total_pages/$limit);        
    $LastPagem1 = $lastpage - 1;        
    
    
    $paginate = '';
    if($lastpage > 1)
    {   
    
        $paginate .= "<div class='paginate'>";
        // Previous
        if ($page > 1){
            $paginate.= "<a href='$targetpage?p=$prev'>previous</a>";
        }else{
            $paginate.= "<span class='disabled'>previous</span>";    }
            

        
        // Pages    
        if ($lastpage < 7 + ($stages * 2))    // Not enough pages to breaking it up
        {    
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page){
                    $paginate.= "<span class='current'>$counter</span>";
                }else{
                    $paginate.= "<a href='$targetpage?p=$counter'>$counter</a>";}                    
            }
        }
        elseif($lastpage > 5 + ($stages * 2))    // Enough pages to hide a few?
        {
            // Beginning only hide later pages
            if($page < 1 + ($stages * 2))        
            {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='$targetpage?p=$counter'>$counter</a>";}                    
                }
                $paginate.= "...";
                $paginate.= "<a href='$targetpage?p=$LastPagem1'>$LastPagem1</a>";
                $paginate.= "<a href='$targetpage?p=$lastpage'>$lastpage</a>";        
            }
            // Middle hide some front and some back
            elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
            {
                $paginate.= "<a href='$targetpage?p=1'>1</a>";
                $paginate.= "<a href='$targetpage?p=2'>2</a>";
                $paginate.= "...";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='$targetpage?p=$counter'>$counter</a>";}                    
                }
                $paginate.= "...";
                $paginate.= "<a href='$targetpage?p=$LastPagem1'>$LastPagem1</a>";
                $paginate.= "<a href='$targetpage?p=$lastpage'>$lastpage</a>";        
            }
            // End only hide early pages
            else
            {
                $paginate.= "<a href='$targetpage?p=1'>1</a>";
                $paginate.= "<a href='$targetpage?p=2'>2</a>";
                $paginate.= "...";
                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page){
                        $paginate.= "<span class='current'>$counter</span>";
                    }else{
                        $paginate.= "<a href='$targetpage?p=$counter'>$counter</a>";}                    
                }
            }
        }
                    
                // Next
        if ($page < $counter - 1){ 
            $paginate.= "<a href='$targetpage?p=$next'>next</a>";
        }else{
            $paginate.= "<span class='disabled'>next</span>";
            }
            
        $paginate.= "</div>"; 
              
    }
    
    $arr[1]= $paginate;
    
    
    return $arr;
      
  }
?>
