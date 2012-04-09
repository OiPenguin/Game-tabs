<?php
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-includes/wp-db.php');
include_once('front-paging-function.php');
?>
<script type="text/javascript" src="<?php echo plugins_url(); ?>/game-tabs/js/tabber.js"></script>
<link rel="stylesheet" href="<?php echo plugins_url(); ?>/game-tabs/css/example.css" TYPE="text/css" MEDIA="screen">
<link href="<?php echo plugins_url(); ?>/game-tabs/css/admin_paging.css" rel="stylesheet" type="text/css" />
<?php get_header(); ?>
<script type="text/javascript">
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>                                                                                                                            
<script type="text/javascript">                                                                                                      
/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */                                                                                            
document.write('<style type="text/css">.tabber{display:none;}<\/style>');                                                            
var tabberOptions = {                                                                            
  'onClick': function(argsObj) {
    var t = argsObj.tabber; /* Tabber object */	
    var i = argsObj.index; /* Which tab was clicked (0..n) */
    var div = this.tabs[i].div; /* The tab content div */
    /* Display a loading message */
    div.innerHTML = "<p>Loading...<\/p>";
    /* Fetch some html depending on which tab was clicked */
    //var url = 'example-ajax-' + i + '.html';
    //var pars = 'foo=bar&foo2=bar2'; /* just for example */
    var myAjax = new Ajax.Updater(div, url, {method:'get',parameters:pars});
  },
  'onLoad': function(argsObj) {
    /* Load the first tab */
	// alert("1");
    argsObj.index = 0;
    this.onClick(argsObj);
  },                                     
}
</script> 
<div id="container">
<h1><?php echo $_REQUEST['n']; ?></h1>
    <div  role="main">    
<div class="tabber" >
     <?php 
     
     if(SHOW_SISTE_GLOBAL == 1) {     
     if(PAGE_WIDTH_GLOBAL!='')
     $width = PAGE_WIDTH_GLOBAL.'px' ;
     else
     $width = '100%';     
     
     // echo $_REQUEST['n'];
     ?>
     <?php // if(TEAM_GLOBAL!='')  ?>            
     <div class="tabbertab" style="width: <?php echo $width; ?>;">
     <h2><?php _e('Latest matches','MyLanguage'); ?></h2>                
    <?php include('games.php'); ?>
    </div>
     <?php }  ?>
     
     
     <?php if(SHOW_NESTE_GLOBAL == 1  ) {?> 
     <div class="tabbertab" style="width: <?php echo $width; ?>;">
     <h2><?php _e('Next matches','MyLanguage'); ?></h2>                
    <?php include('naste.php'); ?>
    </div>
     <?php } ?> 
     
     <?php if(SHOW_TABELL_GLOBAL == 1 ) {?>
     <div class="tabbertab" style="width: <?php echo $width; ?>;">
     <h2><?php _e('Table','MyLanguage'); ?></h2>     
	 <?php include('tabell.php'); ?>
     </div>
     <?php } ?>                             
</div>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>