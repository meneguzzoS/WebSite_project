<?php

    require_once("../helpers/Utils.php");
    require_once("../helpers/Session.php");
    require_once("../helpers/DBAccess.php");
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

    require_once('../validators/val_add_article.php');


    if(Errors::validationErrorOccured())
        Utils::saveFormValues();

    else {


        ##############################################################
        ###  Processazione risultati e interazione con il databse  ###
        ##############################################################
        
        $db = new DB\DBAccess();

        $query =   'INSERT INTO articolo (titolo, corpo, data_inserimento, percorso_img)
                    VALUES (:title, :body, :date, :img)';
    
        $args = [
            'title' => $_POST['news_title'],
            ':body' => $_POST['news_body'],
            ':date' => date("Y-m-d H:i:s"),
            ':img'  => NULL];

        if(isset($_FILES['news_backgroundImg'])&& $_FILES['news_backgroundImg']['error'] != UPLOAD_ERR_NO_FILE) {

            $img_dir = "../../images/news/";

            $instant = new DateTime('now', new DateTimeZone('UTC'));
            $instant_format = $instant->format('YmdHis');

            $filename = $_FILES["news_backgroundImg"]["name"]; 
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            $newfilename = $instant_format . '-' . hash('md5', $_FILES["news_backgroundImg"]["name"] . $instant_format) . '.' . $extension;

            move_uploaded_file($_FILES["news_backgroundImg"]["tmp_name"], $img_dir . $newfilename);

            $args[':img'] = 'news/' . $newfilename;
        }

        $result = $db->easyDBQuery($query, $args);

        if(isset($result) && $result)
            Errors::addSuccessMessage('%GENERAL_NEWS_MESSAGE%', 'Articolo inserito con successo.');

        else{

            Utils::saveFormValues();
            Errors::addServerError('%GENERAL_NEWS_MESSAGE%', 'Articolo non inserito correttamente, contattare l\'amministratore.');
        }


    }
    header('location: ../dashboard_admin.php');
?>
