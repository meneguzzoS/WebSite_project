<?php

require_once('../helpers/Session.php');
require_once('../helpers/Utils.php');
require_once('../helpers/DBAccess.php');
require_once('../helpers/Errors.php');


Session::session_start();

	Utils::sanitizeInput();

$db = new DB\DBAccess();


include('../validators/val_edit_pass.php');

if(Errors::validationErrorOccured())
    Utils::saveFormValues();

else {
    $query = 'UPDATE utente SET password = :password WHERE email = :email';

    $args = [':password' => hash('md5', $_POST['newpassword']),
             ':email' => $_POST['email']];

    $result = $db->easyDBQuery($query, $args);

    if(isset($result) && $result) {// se la uqery ha esito negativo, la a vechcia e nuova password coincidono, quindi agli occhi dell'utente non ci sono problemi
        Errors::addSuccessMessage('%GENERAL_LOGIN_MESSAGE%', '<span lang="en">Password</span> aggiornata con successo.');
        header('location: ../login.php');
        die();
    }

    else {
        Utils::saveFormValues();
        Errors::addServerError('%CHANGE_PASSWORD_MESSAGE%', 'Impossibile aggiornare la <span lang="en">password</span>.');
    }

}


header('location: ../recupero_password.php');
