<?php
    require_once("../php/helpers/Utils.php");
    require_once("../php/helpers/Session.php");
    require_once("../php/helpers/DBAccess.php");

    Session::session_start();

	Utils::sanitizeInput();





    $page = Utils::template_replace("%TITLE%", "Error | A.C. Torre Archimede", "../html/head.html");

    $page .= Utils::template_replace("%PAGENAME%", "Error", "../html/navbar.html");

    require_once("../php/navbar.php");
    
    $page.= '<div class="container content" >';


    if(!isset($_GET['error']))
        $_GET['error'] = -1;  //INIZIALIZZA LA VARIABILE

    switch($_GET['error']){
        case 403:
            $error = '<strong>Error 403: Forbidden </strong> <br> The server has refused to fulfill your request.';
            break;
        case 404:
            $error = '<strong>Error 404: Not Found </strong> <br> The document/file requested was not found on this server.';
            break;
        case 405:
            $error = '<strong>Error 405: Method Not Allowed </strong> <br> The method specified in the Request-Line is not allowed for the specified resource.';
            break;
        case 408:
            $error = '<strong>Error 408: Request Timeout </strong> <br> Your browser failed to send a request in the time allowed by the server.';
            break;
        case 500:
            $error = '<strong>Error 500: Internal Server Error </strong> <br> The request was unsuccessful due to an unexpected condition encountered by the server.';
            break;
        case 502:
            $error = '<strong>Error 502: Bad Gateway </strong> <br> The server received an invalid response from the upstream server while trying to fulfill the request.';
            break;
        case 504:
            $error = '<strong>Error 504: Gateway Timeout </strong> <br> The upstream server failed to send a request in the time allowed by the server.';
            break;  
        default:
            if(isset($_SESSION['fatalErrorMessage']))
                $error = $_SESSION['fatalErrorMessage'];
            else{
                header('location: index.php');
                die();
            }
            break;
    }

    $page .= "<p class='error'> $error </p> </div>";

    $page .= file_get_contents("../html/footer.html");

    echo $page;
