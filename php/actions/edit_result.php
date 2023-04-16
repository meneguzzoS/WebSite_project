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


    ###########################
    ###  Validazione input  ###
    ###########################

    $db = new DB\DBAccess();

    include('../validators/val_match_res.php');

    if(Errors::validationErrorOccured())
        Utils::saveFormValues();

    else {

        
        ####################################################################
        ###  Processazione risultati query e interazione con il databse  ###
        ####################################################################

        $query = 
            'UPDATE partita 
            SET risultato1 = :result_host,
                risultato2 = :result_guest
            WHERE id = :id';

        $args = [
            ':result_host'   => $_POST['result_host'],
            ':result_guest'  => $_POST['result_guest'],
            ':id'            => $_SESSION['editingMatch']['id']];

        $result = $db->easyDBQuery($query, $args);

        if(isset($result) && $result)  {// se la query ha successo viene registrato un successo
            Errors::addSuccessMessage('%GENERAL_MATCH_MESSAGE%', 'Risultato aggiornato con successo.');

            // viene eliminato l'indicatore della modifica in corso
            unset($_SESSION['editingMatch']['id']);

            // il server rimanda alla dashboard notificando il risultato
            header('location: ../dashboard_admin.php');
            die();

        } else {  // in quessto caso l'utente non può fare niente ma l'errore viene riportato comunque e viene data la possibilità di rivedere i dati
            Utils::saveFormValues();
            Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Risultato non aggiornato correttamente, contattare l\'amministratore di sistema.');
        }

    }


    // in caso di errore il server rimanda al form per la notifica degli errorei ed il tentativo di correzione
    header('location: ../edit_match.php?id=' . $_SESSION['editingMatch']['id']);

?>
