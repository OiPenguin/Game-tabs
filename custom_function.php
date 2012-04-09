<?php                                                                      
function get_tournaments()
{
$tournamentsdata = array();    
$tournaments = array();
$tournamentsid = array();
$sql = "SELECT * FROM wp_strommen_tournaments";
$tournaments_data = mysql_query($sql);
$i = 0;

while($tournaments_result = mysql_fetch_array($tournaments_data)) {
	
    $tournaments[$i] = $tournaments_result['tournament_name'];    
    $tournamentsid[$i] = $tournaments_result['tournament_id'];    
	$i++;
}
$tournamentsdata[0] = $tournaments ;
$tournamentsdata[1] = $tournamentsid ;

return $tournamentsdata;
}

function get_team()
{
$team_arr = array();
$team = array();
$team_id = array();
$sql = "SELECT * FROM wp_strommen_team_master";
$team_data = mysql_query($sql);
$i = 0;

while($team_result = mysql_fetch_array($team_data)) {
    
    $team[$i] = $team_result['team_name'];    
    $team_id[$i] = $team_result['team_id'];    
    $i++;
}
$team_arr[0] = $team ;
$team_arr[1] = $team_id ;

return $team_arr;
}

function get_team_with_tournament()
{
$team_arr = array();
$team = array();
$team_id = array();
$team_tournament_name = array();
$sql = "SELECT * FROM wp_strommen_tournament_matches";
$team_data = mysql_query($sql);
$i = 0;

while($team_result = mysql_fetch_array($team_data)) {
    
    $team[$i] = $team_result['team_name'];        
    $team_id[$i] = $team_result['org_element_id'];    
    $team_tournament_name[$i] = $team_result['tournament_name'];    
    $i++;
}
$team_arr[0] = $team ;
$team_arr[1] = $team_id ;
$team_arr[2] = $team_tournament_name ;


return $team_arr;
    
}

function reset_db()
{                     
    $sql = "TRUNCATE TABLE wp_strommen_team_master";
    mysql_query($sql);
    $sql = "TRUNCATE TABLE wp_strommen_tournaments";
    mysql_query($sql);
    $sql = "TRUNCATE TABLE wp_strommen_tournament_matches";
    mysql_query($sql);
    $sql = "TRUNCATE TABLE wp_strommen_matches";
    mysql_query($sql);
    $sql = "UPDATE wp_strommen_config SET value = '' WHERE tag IN ( 'TOURNAMENT' , 'TEAM' , 'MAIN_FEED_XML' ) " ;    
    mysql_query($sql);        
    
}
                                   

?>