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

    include('../validators/val_edit_match.php');

    if(Errors::validationErrorOccured())
        Utils::saveFormValues();

    else {

        // controllo uguaglianza squadre
        // TODO: #20 into validator?
        if($_POST['calendar_host_team'] == $_POST['calendar_guest_team']) {
            Utils::saveFormValues();
            Errors::addValidationError('%CALENDAR_GUEST_TEAM_ERROR%', 'Le due squadre devono essere diverse');
        
        } else {


            ####################################################################
            ###  Processazione risultati query e interazione con il databse  ###
            ####################################################################

            $query = 
               'UPDATE partita 
                SET id_host = :id_host,
                    id_guest = :id_guest,
                    id_stadio = :id_stadio,
                    data = :data
                WHERE id = :id';

            $args = [
                ':id_host'       => $_POST['calendar_host_team'],
                ':id_guest'      => $_POST['calendar_guest_team'],
                ':id_stadio'     => $_POST['calendar_stadium'],
                ':data'          => $_POST['calendar_date'].' '.$_POST['calendar_time'].':00',
                ':id'            => $_SESSION['editingMatch']['id']];

            $result = $db->easyDBQuery($query, $args);

            if(isset($result) && $result)  {// se la query ha successo viene registrato un successo
                Errors::addSuccessMessage('%GENERAL_MATCH_MESSAGE%', 'Partita modificata con successo.');

                // viene eliminato l'indicatore della modifica in corso
                unset($_SESSION['editingMatch']['id']);

                // il server rimanda alla dashboard notificando il risultato
                header('location: ../dashboard_admin.php');
                die();

            } else {  // in quessto caso l'utente non può fare niente ma l'errore viene riportato comunque e viene data la possibilità di rivedere i dati
                Utils::saveFormValues();
                Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Partita non midificata correttamente, contattare l\'amministratore  di sistema.');
            }

        }

    }


    // in caso di errore il server rimanda al form per la notifica degli errorei ed il tentativo di correzione
    header('location: ../edit_match.php?id=' . $_SESSION['editingMatch']['id']);

?>
