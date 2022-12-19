<section class="board">
    <div>

        <?php

        /* initial grid array */
//        $initGame = [
//            'grid' => [
//                ['t','l','s','d','k','s','l','t'],
//                ['b','b','b','b','b','b','b','b',],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['','','','','','','',''],
//                ['B','B','B','B','B','B','B','B'],
//                ['T','L','S','D','K','S','L','T'],
//            ],
//            'white' => true,
//        ];

        $initGame = [
            'grid' => [
                ['','','','b','k','b','',''],
                ['B','','','b','b','b','',''],
                ['','','','','','','',''],
                ['','','','','','S','',''],
                ['','','','','','','',''],
                ['','','','K','','','s',''],
                ['b','','','','','B','B',''],
                ['','','','','','','',''],
            ],
            'white' => true,
        ];
        /* Unicode Chess Pieces */
        $UnicodePieces = [
            'K' => '&#x2654;','D' => '&#x2655;','T' => '&#x2656;','L' => '&#x2657;','S' => '&#x2658;','B' => '&#x2659;',
            'k' => '&#x265A;','d' => '&#x265B;', 't' => '&#x265C;', 'l' => '&#x265D;', 's' => '&#x265E;', 'b' => '&#x265F;'
        ];
        /* X & Y Axis Translation */
        $xAxis = [
            'a' => 0,
            'b' => 1,
            'c' => 2,
            'd' => 3,
            'e' => 4,
            'f' => 5,
            'g' => 6,
            'h' => 7,
        ];
        $yAxis = [
            '1' => 7,
            '2' => 6,
            '3' => 5,
            '4' => 4,
            '5' => 3,
            '6' => 2,
            '7' => 1,
            '8' => 0,
        ];
        /* valid moves */
        $vectors = [
            'k' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            'd' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            't' => [[-1,0],[0,1],[1,0],[0,-1]],
            'l' => [[-1,-1],[-1,1],[1,-1],[1,1]],
            's' => [[-2,-1],[-2,1],[-1,2],[1,2],[2,1],[2,-1],[1,-2],[-1,-2]],
            'b' => [[0,-1]],
        ];

        /* save file */
        if(file_exists('grid.txt')) {
            $file = file_get_contents('grid.txt', true);
            $game = json_decode($file, true);
            $grid = $game['grid'];
            $white = $game['white'];
        } else {
            file_put_contents('grid.txt', json_encode($initGame, JSON_PRETTY_PRINT));
            $grid = $initGame['grid'];
            $white = $initGame['white'];
        }

        /* move validaion */
        function getPossibleMoves($x,$y, $grid, $white, $vectors, $allVectors, $menace = false) {
            $piece = $grid[$y][$x];
            $possibleMoves = [];
            foreach ($vectors as $vector) {
                if ($piece === 'b') {
                    $vector[0] *= -1;
                    $vector[1] *= -1;
                }
                $xToTest = $x + $vector[0];
                $yToTest = $y + $vector[1];
                while ($yToTest >= 0 && $yToTest <= 7 && $xToTest >= 0 && $xToTest <= 7){
                    $fieldToTest = $grid[$yToTest][$xToTest];
                    if( $fieldToTest === '' || (!$white && ctype_upper($fieldToTest) && $piece !== 'b') || ($white && ctype_lower($fieldToTest) && $piece !== 'B' )
                    ) {
                        if (strtolower($piece) === 'k' && $menace === false) {
                            if ( (!fieldUnderAttack($xToTest, $yToTest, $grid, $white, $allVectors)) ) {
                                $possibleMoves[] = [$xToTest, $yToTest];
                            }
                        } else {
                            $possibleMoves[] = [$xToTest, $yToTest];
                        }
                    }
                    if (strtolower($piece) === 'b') {
                        if ($xToTest+1 <= 7 && ((ctype_lower($grid[$yToTest][$xToTest+1]) && ctype_upper($piece)) || (ctype_lower($piece) && ctype_upper($grid[$yToTest][$xToTest+1]))) ){
                            $possibleMoves[] = [$xToTest+1, $yToTest];
                        }
                        if ($xToTest-1 >= 0 && ((ctype_lower($grid[$yToTest][$xToTest-1]) && ctype_upper($piece)) || (ctype_upper($grid[$yToTest][$xToTest-1]) && ctype_lower($piece))) ) {
                            $possibleMoves[] = [$xToTest-1, $yToTest];
                        }
                    }
                    // z.b. Dame: überspringen unterbinden
                    if ($fieldToTest !== '') {
                        break;
                    }
                    // Bauer Spielbeginn
                    if (strtolower($piece) === 'b') {
                        if ($y === 6 && $piece === 'B') {
                            $possibleMoves[] = [$xToTest, $yToTest-1];
                        }
                        if ($y == 1 && $piece === 'b') {
                            $possibleMoves[] = [$xToTest, $yToTest+1];
                        }
                    }
                    // Schleifen Stop für K S B
                    if (strtolower($piece) === 'k' || strtolower($piece) === 's' || strtolower($piece) === 'b') {
                        break;
                    }
                    $xToTest = $xToTest + $vector[0];
                    $yToTest = $yToTest + $vector[1];
                }
            }
            return $possibleMoves;
        }

        function coordinateInArray($x,$y, $coordinates) {
            foreach ($coordinates as $coordinate) {
                if ($coordinate[0] == $x && $coordinate[1] == $y) {
                    return true;
                }
            }
            return false;
        }

        // wird aktuell für 2 usecases genutzt:
        // 1. darf der König auf ein Feld ziehen 2. steht der König nach einem Zug unter Schach
        // 1. weiss true könig prüft alle Felder von schwarz 2. weiß läuft und prüft ob von der neuen pos nun der schwarze König unter schach steht.
        // 1. weiss prüft auf schwarz 2. weiss prüft auf schwarz
        function fieldUnderAttack($x, $y, $grid, $white, $vectors) {
            $fieldUnderAttack = false;
            //1. get all enemys
            for($i = 0; $i < count($grid); $i++) {
                for($j = 0; $j < count($grid[$i]); $j++) {
                    if($grid[$i][$j] !== '') {
                        if( ($white && ctype_lower($grid[$i][$j])) || (!$white && ctype_upper($grid[$i][$j])) ) {
                            //get enemy moves
                            $possibleMovesEnemy = getPossibleMoves($j, $i, $grid, $white, $vectors[strtolower($grid[$i][$j])], $vectors, true);
                            //3. field under threat? bool
                            $fieldUnderAttack = coordinateInArray($x, $y, $possibleMovesEnemy);
                            var_dump($fieldUnderAttack);
                            if ($fieldUnderAttack) {
                                return true;
                            }
                        }
                    }
                }
            }
            return $fieldUnderAttack;
        }

        function findKing($grid) {
            $findKing = [];
            for($i = 0; $i < count($grid); $i++) {
                for($j=0; $j < count($grid[$i]); $j++) {
                    if (strtolower($grid[$i][$j]) === 'k'){
                        $findKing[] = [$i,$j];
                    }
                }
            }
            return $findKing;
        }



        function inCheck($grid, $white, $vectors, $piece) {
            $inCheck = false;
            $findKing[] = findKing($grid);
            foreach ($findKing as $kings){
                foreach ($kings as $king){
                    if( ($white && $grid[$king[0]][$king[1]] === 'k') || (!$white && $grid[$king[0]][$king[1]] === 'K') ) {
                        //field underAttack?
                        var_dump($king[0]);
                        var_dump($king[1]);
                        $inCheck = fieldUnderAttack($king[0], $king[1], $grid, $piece, $vectors);
                    }
                }
            }
            return $inCheck;
        }

        function pawnToQueen($yNew) {
            $pawnToQueen = false;
            if($yNew === 0 || $yNew === 7){
                $pawnToQueen = true;
            }
            return $pawnToQueen;
        }

        // moving pieces
        $message = "⚪ Weiss fängt an";
        if(isset($_POST['from'])&&($_POST['to'])) {
            $inputFrom = str_split($_POST['from']);
            $x = $xAxis[strtolower($inputFrom[0])];
            $y = $yAxis[$inputFrom[1]];

            $inputTo = str_split($_POST['to']);
            $xNew = $xAxis[strtolower($inputTo[0])];
            $yNew = $yAxis[$inputTo[1]];

            $piece = $grid[$y][$x];
            if(!empty($piece)) {
                if( ($white && ctype_lower($piece)) || (!$white && ctype_upper($piece)) ) {
                    $message = "Achtung" . "<br>" . "nicht dein Zug!";
                } else {
                    $possibleMoves = getPossibleMoves($x, $y, $grid, $white, $vectors[strtolower($piece)], $vectors);
                    if (coordinateInArray($xNew, $yNew, $possibleMoves)) {
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

                        if(ctype_upper($piece)) {
                            $message = "⚫ Schwarz am Zug!";
                        } else {
                            $message = "⚪ Weiss am Zug!";
                        }
                        if (inCheck($grid, $white, $vectors, $piece)) {
                            $findKing = findKing($grid);
                            $possibleMoves = getPossibleMoves($findKing[1], $findKing[0], $grid, $piece, $vectors[$grid[$findKing[0]][$findKing[1]]], $vectors);
                            $menace = fieldUnderAttack($xNew, $yNew, $grid, $white, $vectors);
                            if(count($possibleMoves) === 0 && $menace == false ){
                                $message = '!!! SCHACH MATT !!!';
                            } else {
                                $message .= '<br> !!! SCHACH !!!';
                            }
                        }

                        if($white){
                            $game['white'] = false;
                        } else {
                            $game['white'] = true;
                        }
                        $game['grid'] = $grid;
                        file_put_contents('grid.txt', json_encode($game, JSON_PRETTY_PRINT));
                    } else {
                        $message = "!! Zug ungültig !!";
                    }
                }
            } else {
                $message = "!! Feld leer !!";
            }
        }

        /* reset */
        if (isset($_POST['reset'])) {
            unlink('grid.txt');
        }

        function showGrid($grid, $pieces) {
            for($i=0; $i <= 7; $i++)
            {
                echo "<div class='rownum'><strong>" . (8-$i). '</strong> - ' . $i . "</div>";
                echo "<div class='row'>";
                for($j=0;$j<=7;$j++)
                {
                    $total=$i+$j;
                    if($total%2==0)
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
            <div class="white">0</div>
            <div class="white">1</div>
            <div class="white">2</div>
            <div class="white">3</div>
            <div class="white">4</div>
            <div class="white">5</div>
            <div class="white">6</div>
            <div class="white">7</div>
        </div>
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
    </div>
</section>
