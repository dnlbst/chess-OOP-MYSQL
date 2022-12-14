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
//            'turn' => 'x',
//        ];

        $initGame = [
            'grid' => [
                ['','','','b','k','b','',''],
                ['','','','b','b','b','',''],
                ['','','','','','','',''],
                ['','','','','','S','',''],
                ['','','','','','','',''],
                ['','','','','','','',''],
                ['','','','','','','',''],
                ['','','','','','','',''],
            ],
            'turn' => 'x',
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
            $turn = $game['turn'];
        } else {
            file_put_contents('grid.txt', json_encode($initGame, JSON_PRETTY_PRINT));
            $grid = $initGame['grid'];
            $turn = $initGame['turn'];
        }

        /* move validaion */
        function getPossibleMoves($x,$y, $grid, $turn, $vectors, $allVectors, $menace = false) {
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
                    if( $fieldToTest === '' || (ctype_lower($piece) && ctype_upper($fieldToTest) && $piece !== 'b') || (ctype_upper($piece) && ctype_lower($fieldToTest) && $piece !== 'B' )
                    ) {
                        if (strtolower($piece) === 'k' && $menace === false) {
                            if ( (!fieldUnderAttack($xToTest, $yToTest, $grid, $turn, $allVectors)) ) {
                                $possibleMoves[] = [$xToTest, $yToTest];
                            }
                        } else {
                            $possibleMoves[] = [$xToTest, $yToTest];
                        }
                    }

                    if (strtolower($piece) === 'b') {
                        if ($xToTest-1 >= 0 && ((ctype_lower($grid[$yToTest][$xToTest-1]) && ctype_upper($piece)) || (ctype_lower($piece) && ctype_upper($grid[$yToTest][$xToTest-1]))) ){
                            $possibleMoves[] = [$xToTest-1, $yToTest];
                        }
                        if ($xToTest+1 <= 7 && ((ctype_lower($grid[$yToTest][$xToTest+1]) && ctype_upper($piece)) || (ctype_upper($grid[$yToTest][$xToTest+1]) && ctype_lower($piece))) ) {
                            $possibleMoves[] = [$xToTest+1, $yToTest];
                        }
                    }

                    // z.b. Dame: überspringen unterbinden
                    if ($fieldToTest !== '') {
                        break;
                    }

                    // Bauer Spielbeginn
                    if ( strtolower($piece) === 'b' && $grid[$yToTest-1][$xToTest] == '' ) {
                        if ($y == 6) {
                            $possibleMoves[] = [$xToTest, $yToTest-1];
                        }
                        if ($y == 1) {
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

        function fieldUnderAttack($x, $y, $grid, $turn, $vectors) {
            $fieldUnderAttack = false;
            //1. get all enemys
            for($i=0; $i < count($grid); $i++) {
                foreach($grid[$i] as $key => $field) {
                    if($field !== '') {
                        $piece = $field;
                        if( (ctype_lower($turn) && ctype_lower($piece)) || (ctype_upper($turn) && ctype_upper($piece)) ) {
                            //get enemy moves
                            $possibleMovesEnemy = getPossibleMoves($key, $i, $grid, $turn, $vectors[strtolower($piece)], $vectors, true);

                            //3. field under threat? bool
                            $fieldUnderAttack = coordinateInArray($x,$y, $possibleMovesEnemy);
                        }
                    }
                }
            }
            return $fieldUnderAttack;
        }

        // find king funktion schreiben weil ich es jetzt nochmal brauche
        // aus incheck rauskopieren und dort die funktion hier nutzen

        function inCheck($grid, $turn, $vectors, $piece) {
            $inCheck = false;
            for ($i=0; $i < count($grid); $i++) {
                for ($j=0; $j < count($grid[$i]); $j++) {
                    if( (ctype_lower($turn) && $grid[$i][$j] === 'k') || (ctype_upper($turn) && $grid[$i][$j] === 'K') ) {
                      //field underAttack?
                      $inCheck = fieldUnderAttack($j, $i, $grid, $piece, $vectors);
                    }
                }
            }
            return $inCheck;
        }

        // find king funktion schreiben und nutzen um checkmate zu schreiben um index anzahl passible moves zu prfüfen.


        /* input handling */
        $message = "WEISS fängt an";
        if(isset($_POST['from'])&&($_POST['to'])) {
            $inputFrom = str_split($_POST['from']);
            $x = $xAxis[strtolower($inputFrom[0])];
            $y = $yAxis[$inputFrom[1]];

            $inputTo = str_split($_POST['to']);
            $xNew = $xAxis[strtolower($inputTo[0])];
            $yNew = $yAxis[$inputTo[1]];

            $piece = $grid[$y][$x];

            /* moving piece */
            if(!empty($piece)) {
                if( (ctype_lower($turn) && ctype_lower($piece)) || (ctype_upper($turn) && ctype_upper($piece)) ) {
                    $message = "Achtung" . "<br>" . "nicht dein Zug!";
                } else {
                    $possibleMoves = getPossibleMoves($x, $y, $grid, $turn, $vectors[strtolower($piece)], $vectors);
                    if (coordinateInArray($xNew, $yNew, $possibleMoves)) {
                        $grid[$y][$x] = '';
                        $grid[$yNew][$xNew] = $piece;

                        if(ctype_upper($piece)) {
                            $message = "SCHWARZ am Zug!";
                        } else {
                            $message = "WEISS am Zug!";
                        }
                        if (inCheck($grid, $turn, $vectors, $piece)) {
                            if (empty(getPossibleMoves())) {
                                $message .= ' SCHACH MATT!!!!';

                            } else {
                                $message .= ' SCHACH!!!!';

                            }

                        }
                        $game['turn'] = $piece;
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

        /* chess board loop */
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

        /* grid load */
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
