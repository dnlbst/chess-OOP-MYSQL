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
//                ['T','L','S','D','K','S','L','T'],
//            ],
//            'white' => true,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true],
//            ],
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
//                ['','','','L','K','B','',''],
//            ],
//            'white' => false,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true],
//            ],
//
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
//                ['','','','','K','','','T'],
//            ],
//            'white' => false,
//            'check' => [false, false],
//            'rochadeFirstMoves' => [
//                [true,true,true],
//                [true,true,true],
//            ],
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
//                        ['','','k','','','','',''],
//                    ],
//                    'white' => true,
//                    'check' => [false, false],
//                    'rochadeFirstMoves' => [
//                        [true,true,true],
//                        [true,true,true],
//                    ],
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
                ['T','','','','K','','','T'],
            ],
            'white' => true,
            'check' => [false, false],
            'rochadeFirstMoves' => [
                [true,true,true],
                [true,true,true],
            ],
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
            '8' => 0,
        ];

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

        $vectors = [
            'k' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            'd' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            't' => [[-1,0],[0,1],[1,0],[0,-1]],
            'l' => [[-1,-1],[-1,1],[1,-1],[1,1]],
            's' => [[-2,-1],[-2,1],[-1,2],[1,2],[2,1],[2,-1],[1,-2],[-1,-2]],
            'b' => [[-1,0]],
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

        function getPossibleMoves($y,$x, $grid, $white, $vectors, $allVectors, $menace = false) {
            $piece = $grid[$y][$x];
            $possibleMoves = [];
            //zu testende Figur aus Spielfeld nehmen
            $grid[$y][$x] = '';
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
                        if ($menace === false && strtolower($piece) === 'k') {
                            if (!fieldUnderAttack($yToTest, $xToTest, $grid, $white, $allVectors)) {
                                $possibleMoves[] = [$yToTest, $xToTest];
                            }
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

        function fieldUnderAttack($y, $x, $grid, $white, $vectors) {
            $fieldUnderAttack = false;
            for($i = 0; $i < count($grid); $i++) {
                for($j = 0; $j < count($grid[$i]); $j++) {
                    if($grid[$i][$j] !== '') {
                        if( ($white && ctype_lower($grid[$i][$j])) || (!$white && ctype_upper($grid[$i][$j])) ) {
                            //get enemy moves
                            $possibleMovesEnemy = getPossibleMoves($i,$j, $grid, !$white, $vectors[strtolower($grid[$i][$j])], $vectors, true);
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

        function inCheck($grid, $white, $vectors) {
            $inCheck = false;
            $king = findKing($grid, $white);
            if( (!$white && $grid[$king[0]][$king[1]] === 'k') || ($white && $grid[$king[0]][$king[1]] === 'K') ) {
                $inCheck = fieldUnderAttack($king[0], $king[1], $grid, $white, $vectors);
            }
            return $inCheck;
        }

        function offCheck($grid, $white, $vectors) {
            $offCheckMoves = [];
            for($i = 0; $i < count($grid); $i++) {
                for($j=0; $j < count($grid[$i]); $j++) {
                    if ( ($grid[$i][$j] !== '') && (strtolower($grid[$i][$j]) !== 'k') ){
                        if( (!$white && ctype_upper($grid[$i][$j])) || ($white && ctype_lower($grid[$i][$j])) ) {
                            $possibleMovesCompanions = getPossibleMoves($i,$j, $grid, !$white, $vectors[strtolower($grid[$i][$j])], $vectors, true);
                            foreach ($possibleMovesCompanions as $moveCompanions){
                                $simulationGrid = $grid;
                                $simulationGrid[$moveCompanions[0]][$moveCompanions[1]] = $grid[$i][$j];
                                if(!inCheck($simulationGrid, !$white, $vectors)){
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
                $rochadeFirstMoves[0][1] = false;
            }
            if($grid[0][7] === ''){
                $rochadeFirstMoves[0][2] = false;
            }
            if($grid[7][0] === ''){
                $rochadeFirstMoves[1][0] = false;
            }
            if($grid[7][4] === ''){
                $rochadeFirstMoves[1][1] = false;
            }
            if($grid[7][7] === ''){
                $rochadeFirstMoves[2][2] = false;
            }
            return $rochadeFirstMoves;
        }

        if($initGame['white']){
            $message = "⚪ Weiss fängt an";
        } else {
            $message = "⚫ initGame['white'] = false";
        }

        $game = [];
        function moveFinisher($white, $grid, $vectors, $yNew, $xNew, $message, $check, $rochadeFirstMoves, $game){
            $white = !$white;
            if (inCheck($grid, $white, $vectors)) {
                $king = findKing($grid, $white);
                $possibleMovesKing = getPossibleMoves($king[0], $king[1], $grid, $white, $vectors[strtolower($grid[$king[0]][$king[1]])], $vectors);
                $menace = fieldUnderAttack($yNew, $xNew, $grid, !$white, $vectors);
                $offCheckMoves = offCheck($grid, !$white, $vectors);
                if ($menace === false && count($offCheckMoves) === 0 && count($possibleMovesKing) === 0) {
                    $message = '!!! SCHACH MATT !!!';
                } else {
                    if ($white) {
                        $check[1] = true;
                    } else {
                        $check[0] = true;
                    }
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
                    $possibleMoves = getPossibleMoves($y, $x, $grid, $white, $vectors[strtolower($piece)], $vectors);
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

                        if(ctype_upper($piece)) {
                            $message = "⚫ Schwarz am Zug!";
                        } else {
                            $message = "⚪ Weiss am Zug!";
                        }

                        $message = moveFinisher($white, $grid, $vectors, $yNew, $xNew, $message, $check, $rochadeFirstMoves, $game);

//                      ToDo ungültige Rochade gilt als Zug !!!

                    } elseif ( strtolower($piece) === 'k' && strtolower($grid[$yNew][$xNew]) === 't' ){

                        if($piece === 'k' && ($yNew === 0 && $xNew === 0)){
                            if($rochadeFirstMoves[0][1] === true && $rochadeFirstMoves[0][0] === true){
                                $rochadeCoordinates = [[0,1],[0,2],[0,3]];
                                foreach ($rochadeCoordinates as $rochadeCoordinate){
                                    if($grid[$rochadeCoordinate[0]][$rochadeCoordinate[1]] === '' ){
                                        if( fieldUnderAttack($rochadeCoordinates[1][0],$rochadeCoordinates[1][1], $grid, $white, $vectors) === false &&
                                            fieldUnderAttack($rochadeCoordinates[2][0],$rochadeCoordinates[2][1], $grid, $white, $vectors) === false){
                                            $grid[0][4] = '';
                                            $grid[0][0] = '';
                                            $grid[0][2] = 'k';
                                            $grid[0][3] = 't';
                                            $message = "Kurze Rochade gezogen," . "<br>" . "⚪ Weiss am Zug!";
                                        } else {
                                            $message = "Rochade ungültig," . "<br>" . "Felder des Königs bedroht.";
                                        }
                                    }
                                }
                            } else {
                                $message = "Rochade ungültig," . "<br>" . "König od. Turm schon gezogen.";
                            }
                        }
                        if($piece === 'k' && ($yNew === 0 && $xNew === 7)){
                            if($rochadeFirstMoves[0][1] === true && $rochadeFirstMoves[0][2] === true){
                                $rochadeCoordinates = [[0,5],[0,6]];
                                foreach ($rochadeCoordinates as $rochadeCoordinate){
                                    if($grid[$rochadeCoordinate[0]][$rochadeCoordinate[1]] === '' ){
                                        if( fieldUnderAttack($rochadeCoordinates[0][0],$rochadeCoordinates[0][1], $grid, $white, $vectors) === false &&
                                            fieldUnderAttack($rochadeCoordinates[1][0],$rochadeCoordinates[1][1], $grid, $white, $vectors) === false){
                                            $grid[0][4] = '';
                                            $grid[0][7] = '';
                                            $grid[0][6] = 'k';
                                            $grid[0][5] = 't';
                                            $message = "Lange Rochade gezogen," . "<br>" . "⚪ Weiss am Zug!";
                                        } else {
                                            $message = "Rochade ungültig," . "<br>" . "Felder des Königs bedroht.";
                                        }
                                    }
                                }
                            } else {
                                $message = "Rochade ungültig," . "<br>" . "König od. Turm schon gezogen.";
                            }
                        }
                        if($piece === 'K' && ($yNew === 7 && $xNew === 0)){
                            if($rochadeFirstMoves[1][1] === true && $rochadeFirstMoves[1][0] === true){
                                $rochadeCoordinates = [[7,1],[7,2],[7,3]];
                                foreach ($rochadeCoordinates as $rochadeCoordinate){
                                    if($grid[$rochadeCoordinate[0]][$rochadeCoordinate[1]] === '' ){
                                        if( fieldUnderAttack($rochadeCoordinates[1][0],$rochadeCoordinates[1][1], $grid, $white, $vectors) === false &&
                                            fieldUnderAttack($rochadeCoordinates[2][0],$rochadeCoordinates[2][1], $grid, $white, $vectors) === false){
                                            $grid[7][4] = '';
                                            $grid[7][0] = '';
                                            $grid[7][2] = 'K';
                                            $grid[7][3] = 'T';
                                            $message = "Lange Rochade gezogen," . "<br>" . "⚫ Schwarz am Zug!";
                                        } else {
                                            $message = "Rochade ungültig," . "<br>" . "Felder des Königs bedroht.";
                                        }
                                    }
                                }
                            } else {
                                $message = "Rochade ungültig," . "<br>" . "König od. Turm schon gezogen.";
                            }
                        }
                        if($piece === 'K' && ($yNew === 7 && $xNew === 7)){
                            if($rochadeFirstMoves[1][1] === true && $rochadeFirstMoves[1][2] === true){
                                $rochadeCoordinates = [[7,5],[7,6]];
                                foreach ($rochadeCoordinates as $rochadeCoordinate){
                                    if($grid[$rochadeCoordinate[0]][$rochadeCoordinate[1]] === '' ){
                                        if( fieldUnderAttack($rochadeCoordinates[0][0],$rochadeCoordinates[0][1], $grid, $white, $vectors) === false &&
                                            fieldUnderAttack($rochadeCoordinates[1][0],$rochadeCoordinates[1][1], $grid, $white, $vectors) === false){
                                            $grid[7][4] = '';
                                            $grid[7][7] = '';
                                            $grid[7][6] = 'K';
                                            $grid[7][5] = 'T';
                                            $message = "Kurze Rochade gezogen," . "<br>" . "⚫ Schwarz am Zug!";
                                        } else {
                                            $message = "Rochade ungültig," . "<br>" . "Felder des Königs bedroht.";
                                        }
                                    }
                                }
                            } else {
                                $message = "Rochade ungültig," . "<br>" . "König od. Turm schon gezogen.";
                            }
                        }

                        $message = moveFinisher($white, $grid, $vectors, $yNew, $xNew, $message, $check, $rochadeFirstMoves, $game);

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
