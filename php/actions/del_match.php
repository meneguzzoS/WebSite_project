<?php

    require_once('../helpers/Session.php');
    require_once('../helpers/Utils.php');
    require_once('../helpers/DBAccess.php');
    require_once('../helpers/Errors.php');

    Session::session_start();

	Utils::sanitizeInput();

    
    ##########################
    ###  Gestione accessi  ###
    ##########################

    Session::adminGuard();

    if(!isset($_GET['id'])){
        header('location: ../dashboard_admin.php');
        die();
    }
    
     
    ##############################################################
    ###  Processazione risultati e interazione con il databse  ###
    ##############################################################

    $db = new DB\DBAccess();
      
    $query = 'DELETE FROM partita WHERE id = :id';

    $args = [':id' => $_GET['id']];

    $result = $db->easyDBQuery($query, $args);

    if($result){
        Errors::addSuccessMessage('%GENERAL_MATCH_MESSAGE%', 'Partita eliminata con successo.');
    }    
    header('location: ../dashboard_admin.php');

?>
