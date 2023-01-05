<section class="board">
    <div>

        <?php

        $vectors = [
            'k' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            'd' => [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]],
            't' => [[-1,0],[0,1],[1,0],[0,-1]],
            'l' => [[-1,-1],[-1,1],[1,-1],[1,1]],
            's' => [[-2,-1],[-2,1],[-1,2],[1,2],[2,1],[2,-1],[1,-2],[-1,-2]],
            'b' => [[-1,0]],
        ];

        function testCoordinateInArray(){
            $testCoordinates = [[1,3], [4,7], [8,64]];
            $result = coordinateInArray(1,3, $testCoordinates);
            if ($result) {
                echo ' - Test coordinateInArray auf true erfolgreich';
            }
            $result = coordinateInArray(5,8, $testCoordinates);
            if ($result === false) {
                echo ' - Test coordinateInArray auf false erfolgreich';
            }
        }

        function testPawnToQueen(){
            $result = pawnToQueen(0, $xNew, $grid);
                if ($result){
                    echo " - pawnToQueen 0 works";
                }
            $result = pawnToQueen(7);
                if ($result){
                    echo " - pawnToQueen 7 works";
                }
            $result = pawnToQueen(1);
                if ($result === false){
                    echo " - pawnToQueen 1=false works";
                }
        }

        function testFieldUnderAttack($vectors){
            $simGrid = [
                'grid' => [
                    ['','','','','','','',''],
                    ['','','','','','','',''],
                    ['','','','','','','',''],
                    ['','','','','','K','',''],
                    ['','t','','','','','',''],
                    ['T','','','','','','',''],
                    ['','','','','','','',''],
                    ['','','k','','','','',''],
                ],
                'white' => false,
                'check' => [false, false],
                'rochadeFirstMoves' => [
                    [true,true,true],
                    [true,true,true],
                ],
            ];
            $result = fieldUnderAttack(4,0, $simGrid['grid'], $simGrid['white'], $vectors);
                if ($result) {
                    echo ' - Test fieldUnderAttack auf true erfolgreich';
                }
            $result = fieldUnderAttack(6,0, $simGrid['grid'], $simGrid['white'], $vectors);
                if ($result) {
                    echo ' - Test fieldUnderAttack auf false erfolgreich';
                }
        }

        function TestIncheck($vectors){
            $simGrid = [
                'grid' => [
                    ['','','','','','','',''],
                    ['','','','','','','',''],
                    ['','','','','','','',''],
                    ['','','','','','K','',''],
                    ['','t','','','','','',''],
                    ['','','','','','','',''],
                    ['L','','','','','','',''],
                    ['','','k','','','','',''],
                ],
                'white' => false,
                'check' => [false, false],
                'rochadeFirstMoves' => [
                    [true,true,true],
                    [true,true,true],
                ],
            ];
            $result = inCheck($simGrid['grid'], $simGrid['white'], $vectors);
                if ($result){
                    echo ' - Test inCheck auf true erfolgreich';
                } else {
                    echo ' - Test inCheck auf false erfolgreich';
                }
        }

// TESTING all functions
        if (isset($_GET['test']) && $_GET['test'] == 1) {

//            testCoordinateInArray();
//            testPawnToQueen();
//            testFieldUnderAttack();
//            TestIncheck($vectors);

        }

        ?>