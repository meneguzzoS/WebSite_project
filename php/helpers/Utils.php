<?php

require_once("Session.php");

class Utils {

    static function is_uppercase($arg) {

        return preg_match('~^\p{Lu}~u', $arg);

    }

    static function saveFormValues() {

        foreach($_POST as $key => $value)
            $_SESSION['formValues'][$key] = $value;

    }

    static function fillBackForm($page) {

        if(isset($_SESSION['formValues'])) {
            foreach($_SESSION['formValues'] as $key => $value)
                $page = str_replace(
                    '%'.strtoupper($key).'%',
                    $value,
                    $page
                );
            unset($_SESSION['formValues']);
        }

        return $page;

        //TODO:should check a waya to handle <select>s and radios
    }

    static function pendingFormValues() {
        return isset($_SESSION['formValues']);
    }

    static function removePlaceholders($page) {

        $page = preg_replace('/%[A-Z_0-9]*%/', '', $page);

        return $page;

    }


    static function sanitizeInput() {

        foreach($_GET as $key => $val) {
            $val = trim($val);
            $val = htmlentities($val);
            $_GET[$key] = $val;
        }

        foreach($_POST as $key => $val) {
            $val = trim($val);
            $val = htmlentities($val);
            $_POST[$key] = $val;
        }

    }


    static function template_replace($placeholder, $value, $path) {

        $page = file_get_contents($path);
        $page = str_replace($placeholder,$value,$page);

        return $page;
    }



    static function dateTimeFormat_compareToNow($dateTime_format) {
        
        $format_time = strtotime($dateTime_format);
        $actual_time = strtotime(date('Y-m-d H:i:s'));

        return $format_time - $actual_time;
    }

}
