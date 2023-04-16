<?php

    class Session {

        

        static function session_start() {

            if(session_status() != PHP_SESSION_ACTIVE)
                session_start();
            
        }


  

        static function userLoggedIn()  {

            return isset($_SESSION['user']);
        }


        static function loginGuard() {

            if(!Session::userLoggedIn()) {
    
                header('HTTP/1.0 401 Unauthorized');
                header('location: /mmarchia/exam/php/error.php');
                $_SESSION['fatalErrorMessage'] = "Devi effettuare il login per accedere a questa pagina.";
                
                die();
            }

            return true;
        }


        
        static function adminGuard() {

            // posso assumere che 'access' esista perchè se l'utente è autenticato, le variabili sono inizializzate

            if(Session::loginGuard() && $_SESSION['access'] != 1) {

                header('HTTP/1.0 403 Forbidden');
                header('location: /mmarchia/exam/php/error.php');
                $_SESSION['fatalErrorMessage'] = "Non hai i permessi per accedere a questa pagina.";
                die();
            }
        }
    

    }