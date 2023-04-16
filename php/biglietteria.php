<?php

	require_once("helpers/Utils.php");
	require_once("helpers/Session.php");
	require_once("helpers/DBAccess.php");
    require_once('helpers/Tables.php');
    require_once("helpers/Errors.php");

	Session::session_start();

	Utils::sanitizeInput();


    #####################################
    ###  Interazione con il database  ###
    #####################################

    $db = new DB\DBAccess();

    $rows = $db->easyDBSelectQuery(
        'SELECT Sq.nome AS NomeSquadra,
            St.nome AS NomeStadio,
            Sq2.nome AS NomeSquadraAvversaria,
            P.id,
            P.id_stadio,
            P.data,
            P.costo1,
            P.costo2,
            P.costo3,
            P.costo4,
            P.giornata
        FROM partita AS P, stadio AS St, squadra AS Sq, squadra AS Sq2
        WHERE P.id_stadio = St.id
            AND P.id_host = Sq.id
            AND P.id_guest = Sq2.id
            AND P.data >= NOW()
        ORDER BY P.giornata'
    );


    #####################################
    ### Processazione risultati query ###
    #####################################

    $tablestring = Tables::buildBiglietteriaTable($rows);

    
    #######################
    ###  Render pagina  ###
    #######################

    $breadcrumb = '<li aria-current="page">Biglietteria</li>';

	$page  = Utils::template_replace("%TITLE%", "Biglietteria | A.C. Torre Archimede", "../html/head.html");
    $page  = str_replace("%DESCRIPTION%", "Acquista i tuoi biglietti delle partite disputate nello stadio stadio Torre Archimede", $page);
	$page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page = str_replace('><a href="biglietteria.php">Biglietteria</a>', ' id="current_page">Biglietteria',  $page);

	$page_body = file_get_contents("../html/biglietteria.html");

    $page_body = str_replace('%ADMIN_DASHBOARD_TABLE%', $tablestring, $page_body);

    $page .= $page_body;
    unset($page_body);

    $page .= file_get_contents("../html/footer.html");

    $page  = str_replace('%ID%', "biglietteria-content", $page);
    $page = Errors::handleErrorsAndMessages($page);
    $page = Utils::removePlaceholders($page);
    echo $page;

?>
