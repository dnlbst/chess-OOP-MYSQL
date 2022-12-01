<section class="board">
    <div>
        <?php

        /* Unicode Chess Pieces */
        $pieces = [
            'K' => '&#x2654;','D' => '&#x2655;','T' => '&#x2656;','L' => '&#x2657;','S' => '&#x2658;','B' => '&#x2659;',
            'k' => '&#x265A;','d' => '&#x265B;', 't' => '&#x265C;', 'l' => '&#x265D;', 's' => '&#x265E;', 'b' => '&#x265F;'
        ];

        /* initial grid array */
        $grid = [
            ['t','l','s','d','k','s','l','t'],
            ['b','b','b','b','b','b','b','b',],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['B','B','B','B','B','B','B','B'],
            ['T','L','S','D','K','S','L','T'],
        ];


        /* store moves in json file */
        /*if($_POST['reset'] == 'reset'){
        }*/
        $moves = $grid;

        if(file_exists('moves.txt')) {
            $file = file_get_contents('moves.txt', true);
            $moves = json_decode($file, true);
            file_put_contents('moves.txt', json_encode($moves, JSON_PRETTY_PRINT));
        }

        /* moving pieces in general */
        /*if(isset($_POST['piece'])&&($_POST['move'])) {}*/
            $oldPos = $_POST['piece'];
            $newPos = $_POST['move'];
            $grabPiece = str_split($oldPos);
            $setPiece = str_split($newPos);
            $piece = $moves[$grabPiece[0][0]][$grabPiece[1][0]];
            $moves[$setPiece[0][0]][$setPiece[1][0]] = $piece;



            /* remove old position from grid */
            /*$moves[$grabPos[0][0]][$grabPos[1][0]] = '';*/


        /*var_dump($moves[$setPos[0][0]][$setPos[1][0]]);*/




        /* show new move */
        foreach ($moves as $key=>$move){

        }

        /* chess board loop */
        for($i=0; $i <= 7; $i++)
        {
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
                if ($moves[$i][$j] !== '') {
                    echo $pieces[$moves[$i][$j]];
                }

                echo '</div>';
            }
            echo "</div>";
        }

        /* reset */


        ?>

    </div>
    <!-- input forms -->
    <div class="input">
        <h4>Enter next Move</h4>
        <form action="" method="POST">
            <label for="move">from</label>
            <input type="text" id="piece" name="piece" placeholder="move from E.g 22" >
            <label for="move">to</label>
            <input type="text" id="move" name="move" placeholder="to E.g 23" >
            <input type="submit" value="Submit">
            <!--<label for="reset">reset</label>
            <input type="submit" name="reset" value="reset">-->
        </form>
    </div>
</section>

