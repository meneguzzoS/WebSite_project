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


    #####################################
    ###  Interazione con il database  ###
    #####################################

    $db = new DB\DBAccess();
    $db->openDBConnection();

    $teams = $db->DBSelectQuery(
       'SELECT *
        FROM squadra');

    $stadiums = $db->DBSelectQuery(
        'SELECT *
         FROM stadio');

    $db->closeDBConnection();
    unset($db);

    if(!isset($teams) || !isset($stadiums) || $teams == [] || $stadiums == []) {
        Errors::addServerError('%GENERAL_MATCH_MESSAGE%', 'Impossibile aggiungere una partita');
        header('location: dashboard_admin.php');
        die();
    }

    #####################################
    ### Processazione risultati query ###
    #####################################

    $hostteamstring = '<option value="" selected>---</option>' . "\n";
    foreach($teams as $team) {
        $selected = isset($_SESSION['formValues']['calendar_host_team']) && $_SESSION['formValues']['calendar_host_team'] == $team['id'] ? 'selected' : '';
        $hostteamstring .= '<option value="' . $team['id'] . '" ' . $selected . '>' . $team['nome'] . '</option>' . "\n"; 
    }

    $guestteamstring = '<option value="" selected>---</option>' . "\n";
    foreach($teams as $team) {
        $selected = isset($_SESSION['formValues']['calendar_guest_team']) && $_SESSION['formValues']['calendar_guest_team'] == $team['id'] ? 'selected' : '';
        $guestteamstring .= '<option value="' . $team['id'] . '" ' . $selected . '>' . $team['nome'] . '</option>' . "\n"; 
    }

    $stadiumstring = '<option value="" selected>---</option>' . "\n";
    foreach($stadiums as $stadium) {
        $selected = isset($_SESSION['formValues']['calendar_stadium']) && $_SESSION['formValues']['calendar_stadium'] == $stadium['id'] ? 'selected' : '';
        $stadiumstring .= '<option value="' . $stadium['id'] . '" ' . $selected . '>' . $stadium['nome'] . '</option>' . "\n"; 
    }


    #######################
    ###  Render pagina  ###
    #######################

    $breadcrumb = '<li><a href="dashboard.php">Area personale</a></li><li><a href="dashboard_admin.php" lang="en">Dashboard</a></li><li lang="en"aria-current="page">Aggiungi partita</li>';

    $page  = Utils::template_replace("%TITLE%", "Dashboard ADMIN | A.C. Torre Archimede", "../html/head.html");
    $page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page_body = file_get_contents("../html/add_match.html");

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

    echo $page;

?>