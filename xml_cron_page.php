<?php
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-includes/wp-db.php'); 
?>                                   

<?php 
        
    function download_page($path)
    {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$path);
            curl_setopt($ch, CURLOPT_FAILONERROR,1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $retValue = curl_exec($ch);                      
            curl_close($ch);
            return $retValue;      
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

    function checkxmlrequest_match($url=null)
    {
            $sXML =download_page($url);   
            $oXML = new SimpleXMLElement($sXML);             
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
                //$sql = $values.')';
                $sql = $values.',\''.$tid.'\')';             
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
    
    function checkxmlrequest_tournament_tables($url=null)
    {
            
            $sXML =download_page($url);            
            $oXML = new SimpleXMLElement($sXML);
                
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
    // getting a url from config
    
    $sql = "SELECT * FROM wp_strommen_config";
    $config_data = mysql_query($sql);
    while($config_result = mysql_fetch_array($config_data)) {
         $$config_result['tag']=$config_result['value'];
    }   
        
    $select_query = "SELECT * FROM wp_strommen_tournaments";
    $result = mysql_query($select_query);    
    $numberofrow = mysql_num_rows($result);    
    if($numberofrow > 0)
    {  
            while($data = mysql_fetch_array($result))
            {
               $id = $data['tournament_id'];               
               checkxmlrequest_match($MATCH_XML . $id);
               checkxmlrequest_tournament_tables($TABLE_XML.$id);
               
            } 
        }    