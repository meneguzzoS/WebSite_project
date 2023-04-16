<?php

require_once("../helpers/DBAccess.php");
require_once("../helpers/Session.php");
require_once('../helpers/Utils.php');
require_once('../helpers/Errors.php');

Session::session_start();

	Utils::sanitizeInput();



if(Session::userLoggedIn()) {
    // TODO: #1 message already logged in
    header('location: dashboard.php');
    die();
}



$db = new DB\DBAccess();

include('../validators/val_signup.php');



if(Errors::validationErrorOccured()) {
    Utils::saveFormValues();
    Errors::addServerError('%GENERAL_SIGNUP_MESSAGE%', 'Impossibile portare a termine la registrazione, contattare l\'amministratore.');
        header('location: ../signup.php');
}

else {
    $result = $db->easyDBSelectQuery("SELECT COUNT(*) FROM utente WHERE email = '".$_POST['email']."'");
    if($result[0]['COUNT(*)'] != '0'){
        Utils::saveFormValues();
        Errors::addServerError('%GENERAL_SIGNUP_MESSAGE%', 'Questa <span lang="en">email</span> è già in uso.');
        header('location: ../signup.php');
        die();
    }

    $query = 'INSERT INTO utente (nazione, nome, cognome, email, password, livello_privilegi, indirizzo, citta, cap, telefono, data_di_nascita)
                VALUES (:nazione, :nome, :cognome, :email, :password, :livello_privilegi, :indirizzo, :citta, :cap, :telefono, :data_di_nascita)';

    $args = [':nazione'              => $_POST['nazione'],
             ':nome'                 => $_POST['nome'],
             ':cognome'              => $_POST['cognome'],
             ':email'                => $_POST['email'],
             ':password'             => hash('md5', $_POST['password']),
             ':livello_privilegi'    => "0",
             ':indirizzo'            => $_POST['indirizzo'],
             ':citta'                => $_POST['citta'],
             ':cap'                  => $_POST['cap'],
             ':telefono'             => $_POST['telefono'],
             ':data_di_nascita'      => $_POST['compleanno'],];

    $result = $db->easyDBQuery($query, $args);

    if(!isset($result) || !$result) {  
        Errors::addServerError('%GENERAL_SIGNUP_MESSAGE%', 'Impossibile portare a termine la registrazione, contattare l\'amministratore.');
        header('location: ../signup.php');
        die();
    }

    Errors::addSuccessMessage('%GENERAL_LOGIN_MESSAGE%', "Registrazione effettuata con successo.");
    header("Location: ../login.php");
}
?>
