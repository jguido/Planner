<?php

/*
  Plugin Name: Planner Manager
  Plugin URI:
  Description: Plugin for managing post planning
  Author: Unrtech
  Version: 1.0.0
  Author URI: http://unrtech.fr

  Planner manager is released under GPL:
  http://www.opensource.org/licenses/gpl-license.php
 */

// Hook for adding admin menus
if (!defined('WP_CONTENT_URL')) {
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}

if (!defined('WP_CONTENT_PATH')) {
    define('WP_CONTENT_PATH', get_option('siteurl') . '/wp-content');
}

if (!defined('WP_PLUGIN_LANG_DEFAULT')) {
    define('WP_PLUGIN_LANG_DEFAULT', 'fr');
}
$alert = array(
    'success' => '',
    'info' => '',
    'error' => ''
);
$response = '';
add_action('wp_admin_head', 'init_admin_head');

function init_admin_head() {
    wp_enqueue_script('tiny_mce');
}

add_action('admin_init', 'planner_manager_init');

function planner_manager_init() {
    if (!get_option('planner_manager_planning')) {
        add_option('planner_manager_planning', array(
            0 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            1 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            2 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            3 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            4 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            5 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            ),
            6 => array(
                '8h'=>array('comment'=>''), 
                '9h'=>array('comment'=>''), 
                '10h'=>array('comment'=>''), 
                '11h'=>array('comment'=>''), 
                '12h'=>array('comment'=>''), 
                '13h'=>array('comment'=>''), 
                '14h'=>array('comment'=>''), 
                '15h'=>array('comment'=>''), 
                '16h'=>array('comment'=>''), 
                '17h'=>array('comment'=>''), 
                '18h'=>array('comment'=>'')
            )
        ));
    }
    if (!get_option('dow_en')) {
        add_option('dow_en', array(
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday',
        ));
    }
    if (!get_option('dow_fr')) {
        add_option('dow_fr', array(
            0 => 'Lundi',
            1 => 'Mardi',
            2 => 'Mercredi',
            3 => 'Jeudi',
            4 => 'Vendredi',
            5 => 'Samedi',
            6 => 'Dimanche',
        ));
    }
    wp_enqueue_style('planner_md_style', WP_CONTENT_URL . '/plugins/Planner/css/PlannerStyle.css');
}

add_action('admin_menu', 'planner_manager_plugin_admin_menu');

function planner_manager_plugin_admin_menu() {
    add_menu_page(null, 'Planning', 'publish_posts', 'PlannerManagerMenu', 'planner_manager_table', WP_CONTENT_URL . '/plugins/Planner/images/icon.png');
}

function planner_manager_table() {
    $intlDoW = get_option('dow_'.WP_PLUGIN_LANG_DEFAULT);
    $planning = get_option('planner_manager_planning');
    $tplColPlanner = file_get_contents(WP_CONTENT_URL . '/plugins/Planner/templates/colPlannerPost.html');
    $tplTablePlanner = file_get_contents(WP_CONTENT_URL . '/plugins/Planner/templates/tablePlanning.html');
    for ($i = 0 ; $i <= 6 ; $i++) {
        $task = $planning[$i];
        
        $colResponse = str_replace('#TITLE8H#', stripslashes($task['8h']['comment']),   $tplColPlanner);
        $colResponse = str_replace('#TITLE9H#', stripslashes($task['9h']['comment']),   $colResponse);
        $colResponse = str_replace('#TITLE10H#',stripslashes($task['10h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE11H#',stripslashes($task['11h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE12H#',stripslashes($task['12h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE13H#',stripslashes($task['13h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE14H#',stripslashes($task['14h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE15H#',stripslashes($task['15h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE16H#',stripslashes($task['16h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE17H#',stripslashes($task['17h']['comment']),  $colResponse);
        $colResponse = str_replace('#TITLE18H#',stripslashes($task['18h']['comment']),  $colResponse);
        $colResponse = str_replace('#DATADAY#' , $intlDoW[$i],             $colResponse);
        $response .= $colResponse;
    }
    $response = str_replace('#TAB_PLANNER_BODY#', $response, $tplTablePlanner);
    $response = str_replace('#MONDAY#',     $intlDoW[0], $response);
    $response = str_replace('#TUESDAY#',    $intlDoW[1], $response);
    $response = str_replace('#WEDNESDAY#',  $intlDoW[2], $response);
    $response = str_replace('#THURSDAY#',   $intlDoW[3], $response);
    $response = str_replace('#FRIDAY#',     $intlDoW[4], $response);
    $response = str_replace('#SATURDAY#',   $intlDoW[5], $response);
    $response = str_replace('#SUNDAY#',     $intlDoW[6], $response);
    $response = str_replace('#TOKEN#', md5('UNRTECH_PLANNER'), $response);
    
    $response = str_replace('#ALERTSUCCESS#',   $alert['success'],  $response);
    $response = str_replace('#ALERTINFO#',      $alert['info'],     $response);
    $response = str_replace('#ALERTERROR#',     $alert['error'],    $response);
    
    echo $response;
}

//manage POST here
if (isset($_POST['secureToken']) && !empty($_POST['secureToken'])) {
    $comment = isset($_POST['txtComment']) ? $_POST['txtComment'] : null;
    $day = isset($_POST['txtDayDay']) ? $_POST['txtDayDay'] : null ;
    if (!$day) {
        $alert = addAlert('error', 'Le jour de la semaine n\'a pu être trouvé.', $alert);
    }
    $hour = isset($_POST['txtDayHour']) ? $_POST['txtDayHour'] : null ;
    if (!$hour) {
        $alert = addAlert('error', 'L\'heure n\'a pu être trouvée.', $alert);
    }
    $intlDoW = get_option('dow_'.WP_PLUGIN_LANG_DEFAULT);
    $rank = null;
    foreach ($intlDoW as $k => $intlD) {
        if ($intlD == $day) {
            $rank = $k;
        }
    }
    $planning = get_option('planner_manager_planning');
    $planning[$rank][$hour]['comment'] = $comment;
    
    update_option('planner_manager_planning', $planning);
} else {
    $alert = addAlert("error", "Can't find secure token", $alert);
}

function addAlert($level, $message, $container) {
    $container[$level] .= $message."<br/>";
    
    return $container;
}
?>
