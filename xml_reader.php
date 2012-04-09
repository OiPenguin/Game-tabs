<?php                              
        
    function download_page($path)
    {
            if(function_exists('curl_init'))
            {
            $ch = @curl_init();
            
            @curl_setopt($ch, CURLOPT_URL,$path);
            @curl_setopt($ch, CURLOPT_FAILONERROR,1);
            //if( ini_get('safe_mode')!=true ){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            //}
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $retValue = @curl_exec($ch);                      
            @curl_close($ch);
            
            return $retValue;      
            }
            else
            {
            return false;          
            }
    }               
    
    function chk_id($table,$field_name,$id) /// this function is chk for a duplicate entry
    {   
        $sql = " SELECT * FROM $table WHERE $field_name = $id ";       
        $data = mysql_query($sql);
        $numberofrow = mysql_num_rows($data);                         
        if($numberofrow > 0)
        {  return 1;  
        }
        else
        {  return 0;  
        }             
    }     
    
    function chk_record_update($match_id,$match_date) // this function is chk about date if that date is changes that delete that row
    {
        
        $sql = "SELECT * FROM wp_strommen_matches WHERE match_id = '$match_id' ";
        $data = mysql_query($sql);
        $numberofrow = mysql_num_rows($data);
            if($numberofrow>0)    
            { 
              $result = mysql_fetch_array($data);                
              
              if($result['last_changed']!=$match_date)
              {
                $del_sql = "DELETE FROM wp_strommen_matches WHERE match_id = $match_id ";                
                //echo $del_sql;                
                mysql_query($del_sql);
              }
            }   
    }

    function checkxmlrequest_match($tid=null)
    {
            //$sXML =download_page('http://sys21/test_s/matches.xml');   
            //$sXML =download_page(XML_MATCH);            
            $sXML =download_page('http://fotball.speaker.no/ResultDistribution/GenerateXML.aspx?t=matches&tid='.$tid);   
            $oXML = new SimpleXMLElement($sXML); 
            //print($oXML->Match->attributes());            
            for($i=0;$i<count($oXML);$i++)
            {  
                $values = "INSERT INTO wp_strommen_matches(match_date,round_id,hometeam,home_team_id,away_team,away_team_id,match_start_time,home_goals,away_goals,incidents_in,venue_id,venue_name,match_id,last_changed,match_number,match_tournament_id)VALUES" ;
                $count = count($oXML->Match->attributes());                
                $j=0;
                $match_id = $match_date = '';
                $values = $values.'(';                 
                foreach($oXML->Match[$i]->attributes() as $key=>$value)        
                {   
                    if( ($key == 'MatchDate' || $key == 'LastChanged') && $value!='' ) //print $key ."========".$value;
                      {  
                         $value = date('Y-m-d',strtotime($value));                          
                      }
                    if ($key == 'LastChanged' && $value!='' )
                    {
                        $match_date = $value;
                    }
                    if( $key == 'MatchId' && $value!='' )
                    {  
                       $match_id = $value;
                    }    
                       if($j == $count-1)
                         $values= $values ." '$value' ";                   
                       else
                         $values= $values ." '$value', ";
                       
                     $j++;                      
                }
                $sql = $values.',\''.$tid.'\')';
                // print  $sql ;
                //exit;
                //exit;                            
                //print $flag;exit;
               
               chk_record_update($match_id,$match_date); // this function delete that row if any channges is "lastChange" field
                
               if(chk_id('wp_strommen_matches','match_id ',$match_id)== 1)//print chk_id('matches','match_id ',$value);
                  $flag = 1;
               else
                  $flag = 0;
                
                if($flag==0)                
                {
                mysql_query($sql);
                $values='';
                $flag =''; 
                }
            }
       
    }     
     
    function checkxmlrequest_tournament($tournamentname=null)
    {
            $sXML =download_page($tournamentname); 
            
            if($sXML==false) 
            {
              echo _e(' <br/> cURL is not installed on your PHP installation.<br/>Get cURL working on your server.<br/>If you don\'t know how, contact your host','MyLanguage');                
              exit;
            }
            //$sXML =download_page('http://fotball.speaker.no/ResultDistribution/GenerateXML.aspx?t=tournaments&cid=733');
            $oXML = new SimpleXMLElement($sXML);
            
           // print_r($oXML);
           //print($oXML->Match->attributes());            
           
            for($i=0;$i<count($oXML);$i++) 
            {  
                foreach($oXML->Job[$i]->attributes() as $key=>$value)        
                {  
                   //print $key ." => ".$value."</br>";                     
                   $name = " '$value' ";
                    for($j=0;$j<count($oXML->Job[$i]->Tournament);$j++)
                     {
                        $k=0;
                        
                         $count = count($oXML->Job[$i]->Tournament[$j]->attributes());
                         
                              foreach($oXML->Job[$i]->Tournament[$j]->attributes() as $key=>$value)
                              {
                                   if( $key == 'TournamentId' && $value!='' )
                                   {                                                                                           
                                  
                                    if(chk_id('wp_strommen_tournaments','tournament_id  ',$value)== 1)//print chk_id('matches','match_id ',$value);
                                    {    $flag = 1; }
                                    else
                                    {    $flag = 0; }
                                   }
                                  
                                 //print "$j ".$key ."---->".$value."</br>";
                                 if($k == $count-1)
                                 $data = $data." '$value' ";
                                 else
                                 $data = $data." '$value' ,";
                                // print "- $k -";
                                 $k++;
                                 
                              }           
                    // print  
                    $val =  $name .",".  $data; 
                    $val = 'VALUES('.$val.')';
                    //print  $val;
                    
                    $sql =  "INSERT INTO wp_strommen_tournaments(job_name,tournament_name,tournament_id,tournament_type,nation,gender,admin_id)";                    
                    $sql =  $sql.$val;
                    
                    if($flag==0)                
                    {
                        mysql_query($sql);
                        $values='';
                        $flag =''; 
                    }
                    else
                    {
                        // print  $sql;
                    }
                    //  print  $sql; //print "</br>";  
                    $data = '';   
                     }                      
                }  
            } 
    }                      
    
    function checkxmlrequest_tournament_tables($tid=null)
    {
            //$sXML =download_page('http://sys21/test_s/tournamentTables.xml');
            $sXML =download_page('http://fotball.speaker.no/ResultDistribution/GenerateXML.aspx?t=table&tid='.$tid);            
            $oXML = new SimpleXMLElement($sXML);
            
         /* print_r($oXML);            
          exit; */
                
            for($i=0;$i<count($oXML);$i++) 
            {
                $list1 = '';  
                $list2 = ''; 
                              
                foreach($oXML->Tournament[$i]->attributes() as $key1=>$value1)        
                {                                                                                                 
                   
                    if( $key1 == 'LastChanged'  && $value1!='' )
                     { 
                            $value1 = date('Y-m-d',strtotime($value1));                            
                            $chk_date = $value1;
                             
                     }                                    
                       $list1 = $list1 . " '$value1',"; 
                      
                }
                
                for($j=0;$j<count($oXML->Tournament[$i]->TournamentTable);$j++)
                {  
                         $k=0;
                         $data2='';    
                         $count = count($oXML->Tournament[$i]->TournamentTable[$j]->attributes());                         
                              foreach($oXML->Tournament[$i]->TournamentTable[$j]->attributes() as $key=>$value)
                              {                                   
                                  if( $key == 'TeamName'  && $value!='' )
                                  {
                                         $chk_team = $value;
                                  }
                                  if($k == $count-1)
                                        $data2 = $data2." '$value') ";
                                   else
                                        $data2 = $data2." '$value' ,";                                         
                                    $k++;  
                              }

                         $sqltables =  "INSERT INTO wp_strommen_tournament_matches(tournament_id,tournament_name,last_change,no_home_matches,no_home_wins,no_home_draws,no_homes_losses,no_home_goals,no_home_against,no_away_matches,no_away_wins,no_away_draws,
    no_away_losses,no_away_goals,no_away_against,no_matches,no_wins,no_draws,no_losses,no_goals,no_against,no_diff,points,team_name,org_element_id,local_council,parent_id)VALUES(";
                         
                         
                         $sql = "SELECT * FROM wp_strommen_tournament_matches WHERE team_name LIKE '$chk_team' ";
                         
                         $data = mysql_query($sql);
                         $numberofrow = mysql_num_rows($data);
                         if($numberofrow>0)    
                         { 
                            $result = mysql_fetch_array($data);//print_r($result['last_change']);
                            if($result['last_change']!=$chk_date)
                              {  
                                $del_sql = "DELETE FROM wp_strommen_tournament_matches WHERE team_name LIKE '$chk_team' ";
                                 //print $del_sql ;
                                 mysql_query($del_sql);
                                 //exit;               
                                 $sql = $sqltables.$list1.$data2;
                                 mysql_query($sql);      
                              }
           
                         }else
                            {    
                                $sql = $sqltables.$list1.$data2;
                                 mysql_query($sql);
                            }
                     }
              }
    }
    
    //////////////////// function create master tabele /////////////////////    
    function team_master()
    {
	$sql = "TRUNCATE TABLE wp_strommen_team_master";
	$result = mysql_query($sql);	
	
    // $sql = "SELECT home_team_id as Team_id, hometeam AS Name FROM wp_strommen_matches UNION SELECT away_team_id, `away_team` AS Name FROM wp_strommen_matches";
    $sql = "SELECT tournament_id,tournament_name,org_element_id as Team_id, team_name AS Name FROM wp_strommen_tournament_matches";
    $result = mysql_query($sql);
    
    $numberofrow = mysql_num_rows($result);
    
    if($numberofrow > 0)
        {  
            while($data = mysql_fetch_array($result))
            {
               $id = $data['Team_id'];
               $name = $data['Name'];
               $tournamentId = $data['tournament_id'];
               $tournamentName = $data['tournament_name'];
               $insert_sql = "INSERT INTO wp_strommen_team_master(team_name,team_id,tournament_id,tournament_name)VALUES('$name','$id','$tournamentId','$tournamentName')";
               mysql_query($insert_sql);
               
            } 
        }
    }
    /////////////////////////////////////////////////////////////////////////////                                
    // checkxmlrequest_match();
    // checkxmlrequest_tournament();
    // checkxmlrequest_tournament_tables();
    // team_master();
    ?>