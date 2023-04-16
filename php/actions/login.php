<?php

use DB\DBAccess;

require_once("../helpers/DBAccess.php");
require_once("../helpers/Session.php");
require_once('../helpers/Errors.php');
require_once('../helpers/Utils.php');

Session::session_start();

Utils::sanitizeInput();


##########################
###  Gestione accessi  ###
##########################

if(Session::userLoggedIn()) {
    header('location: dashboard.php');
    die();
}


include('../validators/val_login.php');

if(Errors::validationErrorOccured())
    Utils::saveFormValues();
    
else {
    $query = "SELECT livello_privilegi, id, nome FROM utente WHERE email = :email AND password = :pwd";
    $args = [
        ':email' => $_POST['username'],
        ':pwd' => hash('md5', $_POST['password']) ];

    $dsn = new DBAccess();
    $result = $dsn->easyDBSelectQuery($query, $args);


    if(isset($result) && count($result) <= 1) {

        if(count($result) != 0) {

            $_SESSION["user"] = $result[0]['nome'];
            $_SESSION["access"] = $result[0]['livello_privilegi'];
            $_SESSION["userId"] = $result[0]['id'];
            Errors::addSuccessMessage('%GENERAL_DASHBOARD_MESSAGE%', '<span lang="en">Login</span> avvenuto con successo');
            header("Location: ../dashboard.php");
            die();

        } else
            Errors::addServerError('%GENERAL_LOGIN_MESSAGE%', '<span lang="en">E-mail</span> o <span lang="en">password</span> errati.');

    } else
        Errors::addServerError('%GENERAL_LOGIN_MESSAGE', 'Impossibile effetuare il <span lang="en">login</span>, contattare l\'amministratore.');
}

header("Location: ../login.php");

?>
