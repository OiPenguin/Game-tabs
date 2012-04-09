<?php
  /*echo "test";
  exit;*/                         
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once('./admin.php');
  
if ( ! current_user_can( 'manage_options' ) )
	wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );   

$title = __('Matches Detail');

$parent_file = 'options-general.php';
/* translators: date and time format for exact current time, mainly about timezones, see http://php.net/date */
$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
/*
Insert for submit button added by hetal
*/       

/**
 * Display JavaScript on the page.
 *
 * @package WordPress
 * @subpackage General_Settings_Panel
 */
function add_js() {
?>
<?php
}
add_filter('admin_head', 'add_js');
//include('./admin-header.php');
// include file for pagin function
include('paging-function.php');
//$dir = fn_to_get_plugin_directory();

?>

<link href="<?php echo plugins_url(); ?>/game-tabs/css/admin_paging.css" rel="stylesheet" type="text/css" />  
<?php
//fetch records from databse table;

$tableName="wp_strommen_matches";

// update url code      
   if($_REQUEST['rowid'])
   {           
        $url = $_REQUEST['match_url'];
        $sql = "UPDATE $tableName SET url = '$url' WHERE id = ".$_REQUEST['rowid'];                
        mysql_query($sql);
?>
       <script language="javascript" type="text/javascript">
       alert('"SUCCESS! URL Save successfully" ')
       </script>
       <?php
   }     

   // pagignation 
    $tableName="wp_strommen_matches";    
    //$targetpage = get_admin_url()."match_detail.php";          
    
    $Path=$_SERVER['REQUEST_URI'];    
    if($Path != "")
    {
        if(strpos($Path,"p=") > 0)
        {
            $Path=substr($Path,0,strpos($Path,"p=")-1);
        }
    }
    //print $Path;
    //exit;
    
    $targetpage = $Path;//"match_detail.php";
    
    $limit = 20;
    $custom_query = "SELECT * FROM $tableName";       
    $arr_data = paging($tableName,$targetpage,$limit,$custom_query);
    $result = $arr_data[0];    
    $paginate = $arr_data[1];
    $row_count = mysql_num_rows($arr_data[0]);
    
    /*
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
    */

/*$sql_sel = "SELECT * FROM wp_matches";
$result = mysql_query($sql_sel); */
    //$data_sel = mysql_fetch_assoc($res_sel); //print_r($data_sel);//exit;        
                            
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>
 
 <?php if ($row_count > 0) { ?>
 <label><?php _e('URL field will link to the result in "Latest matches"','MyLanguage');?></label>
<table border="1px">
<tr>
    <th><?php _e('Id','MyLanguage');?></th>  
    <th><?php _e('Match Id','MyLanguage');?></th>
    <th><?php _e('Match Number','MyLanguage');?></th>
    <th><?php _e('Date','MyLanguage');?></th>
    <th><?php _e('Round Id','MyLanguage');?></th>
    <?php /* ?><th><?php _e('Home Team Id','MyLanguage');?></th><?php */ ?>
    <th><?php _e('Home Team','MyLanguage');?></th>
     <?php /* ?><th><?php _e('Away Team Id','MyLanguage');?></th><?php */ ?>
    <th><?php _e('Away Team','MyLanguage');?></th>
    <th><?php _e('Match Start Time','MyLanguage');?></th>
    <th><?php _e('Home Goals','MyLanguage');?></th>
    <th><?php _e('Away Goals','MyLanguage');?></th>
    <th><?php _e('Incidents In','MyLanguage');?></th>
    <th><?php _e('Venue Id','MyLanguage');?></th>
    <th><?php _e('Venue Name','MyLanguage');?></th>
    <th><?php _e('URL','MyLanguage');?></th>
    <th><?php _e('save','MyLanguage');?></th>
</tr>
   
<?php 
while($data = mysql_fetch_array($result)) {  
    ?>
    <form method="post" action="" method="post">     
    <tr align="center">    
    <td><?php echo $data['id']; ?></td>
    <td><?php echo $data['match_id']; ?></td>
    <td><?php echo $data['match_number']; ?></td> 
    <td><?php echo $data['match_date']; ?></td>
    <td><?php echo $data['round_id']; ?></td>
    <?php /* ?><td><?php echo $data['home_team_id']; ?></td> <?php */ ?>
    <td><?php echo $data['hometeam']; ?></td>
    <?php /* ?><td><?php echo $data['away_team_id']; ?></td> <?php */ ?>  
    <td><?php echo $data['away_team']; ?></td>
    <td><?php echo $data['match_start_time']; ?></td>
    <td><?php echo $data['home_goals']; ?></td>
    <td><?php echo $data['away_goals']; ?></td>
    <td><?php echo $data['incidents_in']; ?></td>
    <td><?php echo $data['venue_id']; ?></td>
    <td><?php echo $data['venue_name']; ?></td>
    <td>
    <input type="text" id="match_url" name="match_url" value="<?php echo $data['url'] ; ?>" />
    <input type="hidden" id="rowid" name="rowid" value="<?php echo $data['id'] ; ?>" />
    </td>
    <?php
    if($data['url']!=NULL)
        $BtnValue = 'update';
    else
        $BtnValue = 'save';
    ?>
    <td><input type="submit" id="save" name="save"  value="<?php echo $BtnValue;?>" /></td>        

</tr>
    </form> 
<?php
    }
?>
<?php }else { ?>
<tr><td colspan="15"><?php _e('No Records','MyLanguage');?></td></tr>
<?php } ?>
</table>
<?php 
 echo $paginate;
settings_fields('general'); ?>

</div>