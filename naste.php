
        <?php /* ?>
        <br/>
        <h1><?php _e('Last five','MyLanguage'); ?></h1>
        <?php */ ?>
        <br/>
            
            <?php
        if(TEAM_GLOBAL!='') {            
            $tableName="wp_strommen_matches";                                                
            
            $custom_query = "SELECT wp_strommen_matches.*,t1.url as team_url1,t2.url as team_url2 FROM wp_strommen_matches 
            RIGHT JOIN wp_strommen_team_master t1 
            ON t1.team_id = wp_strommen_matches.home_team_id 
            RIGHT JOIN wp_strommen_team_master t2 
            ON t2.team_id = wp_strommen_matches.away_team_id 
            WHERE CURDATE()<= wp_strommen_matches.match_date AND
            wp_strommen_matches.match_tournament_id = ".$_REQUEST['t']." AND  
            (wp_strommen_matches.home_team_id  IN (".TEAM_GLOBAL.") OR wp_strommen_matches.away_team_id  IN (".TEAM_GLOBAL.") ) ORDER BY wp_strommen_matches.match_date ASC ";            
            $result_match = mysql_query($custom_query);                                 
            if(count($result_match) > 0 )
            {    ?>
                <table border="1" class="table_row" >
                   <tr align="center">
                    <th><?php _e('Date','MyLanguage'); ?></th>
                    <th><?php _e('Time','MyLanguage'); ?></th>
                    <th><?php _e('Home Team','MyLanguage'); ?></th>
                    <th><?php _e('Away Team','MyLanguage'); ?></th>
                    <th><?php _e('Venue','MyLanguage'); ?></th>                    
                    <th><?php _e('Result','MyLanguage'); ?></th>
                    <th><?php _e('URL','MyLanguage'); ?></th>
                   </tr>                   
                                   
                <?php
                
                $i=0;       
                while($data = mysql_fetch_array($result_match)) {  ?> 
                     <tr align="left">
                         <td width="10%"><?php echo date('d.m.y',strtotime($data['match_date'])); ?></td>
                         <td><?php echo $data['match_start_time']; ?></td>                                                  
                         <td width="20%"><?php echo $data['hometeam']; ?></td>
                          <?php 
                         
                         if( isset($data['team_url2']) && $data['team_url2']!=''  ) //&& ($data['away_team_id'] == $data['team_id'])
                         {
                         $link = '';
                         $link = $data['team_url2'];
                         if(isset($link) && $link!=''){
                             if(substr($link,0,7)!="http://" )
                             {$link="http://" . $link;}                                                         
                             }
                         ?>
                         
                         <td width="20%"><a href="<?php echo $link; ?>" target="_blank" > <?php echo $data['away_team']; ?> </a> </td>                             
                         <?php }else{?>
                          <td width="20%"><?php echo $data['away_team']; ?></td>
                         <?php } ?>
                         
                         
                         <?php if(GOOGLE_MAP_GLOBAL==1) { ?>
                         <td><a href="http://maps.google.com/maps?q=<?php echo $data['venue_name']; ?>" target="_blank"><?php echo $data['venue_name']; ?></a></td>
                         <?php } else {?>
                         <td><?php echo $data['venue_name']; ?></td>
                         <?php } ?>
                         <td width="10%" align="center">
                         <?php // echo $data['home_goals'] ."-". $data['away_goals'] ; ?>
                         <?php echo "-" ; ?>
                         </td>
                         <?php
                         $txt ='' ;
                         $link = $data['url'];
                         if(isset($link) && $link!=''){
                             if(substr($link,0,7)!="http://" )
                             {$link="http://" . $link;}                            
                             $txt = __("Match info",'MyLanguage');
                         }
                         ?>
                         <td><em><a href="<?php echo $link; ?>" target="_blank"  ><?php echo $txt;?></a></em></td>
                    </tr>
                     <?php $i++; }					 
					 if($i==0)
					 {
					 ?>
                     <tr align="left">
                         <td width="10%" colspan="7" align="center"><?php _e('No Records','MyLanguage');?></td>
                     </tr>
                     <?php
					 }
					 
					  ?>
                </table>     
            <?php } 
            //echo $paginate;  
        }?> 