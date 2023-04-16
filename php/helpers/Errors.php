<?php

require_once("Session.php");

class Errors {

    /*
     * STORE MESSAGE
     */

    static function addValidationError($placeholder, $message) {

        if(!isset($_SESSION['validation_errors'][$placeholder]))
            $_SESSION['validation_errors'][$placeholder] = $message;
        else
            $_SESSION['validation_errors'][$placeholder] .= '<br/>' . $message;

    }

    static function addServerError($placeholder, $message) {

        $_SESSION['server_errors'][$placeholder] = $message;

    }

    static function addSuccessMessage($placeholder, $message) {

        $_SESSION['success_messages'][$placeholder] = $message;

    }


    /*
     * PROCESS MESSAGE
     */

    static function handleErrorsAndMessages($page) {

        if(isset($_SESSION['server_errors'])) {

            foreach($_SESSION['server_errors'] as $placeholder => $error) {
                $page = str_replace(
                    $placeholder,
                    '<p class="severError">' . $error . '</p>',
                    $page
                );
            }
        }

        if(isset($_SESSION['validation_errors'])) {

            foreach($_SESSION['validation_errors'] as $placeholder => $error) {
                $page = str_replace(
                    substr($placeholder, 0, -1) . '_CLASS%',
                    'class="validationError"',
                    $page
                );
                $page = str_replace(
                    substr($placeholder, 0, -1) . '_DESCRIBEDBY%',
                    'aria-describedby="' . $error['id'] . 'Error"',
                    $page
                );
                $page = str_replace(
                    $placeholder,
                    '<p id="' . $error['id'] . 'Error">' . $error['message'] . '</p>',
                    $page
                );

            }
                
        }

        if(isset($_SESSION['success_messages'])) {

            foreach($_SESSION['success_messages'] as $placeholder => $message)
                $page = str_replace(
                    $placeholder,
                    '<p class="successinfo">' . $message . '</p>',
                    $page
                );
        }
        
        unset($_SESSION['validation_errors']);
        unset($_SESSION['server_errors']);
        unset($_SESSION['success_messages']);

        return $page;

    }

     /*
     * BOOLEAN TESTS
     */

    static function validationErrorOccured() {
        return isset($_SESSION['validation_errors']);
    }
}
