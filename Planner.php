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

add_action('admin_init', 'planner_manager_init');

function planner_manager_init() {
    if (!get_option('planner_manager_planning')) {
        add_option('planner_manager_planning', array(
            0 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            1 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            2 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            3 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            4 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            5 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array()),
            6 => array('8h'=>array(), '9h'=>array(), '10h'=>array(), '11h'=>array(), '12h'=>array(), '13h'=>array(), '14h'=>array(), '15h'=>array(), '16h'=>array(), '17h'=>array(), '18h'=>array())
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
    wp_enqueue_script('md_style', WP_CONTENT_URL . '/plugins/Planner/js/jquery-ui-1.10.2.custom.min.js');
    wp_enqueue_style('md_style', WP_CONTENT_URL . '/plugins/Planner/css/PlannerStyle.css');
    wp_enqueue_style('md_ui_style', WP_CONTENT_URL . '/plugins/Planner/css/ui-darkness/jquery-ui-1.10.2.custom.css');
}

add_action('admin_menu', 'planner_manager_plugin_admin_menu');

function planner_manager_plugin_admin_menu() {
    add_menu_page(null, 'Planning', 'publish_posts', 'PalnnerManagerMenu', 'planner_manager_table', WP_CONTENT_URL . '/plugins/plannerManager/images/icon.png');
}

function planner_manager_table() {
    $response = '';
    $planning = get_option('planner_manager_planning');
    $tplColPlanner = file_get_contents(WP_CONTENT_URL . '/plugins/manageDons/templates/colPlannerPost.html');
    $tplTablePlanner = file_get_contents(WP_CONTENT_URL . '/plugins/manageDons/templates/tablePlanning.html');
    foreach ($assos as $asso) {
        
        
        $lineResponse = str_replace('#ASSO#', stripslashes($asso['association']), $tplDonLine);
        $lineResponse = str_replace('#CROQUETTES_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['croquettes_statut'] . '"/>' . $croquetteComment, $lineResponse);
        $lineResponse = str_replace('#PATEES_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['patees_statut'] . '"/>' . $pateesComment, $lineResponse);
        $lineResponse = str_replace('#LITIERE_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['litiere_statut'] . '"/>' . $litiereComment, $lineResponse);
        $lineResponse = str_replace('#SOINS_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['soins_statut'] . '"/>' . $soinsComment, $lineResponse);
        $lineResponse = str_replace('#ENTRETIEN_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['entretien_statut'] . '"/>' . $entretienComment, $lineResponse);
        $lineResponse = str_replace('#NOURISSEUSE_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['nourisseuse_statut'] . '"/>' . $nourisseuseComment, $lineResponse);
        $lineResponse = str_replace('#CDPEP_STATUS#', '<input type="hidden" class="hidStatut" value="' . $asso['cdpep_statut'] . '"/>' . $cdpepComment, $lineResponse);
        $lineResponse = str_replace('#SLUG#', $asso['slug'], $lineResponse);
        $lineResponse = str_replace('#ATTESTATION#', $attestation, $lineResponse);
        $lineResponse = str_replace('#SLUG_ASSO#', $asso['slug'], $lineResponse);
        $response .= $lineResponse;
    }
    $postScrollTop = (isset($_POST['scrollTo']) && $_POST['scrollTo'] != null) ? $_POST['scrollTo'] : 0;
    $tplTableDonList = str_replace('#TAB_DONS_CSS_TOP#', $postScrollTop, $tplTableDonList);
    $response = str_replace('#TAB_DONS_BODY#', $response, $tplTableDonList);

    echo $response;
}

function manageDons_add_asso() {
    $tplAdd = file_get_contents(WP_CONTENT_URL . '/plugins/manageDons/templates/addDon.html');
    echo '<pre>';
    echo $tplAdd;
    echo '</pre>';
}

function slugify($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

//manage POST here
if (isset($_POST['txtAssoName']) && !empty($_POST['txtAssoName'])) {
    $assoName = $_POST['txtAssoName'];
    $slug = slugify($assoName);
    $croquetteStatut = -1;
    $croquetteComment = '';
    $pateeStatut = -1;
    $pateesComment = '';
    $litiereStatut = -1;
    $litiereComment = '';
    $soinsStatut = -1;
    $soinsComment = '';
    $entretienStatut = -1;
    $entretienComment = '';
    $nourisseuseStatut = -1;
    $nourisseuseComment = '';
    $cdpepStatut = -1;
    $cdpepComment = '';
    $attestation = 0;
    $postData = array(
        'association' => $assoName,
        'slug' => $slug,
        'attestation' => $attestation,
        'croquettes_statut' => $croquetteStatut,
        'croquettes_commment' => $croquetteComment,
        'patees_statut' => $pateeStatut,
        'patees_comment' => $pateesComment,
        'litiere_statut' => $litiereStatut,
        'litiere_comment' => $litiereComment,
        'soins_statut' => $soinsStatut,
        'soins_comment' => $soinsComment,
        'entretien_statut' => $entretienStatut,
        'entretien_comment' => $entretienComment,
        'nourisseuse_statut' => $nourisseuseStatut,
        'nourisseuse_comment' => $nourisseuseComment,
        'cdpep_statut' => $cdpepStatut,
        'cdpep_comment' => $cdpepComment
    );
    $assosOption = get_option('manage_dons_asso_list');
    if (empty($assosOption)) {
        $assosOption = array();
    }
    array_push($assosOption, $postData);
    update_option('manage_dons_asso_list', $assosOption);
}
if (
        isset($_POST['selStatutColis']) && $_POST['selStatutColis'] != null &&
        isset($_POST['slugAsso']) && $_POST['slugAsso'] != null
) {
    $selStatut = $_POST['selStatutColis'];
    list($slugAsso, $categorie) = explode('___', $_POST['slugAsso']);
    $comment = (isset($_POST['txtComment']) && $_POST['txtComment'] != null) ? $_POST['txtComment'] : '';
    $assos = get_option('manage_dons_asso_list');
    $tmpAssos = array();
    foreach ($assos as $asso) {
        if ($asso['slug'] == $slugAsso) {
            $asso[$categorie . '_statut'] = $selStatut;
            $asso[$categorie . '_comment'] = $comment;
        }
        array_push($tmpAssos, $asso);
    }
    update_option('manage_dons_asso_list', $tmpAssos);
    get_option('manage_dons_asso_list');
}
if (
        isset($_POST['txtAssoNom']) && $_POST['txtAssoNom'] != null &&
        isset($_POST['slugFrmAsso']) && $_POST['slugFrmAsso'] != null
) {
    $assoNom = $_POST['txtAssoNom'];
    $slugAsso = $_POST['slugFrmAsso'];
    $assoMail = (isset($_POST['txtAssoMail']) && $_POST['txtAssoMail'] != null) ? $_POST['txtAssoMail'] : '';
    $assoTel = (isset($_POST['txtAssoTel']) && $_POST['txtAssoTel'] != null) ? $_POST['txtAssoTel'] : '';
    $assoContact = (isset($_POST['txtAssoContact']) && $_POST['txtAssoContact'] != null) ? $_POST['txtAssoContact'] : '';
    $assoSite = (isset($_POST['txtAssoSite']) && $_POST['txtAssoSite'] != null) ? $_POST['txtAssoSite'] : '';
    $assoAddress = (isset($_POST['txtAssoAddress']) && $_POST['txtAssoAddress'] != null) ? $_POST['txtAssoAddress'] : '';
    $assoAttestation = (isset($_POST['attestationFrmAsso']) && $_POST['attestationFrmAsso'] != null) ? $_POST['attestationFrmAsso'] : 0;
    $assos = get_option('manage_dons_asso_list');
    $assoTmpUpdate = array();
    foreach ($assos as $asso) {
        if ($asso['slug'] == $slugAsso) {
            $asso['association'] = $assoNom;
            $asso['address'] = $assoAddress;
            $asso['mail'] = $assoMail;
            $asso['tel'] = $assoTel;
            $asso['contact'] = $assoContact;
            $asso['site'] = $assoSite;
            $asso['attestation'] = $assoAttestation;
        }
        array_push($assoTmpUpdate, $asso);
    }
    update_option('manage_dons_asso_list', $assoTmpUpdate);
}
if (isset($_GET['asso']) && $_GET['asso'] != null) {
    $assoSelected = array();
    $assos = get_option('manage_dons_asso_list');
    foreach ($assos as $asso) {
        if ($asso['slug'] == $_GET['asso']) {
            $assoSelected = $asso;
            $assoSelected['association'] = stripslashes($assoSelected['association']);
        }
    }
    echo json_encode($assoSelected);
}
if (isset($_GET['rmAsso']) && $_GET['rmAsso'] != null) {
    $assoUpdate = array();
    $assos = get_option('manage_dons_asso_list');
    foreach ($assos as $asso) {
        if ($asso['slug'] != $_GET['rmAsso']) {
            array_push($assoUpdate, $asso);
        }
    }
    update_option('manage_dons_asso_list', $assoUpdate);
    echo null;
}
?>
