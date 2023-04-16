<?php

    require_once('../helpers/Session.php');
    require_once('../helpers/Utils.php');
    require_once('../helpers/DBAccess.php');
    require_once("../helpers/Errors.php");

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
    
    include('../validators/val_match.php');


    if(Errors::validationErrorOccured())
        Utils::saveFormValues();

    else {

        // controllo uguaglianza squadre
        if($_POST['calendar_host_team'] == $_POST['calendar_guest_team']) {
            Utils::saveFormValues();
            Errors::addValidationError('%CALENDAR_GUEST_TEAM_ERROR%', 'Le due squadre devono essere diverse.');
        
        } else {

            
            ##############################################################
            ###  Processazione risultati e interazione con il databse  ###
            ##############################################################

            $query = 'INSERT INTO partita (id_host, id_guest, id_stadio, data, costo1, costo2, costo3, costo4, giornata)
                    VALUES (:id_host, :id_guest, :id_stadio, :data, :costo_platea, :costo_tribuna, :costo_spalti, :costo_curva, :giornata)';

            $args = [
                ':id_host'       => $_POST['calendar_host_team'],
                ':id_guest'      => $_POST['calendar_guest_team'],
                ':id_stadio'     => $_POST['calendar_stadium'],
                ':data'          => $_POST['calendar_date'].' '.$_POST['calendar_time'].':00',
                ':giornata'      => $_POST['calendar_day'],
                ':costo_platea'  => $_POST['calendar_price_platea'],
                ':costo_tribuna' => $_POST['calendar_price_tribuna'],
                ':costo_spalti'  => $_POST['calendar_price_spalti'],
                ':costo_curva'   => $_POST['calendar_price_curva'],];



            $result = $db->easyDBQuery($query, $args);

            if(isset($result) && $result) {
                Errors::addSuccessMessage('%GENERAL_MATCH_MESSAGE%', 'Partita inserita con successo.');
                header('location: ../dashboard_admin.php');
                die();
            }

            else {
                Utils::saveFormValues();
                Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Partita non inserita correttamente, contattare l\'amministratore di sistema.');
            }

        }

    }


    header('location: ../add_match.php');

?>
