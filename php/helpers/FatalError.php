<?php

require_once("Session.php");

class FatalError {
   
    static function load_fatal_error($message = 'FATAL ERROR') {

        Utils::saveFormValues();

        $_SESSION['fatalErrorMessage'] = $message;

        header('location: error.php');
        // include($_SERVER['DOCUMENT_ROOT'] . '/tecweb/php/error.php');
    }

    static function clearErrors() {

        if(isset($_SESSION['fatalErrorMessage']))
            unset($_SESSION['fatalErrorMessage']);

    }

}
