<?php

	require_once("helpers/Utils.php");
	require_once("helpers/Session.php");
	require_once("helpers/DBAccess.php");
    require_once('helpers/Tables.php');

	Session::session_start();

	Utils::sanitizeInput();


    #####################################
    ###  Interazione con il database  ###
    #####################################



    $tablestring = '';

    $db = new DB\DBAccess();

    $rows = $db->easyDBSelectQuery(
        'SELECT Sq.nome AS NomeSquadra,
            St.nome AS NomeStadio,
            Sq2.nome AS NomeSquadraAvversaria,
            P.data,
            P.giornata,
            P.risultato1,
            P.risultato2
        FROM partita AS P, stadio AS St, squadra AS Sq, squadra AS Sq2
        WHERE P.id_stadio = St.id
            AND P.id_host = Sq.id
            AND St.id = P.id_stadio
            AND Sq.id = P.id_host AND Sq2.id = P.id_guest            
            AND (P.id_host = 1 OR P.id_guest = 1)
        ORDER BY P.giornata'
        
    );


    #####################################
    ### Processazione risultati query ###
    #####################################

    $tablestring .= Tables::buildCalendarTable($rows);
 

    #######################
    ###  Render pagina  ###
    #######################

    $breadcrumb = '<li aria-current="page">Calendario</li>';

	$page  = Utils::template_replace("%TITLE%", "Calendario | A.C. Torre Archimede", "../html/head.html");
    $page  = str_replace("%DESCRIPTION%", "Visualizza le prossime partite e quelle disputate dalla A.C. Torre Archimede", $page);
	$page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page = str_replace('><a href="calendario.php">Calendario</a>', ' id="current_page">Calendario',  $page);

	$page_body = file_get_contents("../html/calendario.html");

    $page_body = str_replace('%ADMIN_DASHBOARD_TABLE%', $tablestring, $page_body);

    $page .= $page_body;
    unset($page_body);

    $page  = str_replace('%ID%', "calendario-content", $page);
    $page .= file_get_contents("../html/footer.html");


    $page = Utils::removePlaceholders($page);
    echo $page;

?>
