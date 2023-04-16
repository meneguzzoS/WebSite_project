<?php

    require_once("helpers/Utils.php");
    require_once("helpers/Session.php");
    require_once("helpers/DBAccess.php");
    require_once("helpers/Tables.php");
    require_once("helpers/Errors.php");

    Session::session_start();

	Utils::sanitizeInput();

    
    ##########################
    ###  Gestione accessi  ###
    ##########################

    Session::adminGuard();

    if(!isset($_GET['id']) || !ctype_digit($_GET['id']) || $_GET['id'] <= 0) { //richeista mal formata
        Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Non è stato possibile modificare la partita richeista.');
        header('location: dashboard_admin.php');
        die();
    }


    #####################################
    ###  Interazione con il database  ###
    #####################################

    $db = new DB\DBAccess();
    $db->openDBConnection();

   
    $query =   'SELECT COUNT(*) AS count
                FROM partita
                WHERE id = :id';

    $args = [':id' => $_GET['id']];


    $result = $db->DBSelectQuery($query, $args);
        // ritorno a dashboard, err => partita non esiste
    if(!isset($result) || $result[0]['count'] != 1) {
        Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Non è stato possibile modificare la partita richeista.');
        header('location: dashboard_admin.php');
        die();
    }

    
    $query =   'SELECT *
                FROM squadra';

    $teams = $db->DBSelectQuery($query);


    $query =   'SELECT *
                FROM stadio';

    $stadiums = $db->DBSelectQuery($query);

    if(!isset($teams) || !isset($stadiums) || $teams == [] || $stadiums == []) {
        Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Non è stato possibile modificare la partita richeista.');
        header('location: dashboard_admin.php');
        die();
    }



    $query =   'SELECT *
                FROM partita
                WHERE id = :id';

    $args = [':id' => $_GET['id']];

    $match = $db->DBSelectQuery($query, $args)[0];

    if(!isset($match) || $match == []) {
        Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Non è stato possibile modificare la partita richeista.');
        header('location: dashboard_admin.php');
        die();
    }

    if(!isset($_SESSION['formValues']['calendar_day']))           $_SESSION['formValues']['calendar_day']           = $match['giornata'];
    if(!isset($_SESSION['formValues']['calendar_date']))          $_SESSION['formValues']['calendar_date']          = substr($match['data'], 0, 10);
    if(!isset($_SESSION['formValues']['calendar_time']))          $_SESSION['formValues']['calendar_time']          = substr($match['data'], 11, 5);
    if(!isset($_SESSION['formValues']['calendar_stadium']))       $_SESSION['formValues']['calendar_stadium']       = $match['id_stadio'];
    if(!isset($_SESSION['formValues']['calendar_host_team']))     $_SESSION['formValues']['calendar_host_team']     = $match['id_host'];
    if(!isset($_SESSION['formValues']['calendar_guest_team']))    $_SESSION['formValues']['calendar_guest_team']    = $match['id_guest'];
    if(!isset($_SESSION['formValues']['calendar_price_platea']))  $_SESSION['formValues']['calendar_price_platea']  = $match['costo1'];
    if(!isset($_SESSION['formValues']['calendar_price_tribuna'])) $_SESSION['formValues']['calendar_price_tribuna'] = $match['costo2'];
    if(!isset($_SESSION['formValues']['calendar_price_spalti']))  $_SESSION['formValues']['calendar_price_spalti']  = $match['costo3'];
    if(!isset($_SESSION['formValues']['calendar_price_curva']))   $_SESSION['formValues']['calendar_price_curva']   = $match['costo4'];
    if(!isset($_SESSION['formValues']['result_host']))            $_SESSION['formValues']['result_host']            = $match['risultato1'];
    if(!isset($_SESSION['formValues']['result_guest']))           $_SESSION['formValues']['result_guest']           = $match['risultato2'];


    $db->closeDBConnection();


    #####################################
    ### Processazione risultati query ###
    #####################################



    $hostteamstring = '';
    $singlehost = '';
    foreach($teams as $team) {
        $selected = $_SESSION['formValues']['calendar_host_team'] == $team['id'] ? 'selected' : '';
        $hostteamstring .= '<option value="' . $team['id'] . '" ' . $selected . '>' . $team['nome'] . '</option>' . "\n"; 
        if($selected)
            $singlehost = $team['nome'];
    }

    $guestteamstring = '';
    $singleguest = '';
    foreach($teams as $team) {
        $selected = $_SESSION['formValues']['calendar_guest_team'] == $team['id'] ? 'selected' : '';
        $guestteamstring .= '<option value="' . $team['id'] . '" ' . $selected . '>' . $team['nome'] . '</option>' . "\n"; 
        if($selected)
            $singleguest = $team['nome'];
    }

    $stadiumstring = '';
    $singlestadium = '';
    foreach($stadiums as $stadium) {
        $selected = $_SESSION['formValues']['calendar_stadium'] == $stadium['id'] ? 'selected' : '';
        $stadiumstring .= '<option value="' . $stadium['id'] . '" ' . $selected . '>' . $stadium['nome'] . '</option>' . "\n"; 
        if($selected)
            $singlestadium = $stadium['nome'];
    }

    #######################
    ###  Render pagina  ###
    #######################

    $breadcrumb = '<li><a href="dashboard.php">Area personale</a></li><li><a href="dashboard_admin.php" lang="en">Dashboard</a></li><li lang="en"aria-current="page">Modifica partita</li>';

    $page = Utils::template_replace("%TITLE%", "Dashboard ADMIN | A.C. Torre Archimede", "../html/head.html");
    $page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");
    
    if(Utils::dateTimeFormat_compareToNow($_SESSION['formValues']['calendar_date'] . ' ' . $_SESSION['formValues']['calendar_time']) > 0 )
        $page_body = file_get_contents("../html/edit_match.html");
        
    else $page_body = file_get_contents("../html/edit_result.html");

    $page_body = str_replace('%CALENDAR_STADIUM_SINGLE%', $singlestadium, $page_body);
    $page_body = str_replace('%CALENDAR_HOST_TEAM_SINGLE%', $singlehost, $page_body);
    $page_body = str_replace('%CALENDAR_GUEST_TEAM_SINGLE%', $singleguest, $page_body);

    $page_body = str_replace('%CALENDAR_STADIUM_LIST%', $stadiumstring, $page_body);
    $page_body = str_replace('%CALENDAR_HOST_TEAM_LIST%', $hostteamstring, $page_body);
    $page_body = str_replace('%CALENDAR_GUEST_TEAM_LIST%', $guestteamstring, $page_body);

    $page_body = str_replace('%ID%', 'addedit-match-content', $page_body);
    $page_body = Errors::handleErrorsAndMessages($page_body);
    $page_body = Utils::fillBackForm($page_body);
    $page_body = Utils::removePlaceholders($page_body);

    $page .= $page_body;
    unset($page_body);

    $page .= file_get_contents("../html/footer.html");

    $page = str_replace('%ID%', 'addedit-match-content', $page);


    // mi serve per passare ad 'actions/edit_match.php' l'id della partita che sto modificando
    // lo faccio alla fine per essere sicuro di impostarlo quando nesosn errore può bloccare l'esecuzione
    $_SESSION['editingMatch']['id'] = $_GET['id'];

    echo $page;

?>