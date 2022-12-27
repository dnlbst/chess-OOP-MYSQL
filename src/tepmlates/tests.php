<section class="board">
    <div>

        <?php

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

//        function testPawnToQueen(){
//            $result = pawnToQueen(0, $xNew, $grid);
//                if ($result){
//                    echo " - pawnToQueen 0 works";
//                }
//            $result = pawnToQueen(7);
//                if ($result){
//                    echo " - pawnToQueen 7 works";
//                }
//            $result = pawnToQueen(1);
//                if ($result === false){
//                    echo " - pawnToQueen 1=false works";
//                }
//        }

        function testFindKing(){
            $testGrid = [
                'kings' =>  [
                        ['','k',''],
                        ['','K',''],
                    ],
            ];
            $testKings = $testGrid['kings'];
            $result = findKing($testKings);
            foreach ($result as $testKing){
                echo ' - find King works: ' . $testKings[$testKing[0]][$testKing[1]] . ' - ' ;
            }

        }

        function testFieldUnderAttack(){
            $testUnderAttack = false;
            $result = fieldUnderAttack();

            return $testUnderAttack;
        }

//$result = fieldUnderAttack(4,5,$grid, false, $vectors);
//var_dump($result);

        // TESTING all functions
        if (isset($_GET['test']) && $_GET['test'] == 1) {

//            testCoordinateInArray();
//            testPawnToQueen();
            testFindKing();
//            testFieldUnderAttack();
        }

        ?>