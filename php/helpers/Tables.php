<?php

class Tables {
    static function buildHomeTable($matches) {

        if(isset($matches) && $matches != []) {

            $t_body = '';

            foreach($matches as $match) {
                $t_body .= "<tr>\n";

                $t_body .= '<td data-label="Giornata">' . $match['Giornata']                    . "</td>\n";
                $t_body .= '<td data-label="Data">' . substr($match['Data'], 0, 10)             . "</td>\n";
                $t_body .= '<td data-label="Ora">' . substr($match['Data'], 10, 6)              . "</td>\n";
                $t_body .= '<td data-label="Squadra di casa">' . $match['NomeSquadra']          . "</td>\n";
                $t_body .= '<td data-label="Squadra ospite">' . $match['NomeSquadraAvversaria'] . "</td>\n";
                $t_body .= '<td data-label="Stadio">' . $match['NomeStadio']                    . "</td>\n";
                
                $t_body .= "</tr>\n";
            }

            $table = file_get_contents('../html/tables/struttura.html');

            $table = str_replace('%BODY%', $t_body, $table);
            unset($t_body);

            $table = str_replace('%SUMMARY%', 'Anteprima delle prossime tre partite da giocare', $table);
            $table = str_replace('%CAPTION%', 'Prossime partite', $table);
            $table = str_replace('%ID%', 'id="nextMatchesTable"', $table);
            $table = str_replace('%HEAD%', file_get_contents('../html/tables/home_header.html'), $table);

        } else
            $table = '<p class="info">Nessun evento attualmente in calendario<p>';

        return $table;

    }


    static function buildCalendarTable($matches) {

        if(isset($matches) && $matches != []) {// ? or count($rows) >0

            $t_body = '';

            foreach($matches as $match) {
                $t_body .= '<tr>';
        

                $t_body .= '<td data-label="Giornata">'  . $match['giornata']           . '</td>';
                $t_body .= '<td data-label="Data">' . substr($match['data'], 0, 10)     . '</td>';
                $t_body .= '<td data-label="Ora">' . substr($match['data'], 10, 6)      . '</td>';
                $t_body .= '<td data-label="Squadra di casa">' . $match['NomeSquadra']  . '</td>';
                $t_body .= '<td data-label="Ospiti">' . $match['NomeSquadraAvversaria'] . '</td>';
                $t_body .= '<td data-label="Stadio">' . $match['NomeStadio']            . '</td>';

                if($match['risultato1'] != null)
                    $t_body .= '<td data-label="Risultato">' . $match['risultato1'] ." - ".$match['risultato2']. '</td>';
                else $t_body .= '<td data-label="Risultato"> TBD </td>';

                $t_body .= '</tr>';
            }

            $table = file_get_contents('../html/tables/struttura.html');

            $table = str_replace('%BODY%', $t_body, $table);
            unset($t_body);

            $table = str_replace('%SUMMARY%', 'Tabella che descrive il calendario delle partite della squadra Torre Archimede', $table);
            $table = str_replace('%CAPTION%', 'Calendario delle partite', $table);
            $table = str_replace('%ID%', 'id="calendarioPartite"', $table);
            $table = str_replace('%HEAD%', file_get_contents('../html/tables/match_header.html'), $table);
            
        } else
            $table = '<p class="info">Nessun evento attualmente in calendario<p>';

        return $table;

    }

    static function buildBiglietteriaTable($matches) {

        if(isset($matches) && $matches != []) {// ? or count($rows) >0

            $t_body = "";

            foreach($matches as $match) {
                $t_body .= '<tr>';
        

                $t_body .= '<td data-label="Giornata">' . $match['giornata']            . '</td>';
                $t_body .= '<td data-label="Data">' . substr($match['data'], 0, 10)     . '</td>';
                $t_body .= '<td data-label="Ora">' . substr($match['data'], 10, 6)      . '</td>';
                $t_body .= '<td data-label="Squadra di casa">' . $match['NomeSquadra']  . '</td>';
                $t_body .= '<td data-label="Ospiti">' . $match['NomeSquadraAvversaria'] . '</td>';
                $t_body .= '<td data-label="Stadio">' . $match['NomeStadio']            . '</td>';
                $t_body .= '<td data-label="Prezzi"> A partire da ' . min($match['costo1'], $match['costo2'],$match['costo3'].$match['costo4']). '€</td>';

                if($match['NomeStadio'] == 'Torre Archimede') // FIXME: #3 switch to stadium name ni production
                    $t_body .= '<td data-label="Link acquisto"><a href="checkout.php?id='.$match['id'].'">Acquista</a></td>' ;//match_id
                else

                    $t_body .= '<td data-label="Link acquisto"><a href="">Acquista esternamente</a> </td>';//TODO: #4 aggiunta indicatore di risorsa esterna

                $t_body .= '</tr>';
            }

            $table = file_get_contents('../html/tables/struttura.html');

            $table = str_replace('%BODY%', $t_body, $table);
            unset($t_body);

            $table = str_replace('%SUMMARY%', 'Tabella che descrive la biglietteria delle partite', $table);
            $table = str_replace('%CAPTION%', 'Biglietteria delle partite', $table);
            $table = str_replace('%ID%', 'id="biglietteriaPartite"', $table);
            $table = str_replace('%HEAD%', file_get_contents('../html/tables/biglietto_header.html'), $table);
            
        } else
            $table = '<p class="info">Nessun evento acquistabile, avrai più fortuna la prossima volta.<p>';

        return $table;

    }

