<?php

    require_once("../helpers/Utils.php");
    require_once("../helpers/Session.php");
    require_once("../helpers/DBAccess.php");
    require_once("../helpers/Errors.php");

    Session::session_start();

	Utils::sanitizeInput();


    ##########################
    ###  Gestione accessi  ###
    ##########################

    Session::loginGuard();

    
    ##############################################################
    ###  Processazione risultati e interazione con il databse  ###
    ##############################################################

    $db = new DB\DBAccess();
    
    $query =   'INSERT INTO biglietto (id_posto, id_partita, id_utente)
                VALUES (:posto, :partita, :utente)';
    $args = [
        ":posto"   => $_POST['posto'],
        ":partita" => $_SESSION['purchasingMatch']['id'],
        ":utente"  => $_SESSION['userId']];
    
    $db->openDBConnection();
    if($_POST['nbiglietti'] > 5 || $_POST['nbiglietti'] < 1){
        Utils::saveFormValues();
        Errors::addValidationError('%CHECKOUT_MESSAGE%', '', "", "Acquisto non eseguito correttamente, selezionare massimo 5 posti.");
        $db->closeDBConnection();
        header('location: ../checkout.php?id='. $_SESSION['purchasingMatch']['id']);
        die();
    }

    for($i = 0; $i < $_POST['nbiglietti']; ++$i){

        $result = $db->DBQuery($query, $args);

        if(!(isset($result) && $result)){
            Utils::saveFormValues();
            Errors::addServerError('%CHECKOUT_MESSAGE%', 'Acquisto non eseguito correttamente, contattare l\'amministratore.');

            $db->closeDBConnection();

            header('location: ../checkout.php?id='. $_SESSION['purchasingMatch']['id']);
            die();
        }
    }

    Errors::addSuccessMessage('%BIGLIETTERIA_MESSAGE%', 'Acquisto eseguito con successo. Grazie!');
    $db->closeDBConnection();

    unset($_SESSION['purchasingMatch']['id']);

    header('location: ../biglietteria.php');
