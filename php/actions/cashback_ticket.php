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

    Session::loginGuard();

    if(!isset($_GET['id'])){
        header('location: ../dashboard.php');
        die();
    }
       

    ####################################################################
    ###  Processazione risultati e interazione con il databse  ###
    ####################################################################

    $db = new DB\DBAccess();
    
    $query = 'DELETE FROM biglietto 
                WHERE id = ' . $_GET['id'].' AND id_utente = '.$_SESSION['userId'];

    $result = $db->easyDBQuery($query);

    if($result){
        Errors::addSuccessMessage('%GENERAL_MATCH_MESSAGE%', 'Partita rimborsata con successo!');
    }    
    header('location: ../dashboard.php');

?>
