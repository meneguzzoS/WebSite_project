<?php
    require_once ("helpers/Utils.php");
    require_once ("helpers/Session.php");
    require_once ("helpers/DBAccess.php");
    require_once ('helpers/Tables.php');
    require_once ('helpers/Errors.php');

    Session::session_start();

	Utils::sanitizeInput();


    ##########################
    ###  Gestione accessi  ###
    ##########################

    Session::loginGuard();
    
    if(!isset($_GET['id']) || !ctype_digit($_GET['id']) || $_GET['id'] <= 0)  {
        Errors::addServerError('%BIGLIETTERIA_MESSAGE%', 'Non è stato possibile effettuare l\'acquisto.');
        header("location: biglietteria.php");
        die();
    }

    
    #####################################
    ###  Interazione con il database  ###
    #####################################

    $db = new DB\DBAccess();
    $db->openDBConnection();

    $rows = $db->DBSelectQuery(
        'SELECT squadra.nome as NomeSquadra,
            squadra2.nome as NomeGuest,
            stadio.nome as NomeStadio, 
            stadio.indirizzo as StadioIndirizzo,
            partita.id, partita.id_host,
            partita.id_guest,
            partita.id_stadio,
            partita.data,
            partita.costo1 as costo1,
            partita.costo2 as costo2,
            partita.costo3 as costo3,
            partita.costo4 as costo4
        FROM partita,
            stadio,
            squadra,
            squadra as squadra2
        WHERE partita.id_stadio = stadio.id 
            AND partita.id_host = squadra.id 
            AND squadra2.id = partita.id_guest
            AND stadio.nome = \'Torre Archimede\'
            AND partita.id = :id',
            
        [':id' => $_GET['id']])[0];

    $settori = $db->DBSelectQuery("SELECT id, settore FROM posto");

    $anagraphicResult = $db->DBSelectQuery(
        'SELECT *
        FROM utente
        WHERE id = :id', 
        
        [':id' => $_SESSION['userId']]
    )[0];

    $db->closeDBConnection();
    unset($db);

    if(!isset($anagraphicResult) || !isset($rows) || !isset($settori) || $anagraphicResult == [] ||$rows == [] || $settori == []) {
        Errors::addServerError('%BIGLIETTERIA_MESSAGE%', 'Si è verificato un errore inatteso, contattare l\'amministratore o riprovare più tardi.');
        header("location: biglietteria.php");
        die();
    }


    #####################################
    ### Processazione risultati query ###
    #####################################

    $datipartita = '<dl><dt>Sfidanti:</dt><dd>' . $rows['NomeSquadra'] . ' - ' . $rows['NomeGuest'] . '</dd> ';
    $datipartita .= '<dt>Data:</dt><dd>' . $rows['data'] . '</dd> ';
    $datipartita .= '<dt>Stadio:</dt><dd>' . $rows['NomeStadio'] . '</dd> ';
    $datipartita .= '<dt>Indirizzo:</dt><dd>' . $rows['StadioIndirizzo'] . '</dd></dl> ';
    
    $tabellaposti= '	<table id="prezzipartita">
                            <caption>Prezzi della partita per settore</caption>
                            <thead><tr>
                                <th scope="col">'.$settori[0]['settore'].'</th>
                                <th scope="col">'.$settori[1]['settore'].'</th>
                                <th scope="col">'.$settori[2]['settore'].'</th>
                                <th scope="col">'.$settori[3]['settore'].'</th>
                            </tr></thead>
                            <tbody><tr>
                                <td id="costo1" data-label="Platea">'.$rows['costo1'].'.00€</td>  
                                <td id="costo2" data-label="Tribuna">'.$rows['costo2'].'.00€</td>
                                <td id="costo3" data-label="Spalti">'.$rows['costo3'].'.00€</td>
                                <td id="costo4" data-label="Curva">'.$rows['costo4'].'.00€</td>
                            </tbody></tr> 
                            </table>   
                        ';
   
    
    $posti = '';
    foreach ($settori as $row)
        $posti .= '<option value="'. $row['id'] .'">'. $row['settore'] .'</option>';



    $fatturazione = '<dl>
    <dt>Nome:</dt><dd>'                                                                         . $anagraphicResult['nome']            . '</dd>
    <dt>Cognome:</dt><dd>'                                                                      . $anagraphicResult['cognome']         . '</dd>
    <dt>Data di nascita:</dt><dd>'                                                              . $anagraphicResult['data_di_nascita'] . '</dd>
    <dt>Indirizzo:</dt><dd>'                                                                    . $anagraphicResult['indirizzo'].", ".$anagraphicResult['cap'].", ".$anagraphicResult['citta']       . '</li>
    <dt lang="en">Email:</dt><dd>'                                                              . $anagraphicResult['email']           . '</dd>
    <dt><abbr title="Telefono">Tel.</abbr>/<abbr title="Cellulare">Cell.</abbr>:</dt><dd>'      . $anagraphicResult['telefono']        . '</dd>
    </dl>';    
    

    #######################
    ###  Render pagina  ###
    #######################

    $breadcrumb = '<li><a href="biglietteria.php">Biglietteria</a></li><li lang="en" aria-current="page">Checkout</li>';

    $page  = Utils::template_replace("%TITLE%", "Checkout | A.C. Torre Archimede", "../html/head.html");
    $page .= Utils::template_replace("%PAGENAME%", $breadcrumb, "../html/navbar.html");

    require_once("../php/navbar.php");

    $page .= file_get_contents("../html/checkout.html");

    $page  = str_replace("%DATI_PARTITA%", $datipartita, $page);
    $page  = str_replace("%TABELLA_POSTI%", $tabellaposti, $page);
    $page  = str_replace("%POSTI%", $posti, $page);

    $page  = str_replace("%DATI_FATTURAZIONE%", $fatturazione, $page);

    $page .= file_get_contents("../html/footer.html");

    $page  = str_replace('%ID%', "checkout-content", $page);
    $page = Errors::handleErrorsAndMessages($page);
    $page  = Utils::removePlaceholders($page);


    $_SESSION['purchasingMatch']['id'] = $_GET['id'];

    echo $page;


?>
