<section class="board">
    <div>

        <?php

//        $initGame = [
//            'grid' => [
//                ['t','l','s','d','k','s','l','t'],
//                ['b','b','b','b','b','b','b','b',],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['B','B','B','B','B','B','B','B'],
//                ['T','L','S','D','K','S','L','T']
//            ],
//            'white' => true,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true]
//            ]
//        ];

        /* Schach Testgrid*/
//        $initGame = [
//            'grid' => [
//                ['','','','','','k','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['d','','T','','','','',''],
//                ['','','','','','S','',''],
//                ['','','','','L','B','',''],
//                ['','','','L','K','B','','']
//            ],
//            'white' => false,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true]
//            ]
//        ];

        /* Schach Matt Testgrid*/
//        $initGame = [
//            'grid' => [
//                ['','','','','k','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['t','','','','','','',''],
//                ['','','','B','B','B','',''],
//                ['','','','','K','','','T']
//            ],
//            'white' => false,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true]
//            ]
//        ];

        /* Anzahl offCheckMoves Testgrid */
//                $initGame = [
//                    'grid' => [
//                        ['','','','','','','',''],
//                        ['','','','','','','',''],
//                        ['','','','','','','',''],
//                        ['','','','','','K','',''],
//                        ['','','','','','','',''],
//                        ['T','','s','','','','',''],
//                        ['','','','','','','',''],
//                        ['','','k','','','','','']
//                    ],
//                    'white' => true,
//                    'check' => [false, false],
//                    'rochadeFirstMoves' => [
//                        [true,true,true],
//                        [true,true,true]
//                    ]
//                ];

        /* Rochade Testgrid */
        $initGame = [
            'grid' => [
                ['t','','','k','','','','t'],
                ['','','','','','','',''],
                ['','s','','','','','',''],
                ['','','','','','','S',''],
                ['','','','','','','',''],
                ['','','','','','','','l'],
                ['','','','','','','',''],
                ['T','','','','K','','','T']
            ],
            'white' => true,
            'check' => [false, false],
            'rochadeFirstMoves' => [
                '0' => [
                    '0' => true,
                    'king' => true,
                    '7' => true],
                '7' => [
                    '0' => true,
                    'king' => true,
                    '7' => true]
            ]
        ];

        $UnicodePieces = [
            'K' => '&#x2654;','D' => '&#x2655;','T' => '&#x2656;','L' => '&#x2657;','S' => '&#x2658;','B' => '&#x2659;',
            'k' => '&#x265A;','d' => '&#x265B;', 't' => '&#x265C;', 'l' => '&#x265D;', 's' => '&#x265E;', 'b' => '&#x265F;'
        ];

        $yAxis = [
            '1' => 7,
            '2' => 6,
            '3' => 5,
            '4' => 4,
            '5' => 3,
            '6' => 2,
            '7' => 1,
            '8' => 0
        ];

        $xAxis = [
            'a' => 0,
            'b' => 1,
            'c' => 2,
            'd' => 3,
            'e' => 4,
            'f' => 5,
            'g' => 6,
            'h' => 7
        ];

        $vectors = [
            'k' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            'd' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            't' => [[-1,0],[0,1],[1,0],[0,-1]],
            'l' => [[-1,-1],[-1,1],[1,-1],[1,1]],
            's' => [[-2,-1],[-2,1],[-1,2],[1,2],[2,1],[2,-1],[1,-2],[-1,-2]],
            'b' => [[-1,0]]
        ];

        if(file_exists('grid.txt')) {
            $file = file_get_contents('grid.txt', true);
            $game = json_decode($file, true);
            $grid = $game['grid'];
            $white = $game['white'];
            $check = $game['check'];
            $rochadeFirstMoves = $game['rochadeFirstMoves'];
        } else {
            file_put_contents('grid.txt', json_encode($initGame, JSON_PRETTY_PRINT));
            $grid = $initGame['grid'];
            $white = $initGame['white'];
            $check = $initGame['check'];
            $rochadeFirstMoves = $initGame['rochadeFirstMoves'];
        }

        if($initGame['white']){
            $message = "⚪ Weiss beginnt";
        } else {
            $message = "⚫ initGame['white'] = false";
        }


        function getPossibleMoves($y,$x, $grid, $white, $vectors, $allVectors, $check, $menace = false) {
            $piece = $grid[$y][$x];
            $grid[$y][$x] = '';
            $possibleMoves = [];
//            Todo schön wär, wenn könig auf Turm zieht rochade funktion nutzen? und als possible moves aufnehmen?

            foreach ($vectors as $vector) {
                if ($piece === 'b') {
                    $vector[0] *= -1;
                    $vector[1] *= -1;
                }
                $yToTest = $y + $vector[0];
                $xToTest = $x + $vector[1];
                while ($yToTest >= 0 && $yToTest <= 7 && $xToTest >= 0 && $xToTest <= 7) {
                    $fieldToTest = $grid[$yToTest][$xToTest];
                    if ($fieldToTest === '' || ($white && ctype_lower($fieldToTest) && $piece !== 'B') || (!$white && ctype_upper($fieldToTest) && $piece !== 'b')) {
                        //in fieldUnderAttack setzen wir absichtlich menace auf true, weil:
                        //wir in getEnemyMoves den König aus der Suche hier ausschließen und keine Endlosschleife erzeugen wollen.
                        if ($menace === false && strtolower($piece) === 'k') {
                            if (!fieldUnderAttack($yToTest, $xToTest, $grid, $white, $allVectors, $check)) {
                                $possibleMoves[] = [$yToTest, $xToTest];
                            }
                        //und menace true - weil wir für die offCheckMoves possibleMoves nochmal brauchen und hier wieder nicht in die Endloschleife wollen.
                        } elseif ($menace === false && ((!$white && $check[0] === true) || ($white && $check[1] === true))) {
//                      ToDo: warum hier white flippen?
                            $possibleMoves = offCheck($grid, !$white, $allVectors, $check);
                        } else {
                            $possibleMoves[] = [$yToTest, $xToTest];
                        }
                    }
                    if (strtolower($piece) === 'b') {
                        if ($xToTest + 1 <= 7 && ((ctype_lower($grid[$yToTest][$xToTest + 1]) && ctype_upper($piece)) || (ctype_lower($piece) && ctype_upper($grid[$yToTest][$xToTest + 1])))) {
                            $possibleMoves[] = [$yToTest, $xToTest + 1];
                        }
                        if ($xToTest - 1 >= 0 && ((ctype_lower($grid[$yToTest][$xToTest - 1]) && ctype_upper($piece)) || (ctype_upper($grid[$yToTest][$xToTest - 1]) && ctype_lower($piece)))) {
                            $possibleMoves[] = [$yToTest, $xToTest - 1];
                        }
                    }
                    // kein überspringen von Figuren: z.b. Dame
                    if ($fieldToTest !== '') {
                        break;
                    }
                    // Bauer Spielbeginn
                    if (strtolower($piece) === 'b') {
                        if ($y === 6 && $piece === 'B') {
                            $possibleMoves[] = [$yToTest - 1, $xToTest];
                        }
                        if ($y === 1 && $piece === 'b') {
                            $possibleMoves[] = [$yToTest + 1, $xToTest];
                        }
                    }
                    // Schleifen Stop für K S B
                    if (strtolower($piece) === 'k' || strtolower($piece) === 's' || strtolower($piece) === 'b') {
                        break;
                    }
                    $yToTest += $vector[0];
                    $xToTest += $vector[1];
                }
            }
            return $possibleMoves;
        }

        function coordinateInArray($y, $x, $coordinates) {
            foreach ($coordinates as $coordinate) {
                if ($coordinate[0] === $y && $coordinate[1] === $x) {
                    return true;
                }
            }
            return false;
        }

        function fieldUnderAttack($y, $x, $grid, $white, $vectors, $check) {
            $fieldUnderAttack = false;
            for($i = 0; $i < count($grid); $i++) {
                for($j = 0; $j < count($grid[$i]); $j++) {
                    if($grid[$i][$j] !== '') {
                        if( ($white && ctype_lower($grid[$i][$j])) || (!$white && ctype_upper($grid[$i][$j])) ) {
                            //get enemy moves - menace true, weil wir den König aus der Suche ausschließen wollen daher setzen wir hier absichtlich menace auf true
                            //und menace true - weil wir für die offCheckMoves possiblemoves nochmal brauchen.
                            $possibleMovesEnemy = getPossibleMoves($i,$j, $grid, !$white, $vectors[strtolower($grid[$i][$j])], $vectors, $check,true);
                            //3. field under threat? bool
                            $fieldUnderAttack = coordinateInArray($y,$x, $possibleMovesEnemy);
                            if ($fieldUnderAttack) {
                                return true;
                            }
                        }
                    }
                }
            }
            return $fieldUnderAttack;
        }

        function findKing($grid, $white) {
            for($i = 0; $i < count($grid); $i++) {
                for($j=0; $j < count($grid[$i]); $j++) {
                    if ((!$white && $grid[$i][$j] === 'k') || ($white && $grid[$i][$j] === 'K')){
                        return [$i,$j];
                    }
                }
            }
        }

        function inCheck($grid, $white, $vectors, $check) {
            $inCheck = false;
            $king = findKing($grid, $white);
            if( (!$white && $grid[$king[0]][$king[1]] === 'k') || ($white && $grid[$king[0]][$king[1]] === 'K') ) {
                $inCheck = fieldUnderAttack($king[0], $king[1], $grid, $white, $vectors, $check);
            }
            return $inCheck;
        }

        function offCheck($grid, $white, $vectors, $check) {
            $offCheckMoves = [];
            for($i = 0; $i < count($grid); $i++) {
                for($j=0; $j < count($grid[$i]); $j++) {
                    if ( ($grid[$i][$j] !== '') && (strtolower($grid[$i][$j]) !== 'k') ){
                        if( (!$white && ctype_upper($grid[$i][$j])) || ($white && ctype_lower($grid[$i][$j])) ) {
                            $possibleMovesCompanions = getPossibleMoves($i,$j, $grid, !$white, $vectors[strtolower($grid[$i][$j])], $vectors, $check,true);
                            foreach ($possibleMovesCompanions as $moveCompanions){
                                $simulationGrid = $grid;
                                $simulationGrid[$moveCompanions[0]][$moveCompanions[1]] = $grid[$i][$j];
                                if(!inCheck($simulationGrid, !$white, $vectors, $check)){
                                    $offCheckMoves[] = $moveCompanions;
                                }
                            }
                        }
                    }
                }
            }
            return $offCheckMoves;
        }

        function pawnToQueen($yNew) {
            $pawnToQueen = false;
            if($yNew === 0 || $yNew === 7){
                $pawnToQueen = true;
            }
            return $pawnToQueen;
        }

        function rochadeFirstMoveTrigger($rochadeFirstMoves, $grid){
            if($grid[0][0] === ''){
                $rochadeFirstMoves[0][0] = false;
            }
            if($grid[0][4] === ''){
                $rochadeFirstMoves[0]['king'] = false;
            }
            if($grid[0][7] === ''){
                $rochadeFirstMoves[0][2] = false;
            }
            if($grid[7][0] === ''){
                $rochadeFirstMoves[7][0] = false;
            }
            if($grid[7][4] === ''){
                $rochadeFirstMoves[7]['king'] = false;
            }
            if($grid[7][7] === ''){
                $rochadeFirstMoves[7][2] = false;
            }
            return $rochadeFirstMoves;
        }

        // Rochade Felder X-Achse des Königs
        function rochadeKingXValues($xNew, $grid, $white){
            $kingPos = findKing($grid, $white);
            if($xNew === 0){
                $rochadeKingXValues = [$kingPos[1]-1,$kingPos[1]-2];
            } else {
                $rochadeKingXValues = [$kingPos[1]+1,$kingPos[1]+2];
            }
            return $rochadeKingXValues;
        }

        // Alle Rochade Felder frei?
        function rochadeFieldsEmpty($yNew, $xNew, $grid){
            $rochadeFieldsEmpty = true;
            if($xNew === 0){
                $rochadeCoordinates = [[$yNew,1],[$yNew,2],[$yNew,3]];
            } else {
                $rochadeCoordinates = [[$yNew,5],[$yNew,6]];
            }
            foreach ($rochadeCoordinates as $rochadeCoordinate) {
                if ($grid[$rochadeCoordinate[0]][$rochadeCoordinate[1]] !== '' ) {
                    $rochadeFieldsEmpty = false;
                    break;
                }
            }
            return $rochadeFieldsEmpty;
        }

        function rochade($piece, $yNew, $xNew, $grid, $rochadeFirstMoves, $white, $vectors, $check) {
            $rochade = [
                'xCoordinates' => [],
                'message' => ''
            ];
            $rochadeKingXValuesClean = true;
            // welcher König && auf eigenen Turm && li/re?
            if((($piece === 'k' && $yNew === 0) || ($piece === 'K' && $yNew === 7)) && ($xNew === 0 || $xNew === 7)){
                // erster Zug König und Turm?
                if($rochadeFirstMoves[$yNew]['king'] && $rochadeFirstMoves[$yNew][$xNew]){
                    // Rochade Felder besetzt?
                    $rochadeFieldsEmpty = rochadeFieldsEmpty($yNew, $xNew, $grid);
                    if($rochadeFieldsEmpty){
                        // Zugfelder des Königs
                        $rochadeKingXValues = rochadeKingXValues($xNew, $grid, $white);
                        foreach ($rochadeKingXValues as $kingMove){
                            if(fieldUnderAttack($yNew, $kingMove, $grid, $white, $vectors, $check)){
                                $rochadeKingXValuesClean = false;
                                break;
                            }
                        }
                        if($rochadeKingXValuesClean){
                            $rochade['xCoordinates'] = $rochadeKingXValues;
                            $rochade['message'] = 'Rochade gezogen';
                        } else {
                            $rochade['message'] = 'Rochade ungültig, Felder des Königs bedroht';
                        }
                    } else {
                        $rochade['message'] = 'Rochade ungültig, Rochade besetzt';
                    }
                } else {
                    $rochade['message'] = 'Rochade ungültig, eine Figur wurde schon bewegt';
                }
            }
            return $rochade;
        }

        $game = [];
        function moveFinisher($yNew, $xNew, $grid, $white, $vectors, $message, $rochadeFirstMoves, $game, $check){
            $white = !$white;
//            ToDo incheck ruft hier nochmal getpossiblemoves auf, Schach könnte hier jetzt durch $check abgefragt werden.
            if (inCheck($grid, $white, $vectors, $check)) {

                $king = findKing($grid, $white);
                $possibleMovesKing = getPossibleMoves($king[0], $king[1], $grid, $white, $vectors[strtolower($grid[$king[0]][$king[1]])], $vectors, $check);
                $menace = fieldUnderAttack($yNew, $xNew, $grid, !$white, $vectors, $check);
                $offCheckMoves = offCheck($grid, !$white, $vectors, $check);
                if ($white) {
                    $check[1] = true;
                } else {
                    $check[0] = true;
                }
                if ($menace === false && count($offCheckMoves) === 0 && count($possibleMovesKing) === 0) {
                    $message = '!!! SCHACH MATT !!!';
                } else {
                    $message .= '<br> !!! SCHACH !!!';
                }
            }

            $rochadeFirstMoves = rochadeFirstMoveTrigger($rochadeFirstMoves, $grid);

            $game['grid'] = $grid;
            $game['white'] = $white;
            $game['check'] = $check;
            $game['rochadeFirstMoves'] = $rochadeFirstMoves;
            file_put_contents('grid.txt', json_encode($game, JSON_PRETTY_PRINT));
            return $message;
        }

        if(isset($_POST['from'])&&($_POST['to'])) {
            $inputFrom = str_split($_POST['from']);
            //A2 = A-x-col / 2-y-row
            $x = $xAxis[strtolower($inputFrom[0])];
            $y = $yAxis[$inputFrom[1]];

            $inputTo = str_split($_POST['to']);
            $xNew = $xAxis[strtolower($inputTo[0])];
            $yNew = $yAxis[$inputTo[1]];

            $piece = $grid[$y][$x];
            if($piece !== '') {
                if( ($white && ctype_lower($piece)) || (!$white && ctype_upper($piece)) ) {
                    $message = "Achtung" . "<br>" . "nicht dein Zug!";
                } else {
                    $possibleMoves = getPossibleMoves($y, $x, $grid, $white, $vectors[strtolower($piece)], $vectors, $check);
                    if (coordinateInArray($yNew, $xNew, $possibleMoves)) {

                        $grid[$y][$x] = '';

                        if(pawnToQueen($yNew) && strtolower($piece) === 'b'){
                            if(ctype_lower($piece)){
                                $grid[$yNew][$xNew] = 'd';
                            } else {
                                $grid[$yNew][$xNew] = 'D';
                            }
                        } else {
                            $grid[$yNew][$xNew] = $piece;
                        }

                        $message = moveFinisher($yNew, $xNew, $grid, $white, $vectors, $message, $rochadeFirstMoves, $game, $check);

//                      ToDo ungültige Rochade gilt als Zug !!!

                    } elseif (strtolower($piece) === 'k' && strtolower($grid[$yNew][$xNew]) === 't' ){
                        if(!inCheck($grid, $white, $vectors, $check)){
                            $message = "";
                            $rochade = rochade($piece, $yNew, $xNew, $grid, $rochadeFirstMoves, $white, $vectors, $check);
                            if(count($rochade['xCoordinates']) !== 0){
                                $grid[$yNew][$rochade['xCoordinates'][1]] = $piece;
                                $grid[$yNew][$rochade['xCoordinates'][0]] = $grid[$yNew][$xNew];
                                $grid[$yNew][4] = '';
                                $grid[$yNew][$xNew] = '';
                            }
                            $message = $rochade['message'] . '<br>' . moveFinisher($yNew, $xNew, $grid, $white, $vectors, $message, $rochadeFirstMoves, $game, $check);
                        } else {
                            $message = "! keine Rochade bei SCHACH.";
                        }
                    } else {
                        $message = "!! Zug ungültig !!";
                    }
                }
            } else {
                $message = "!! Feld leer !!";
            }
            if(ctype_upper($piece)) {
                $message .= "<br>" . "⚫ Schwarz am Zug!";
            } else {
                $message .= "<br>" . "⚪ Weiss am Zug!";
            }
        }
        /* reset */
        if (isset($_POST['reset'])) {
            unlink('grid.txt');
        }

        function showGrid($grid, $pieces) {
            for($i=0; $i <= 7; $i++)
            {
//                echo "<div class='rownum'><strong>" . (8-$i). '</strong> - ' . $i . "</div>";
                echo "<div class='rownum'>" . (8-$i). "</div>";
                echo "<div class='row'>";
                for($j=0;$j<=7;$j++)
                {
                    $total=$i+$j;
                    if($total%2===0)
                    {
                        echo "<div class='white'>";
                    }
                else
                    {
                        echo "<div class='black'>";
                    }
                    if ($grid[$i][$j] !== '') {
                        echo $pieces[$grid[$i][$j]];
                    }
                    echo '</div>';
                }
                echo "</div>";
            }
        }

        showGrid($grid, $UnicodePieces);

        ?>
        <div class="row">
            <div class="white">A</div>
            <div class="white">B</div>
            <div class="white">C</div>
            <div class="white">D</div>
            <div class="white">E</div>
            <div class="white">F</div>
            <div class="white">G</div>
            <div class="white">H</div>
        </div>
<!--        <div class="row">-->
<!--            <div class="white">0</div>-->
<!--            <div class="white">1</div>-->
<!--            <div class="white">2</div>-->
<!--            <div class="white">3</div>-->
<!--            <div class="white">4</div>-->
<!--            <div class="white">5</div>-->
<!--            <div class="white">6</div>-->
<!--            <div class="white">7</div>-->
<!--        </div>-->
    </div>
</section>
