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
    
    
    ###################################
    ### Interazione con il database ###
    ###################################
    
    $db = new DB\DBAccess();

    $rows = $db->easyDBSelectQuery(
        "SELECT Sq.nome AS NomeSquadra,
        St.nome AS NomeStadio,
        Sq2.nome AS NomeSquadraAvversaria,
        P.id,
        P.id_stadio,
        P.data,
        P.costo1,
        P.costo2,
        P.costo3,
        P.costo4,
        P.giornata,
        P.risultato1,
        P.risultato2
        FROM partita AS P, stadio AS St, squadra AS Sq, squadra AS Sq2
        WHERE P.id_stadio = St.id
            AND P.id_host = Sq.id
            AND P.id_guest = Sq2.id
        ORDER BY P.data"
        );

    unset($db);


    #####################################
    ### Processazione risultati query ###
    #####################################

    $tablestring = Tables::buildAdminDashboardTable($rows);


    #####################
    ### Render pagina ###
    #####################

    $breadcrumb = '<li><a href="dashboard.php">Area personale</a></li><li lang="en" aria-current="page">Dashboard</li>';

    $page = Utils::template_replace("%TITLE%", "Dashboard ADMIN | A.C. Torre Archimede", "../html/head.html");
    $page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page_body = file_get_contents("../html/dashboard_admin.html");

    $page_body = str_replace('%ADMIN_DASHBOARD_TABLE%', $tablestring, $page_body);
    
    $page_body = Errors::handleErrorsAndMessages($page_body);
    $page_body = Utils::fillBackForm($page_body);
    

    $page .= $page_body;
    unset($page_body);

    $page .= file_get_contents("../html/footer.html");
    
    $page  = str_replace('%ID%', "adminDashboard-content", $page);

    $page = Utils::removePlaceholders($page);
    echo $page;

?>