    static function buildAdminDashboardTable($matches) {

        if(isset($matches) && $matches != []) {// ? or count($rows) >0

            $t_body = '';

            foreach($matches as $match) {
                $t_body .= '<tr>';
                $t_body .= '<td data-label="Risultato">'            . $match['giornata']              . '</td>';
                $t_body .= '<td data-label="Data">'                 . substr($match['data'], 0, 10)   . '</td>';
                $t_body .= '<td data-label="Ora">'                  . substr($match['data'], 10, 6)   . '</td>';
                $t_body .= '<td data-label="Squadra di casa">'      . $match['NomeSquadra']           . '</td>';
                $t_body .= '<td data-label="Ospiti">'               . $match['NomeSquadraAvversaria'] . '</td>';
                $t_body .= '<td data-label="Stadio">'               . $match['NomeStadio']            . '</td>';
                $t_body .= '<td data-label="Prezzo Platea">'        . $match['costo1']                . '€</td>';
                $t_body .= '<td data-label="Prezzo Tribuna">'       . $match['costo2']                . '€</td>';
                $t_body .= '<td data-label="Prezzo Spalti">'        . $match['costo3']                . '€</td>';
                $t_body .= '<td data-label="Prezzo Curva">'         . $match['costo4']                . '€</td>';

                if($match['risultato1'] != null)
                    $t_body .= '<td data-label="Risultato">' . $match['risultato1'] ." - ".$match['risultato2']. '</td>';
                else $t_body .= '<td data-label="Risultato"> TBD </td>';

                $t_body .= '<td><a href="edit_match.php?id='        . $match['id']      . '">Modifica</a> - ';
                $t_body .= '<a href="actions/del_match.php?id=' . $match['id']      . '">Elimina</a> </td>';

                $t_body .= '</tr>';
            }

            $table = file_get_contents('../html/tables/struttura.html');

            $table = str_replace('%BODY%', $t_body, $table);
            unset($t_body);

            $table = str_replace('%SUMMARY%', 'Tabella che mostra le partite in archivio e permette di modificarle ed eliminarle', $table);
            $table = str_replace('%CAPTION%', 'Partite in archivio da modificare o cancellare', $table);
            $table = str_replace('%ID%', 'id="matchesTable"', $table);
            $table = str_replace('%HEAD%', file_get_contents('../html/tables/match_admin_header.html'), $table);
            
        } else
            $table = '<p class="info">Nessun evento inserito<p>';

        return $table;

    }


    static function buildDashboardTable($tickets, $summary, $caption, $tableId) {

        if(isset($tickets) && $tickets != []) {// ? or count($rows) >0

            $t_body = '';

            foreach($tickets as $ticket) {
                $t_body .= '<tr>';
                

                $t_body .= '<td data-label="Risultato">' . $ticket['giornata']               . '</td>';
                $t_body .= '<td data-label="Data">' . substr($ticket['data'], 0, 10)         . '</td>';
                $t_body .= '<td data-label="Ora">' . substr($ticket['data'], 10, 6)          . '</td>';
                $t_body .= '<td data-label="Squadra di casa">' . $ticket['NomeSquadra']      . '</td>';
                $t_body .= '<td data-label="Ospiti">' . $ticket['NomeSquadraAvversaria']     . '</td>';
                $t_body .= '<td data-label="Stadio">' . $ticket['NomeStadio']                . '</td>';
                $t_body .= '<td data-label="Prezzi">' . $ticket['costo'.$ticket['id_posto']] . '€</td>';
                $t_body .= '<td data-label="Settore stadio">' . $ticket['NomePosto']         . '</td>';
                if($tableId == "followingTicketsTable")
                    $t_body .= '<td data-label="Rimborso"><a href="actions/cashback_ticket.php?id='. $ticket['idBiglietto'] .'">Chiedi Rimborso</a></td>';

                $t_body .= '</tr>';
            }

            $table = file_get_contents('../html/tables/struttura.html');

            $table = str_replace('%BODY%', $t_body, $table);
            unset($t_body);

            $table = str_replace('%SUMMARY%', $summary, $table);
            $table = str_replace('%CAPTION%', $caption, $table);
            $table = str_replace('%ID%', "id=\"$tableId\"", $table);                
            
            if($tableId == "followingTicketsTable")
                $table = str_replace('%HEAD%', file_get_contents('../html/tables/refundable_ticket.html'), $table);
            else $table = str_replace('%HEAD%', file_get_contents('../html/tables/ticket_header.html'), $table);
            
        } else
            $table = '<p class="info">Nessun biglietto acquistato<p>';

        return $table;
    }
    
}
