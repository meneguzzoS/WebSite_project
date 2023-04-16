<?php

    use DB\DBAccess;

    require_once("helpers/Utils.php");
    require_once("helpers/DBAccess.php");
    require_once("helpers/Session.php");
    require_once("helpers/Tables.php");
    require_once("helpers/Errors.php");

    Session::session_start();

	Utils::sanitizeInput();

    
    ##########################
    ###  Gestione accessi  ###
    ##########################

    Session::loginGuard();
    

    ###################################
    ### Interazione con il database ###
    ###################################
    
    $db = new DBAccess;
    $db->openDBConnection();

    $anagraphicResult = $db->DBSelectQuery(
        'SELECT *
        FROM utente
        WHERE id = :id', 
        
        [':id' => $_SESSION['userId']]
    )[0];

    $ticketsFollow = $db->DBSelectQuery(
        'SELECT Sq.nome AS NomeSquadra,
        St.nome AS NomeStadio,
        Sq2.nome AS NomeSquadraAvversaria,
        P.id,
        P.data,
        P.costo1,
        P.costo2,
        P.costo3,
        P.costo4,
        B.id_posto,
        B.id as idBiglietto,
        P.giornata,
        P.risultato1,
        P.risultato2,
        Pos.settore as NomePosto
        FROM  biglietto AS B, partita AS P, stadio as St, squadra as Sq, squadra as Sq2, posto as Pos
        WHERE B.id_partita = P.ID
            AND B.id_utente = :id
            AND P.data >= NOW()
            AND Sq.id = P.id_host
            AND Sq2.id = P.id_guest
            AND St.id = P.id_stadio
            AND Pos.id = B.id_posto', 

        [':id' => $_SESSION['userId']]
    );

    $ticketsPast = $db->DBSelectQuery(
        'SELECT Sq.nome AS NomeSquadra,
        St.nome AS NomeStadio,
        Sq2.nome AS NomeSquadraAvversaria,
        P.id,
        P.data,
        P.costo1,
        P.costo2,
        P.costo3,
        P.costo4,
        B.id_posto,
        B.id as idBiglietto,
        P.giornata,
        P.risultato1,
        P.risultato2,
        Pos.settore as NomePosto
        FROM  biglietto AS B, partita AS P, stadio as St, squadra as Sq, squadra as Sq2, posto as Pos
        WHERE B.id_partita = P.ID
            AND B.id_utente = :id
            AND P.data <= NOW()
            AND Sq.id = P.id_host
            AND Sq2.id = P.id_guest
            AND St.id = P.id_stadio
            AND Pos.id = B.id_posto', 

        [':id' => $_SESSION['userId']]

    );

    $db->closeDBConnection();
    unset($db);

    #####################################
    ### Processazione risultati query ###
    #####################################
    if(isset($anagraphicResult) && $anagraphicResult != []) {

    $html = '
        <dt>Nome:</dt><dd>'                                                                         . $anagraphicResult['nome']            . '</dd>
        <dt>Cognome:</dt><dd>'                                                                      . $anagraphicResult['cognome']         . '</dd>
        <dt>Data di nascita:</dt><dd>'                                                              . $anagraphicResult['data_di_nascita'] . '</dd>
        <dt>Nazione:</dt><dd>'                                                                      . $anagraphicResult['nazione']         . '</dd>
        <dt>Citt&agrave;:</dt><dd>'                                                                 . $anagraphicResult['citta']           . '</dd>
        <dt>Indirizzo:</dt><dd>'                                                                    . $anagraphicResult['indirizzo']       . '</dd>
        <dt><abbr title="Codice di Avviamento Postale">CAP</abbr>:</dt><dd>'                        . $anagraphicResult['cap']             . '</dd>
        <dt lang="en">Email:</dt><dd>'                                                              . $anagraphicResult['email']           . '</dd>
        <dt><abbr title="Telefono">Tel.</abbr>/<abbr title="Cellulare">Cell.</abbr>:</dt><dd>'      . $anagraphicResult['telefono']        . '</dd>';
    } else {
        $html = '%ANAGRAFICA%';
        Errors::addServerError('%ANAGRAFICA%', 'Si è verificato un errore inatteso, contattare l\'amministratore o riprovare più tardi.');
    }

    #####################
    ### Render pagina ###
    #####################

    $breadcrumb = '<li aria-current="page">Area personale</li>';

    $page  = Utils::template_replace("%TITLE%", "Aree personale | A.C. Torre Archimede", "../html/head.html");
    $page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page = str_replace('><a href="dashboard.php">Area personale</a>', ' id="current_page">Area personale',  $page);

    $page .= file_get_contents("../html/dashboard.html");
    
    if(isset($_SESSION["access"]) && $_SESSION["access"] == "1")
        $page = str_replace('%DASHBOARD_ADMIN%', '<form><button id="switchDashboard" formaction="dashboard_admin.php">Modalità Admin</button></form>', $page);
    
    if(!isset($ticketsFollow))
        Errors::addServerError('%ULTIME_PARTITE%', 'Si è verificato un errore inatteso, contattare l\'amministratore o riprovare più tardi.');
    else
        $page = str_replace(
            '%PROSSIME_PARTITE%',
            Tables::buildDashboardTable(
                $ticketsFollow,
                'Tabella che raccoglie i biglietti acquistati con relativi dettagli delle partite future',
                'Calendario dei biglietti per partite future',
                'followingTicketsTable'),
            $page);

    if(!isset($ticketsPast))
        Errors::addServerError('%PROSSIME_PARTITE%', 'Si è verificato un errore inatteso, contattare l\'amministratore o riprovare più tardi.');
    else
        $page = str_replace(
            '%ULTIME_PARTITE%',
            Tables::buildDashboardTable(
                $ticketsPast,
                'Tabella che raccoglie i biglietti acquistati con relativi dettagli delle partite passate',
                'Calendario dei biglietti per partite passate',
                'pastTicketsTable'),
            $page);


    $page  = str_replace('%ANAGRAFICA%', $html, $page);

    $page .= file_get_contents("../html/footer.html");
        
    $page  = str_replace('%ID%', "dashboard-content", $page);

    $page = Errors::handleErrorsAndMessages($page);
    $page = Utils::removePlaceholders($page);


    echo $page;

?>
