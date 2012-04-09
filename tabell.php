
                <?php /* ?>
             <br/>
             <h1><?php _e('Table','MyLanguage'); ?></h1>  
             <?php */ ?>
             <br/>
            <?php             
            $tableName="wp_strommen_tournament_matches";        
            
            $custom_query = "SELECT wp_strommen_tournament_matches.*, wp_strommen_team_master.team_id,wp_strommen_team_master.url as team_url FROM wp_strommen_tournament_matches                JOIN wp_strommen_team_master ON wp_strommen_team_master.team_id = wp_strommen_tournament_matches.org_element_id                             
            AND wp_strommen_tournament_matches.tournament_id = ".$_REQUEST['t']."               
            ORDER BY wp_strommen_tournament_matches.points DESC,wp_strommen_tournament_matches.no_diff DESC ";           
           
            $result_match = mysql_query($custom_query);
            /*
            $targetpage = "main_page.php";     
            $limit = PAGE_ROW_GLOBAL;            
            $arr_data = paging($tableName,$targetpage,$limit,$custom_query);
            $result_match = $arr_data[0];
            $paginate =  $arr_data[1];
            */
            //$sql_match = "SELECT * FROM wp_tournament_matches ORDER BY points DESC";            
            //$result_match = mysql_query($sql_match);
            
            if(count($result_match) > 0 )
            {    ?>
                <table border="1" class="table_row">
                   <tr align="left">                    
                    <th class="" rowspan="2"><?php _e('Team Name','MyLanguage'); ?></th>                    
                    <th class="" colspan="5"><?php _e('home games','MyLanguage'); ?></th>
                    <th class="" colspan="5"><?php _e('away games','MyLanguage'); ?></th>
                    <th class="" colspan="5"><?php _e('total','MyLanguage'); ?></th>
                    <th rowspan="2"><?php _e('DIF','MyLanguage'); ?></th>
                    <th rowspan="2"><?php _e('P','MyLanguage'); ?></th>
                    <?php /* <th rowspan="2"><?php _e('More info','MyLanguage'); ?></th> */?>
                   </tr>                   
                  <tr align="center">
                   <th class="total-header"><?php _e('K','MyLanguage'); ?></th>
                   <th class="total-header"><?php _e('S','MyLanguage'); ?></th>
                   <th class="total-header"><?php _e('U','MyLanguage'); ?></th>
                   <th class="total-header"><?php _e('T','MyLanguage'); ?></th>
                   <th class="total-header"><?php _e('Mdif','MyLanguage'); ?></th>
                   <th class="home-header"><?php _e('K','MyLanguage'); ?></th>
                   <th class="home-header"><?php _e('S','MyLanguage'); ?></th>
                   <th class="home-header"><?php _e('U','MyLanguage'); ?></th>
                   <th class="home-header"><?php _e('T','MyLanguage'); ?></th>
                   <th class="home-header"><?php _e('Mdif','MyLanguage'); ?></th>
                   <th class="away-header"><?php _e('K','MyLanguage'); ?></th>
                   <th class="away-header"><?php _e('S','MyLanguage'); ?></th>
                   <th class="away-header"><?php _e('U','MyLanguage'); ?></th>
                   <th class="away-header"><?php _e('T','MyLanguage'); ?></th>
                   <th class="away-header"><?php _e('Mdif','MyLanguage'); ?></th>
                  </tr>                   
                <?php
                $i=0;       
                while($data = mysql_fetch_array($result_match)) {  ?> 
                     <tr align="center">
                         <?php /*?><td class="left"><?php echo ++$i; ?></td><?php */?>
                         <td align="left">
                         <em>
                         <?php if( isset($data['team_url']) && $data['team_url']!='' && ($data['org_element_id']==$data['team_id']) )       
                         {
                             $link = '';
                             $link = $data['team_url'];
                             if(isset($link) && $link!=''){
                             if(substr($link,0,7)!="http://" )
                             {$link="http://" . $link;}                                                         
                             }                             
                             ?>
                             <a href="<?php echo $link; ?>" target="_blank" > <?php echo $data['team_name']; ?></a>
                             <?php
                         }else{
                         ?>
                         <?php echo $data['team_name']; ?>
                         <?php } ?> 
                         </em>
                         </td>
                         
                                                  
                         <td class="home" width="4%"><?php echo $data['no_home_matches']; ?></td>
                         <td class="home" width="4%"><?php echo $data['no_home_wins']; ?></td>
                         <td class="home" width="4%"><?php echo $data['no_home_draws']; ?></td>
                         <td class="home" width="4%"><?php echo $data['no_homes_losses']; ?></td>
                         <td class="total" width="4%"><?php echo $data['no_home_goals'] ."-".$data['no_home_against'] ; ?></td>                          
                         
                         <td class="away" width="4%"><?php echo $data['no_away_matches']; ?></td>
                         <td class="away" width="4%"><?php echo $data['no_away_wins']; ?></td>
                         <td class="away" width="4%"><?php echo $data['no_away_draws']; ?></td>
                         <td class="away" width="4%"><?php echo $data['no_away_losses']; ?></td>
                         <td class="home" width="4%"><?php echo $data['no_away_goals'] ."-".$data['no_away_against'] ; ?></td>
                         
                         <td class="total" width="4%"><?php echo $data['no_matches']; ?></td>
                         <td class="total" width="4%"><?php echo $data['no_wins']; ?></td>
                         <td class="total" width="4%"><?php echo $data['no_draws']; ?></td>
                         <td class="total" width="4%"><?php echo $data['no_losses']; ?></td>
                         <td class="away" width="4%"><?php echo $data['no_goals'] ."-".$data['no_against'] ; ?></td>                         
                         
                         <td><?php echo $data['no_diff']; ?></td>
                         <td><em><?php echo $data['points']; ?></em></td>
                         <?php
                         $txt ='';
                         $link = $data['url'];
                         if(isset($link) && $link!=''){
                             if(substr($link,0,7)!="http://" )
                             {$link="http://" . $link;}                                                        
                             $txt = __("Match info",'MyLanguage');
                         }                         
                         ?>
                         <?php /* <td><em><a href="<?php echo $link;  ?>" target="_blank" ><?php echo $txt;?></a></em></td>  */ ?>
                     </tr>
                     <?php $i++;
                     } if($i==0) { 
                     ?>
                     <tr align="left">
                         <td width="10%" colspan="18" align="center"><?php _e('No Records','MyLanguage');?></td>
                     </tr>
                     <?php } ?>
                     
                </table>     
            <?php } //echo $paginate;  ?> 
