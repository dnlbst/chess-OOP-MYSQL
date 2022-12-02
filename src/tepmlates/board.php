<section class="board">
    <div>
        <?php

        /* Unicode Chess Pieces */
        $UnicodePieces = [
            'K' => '&#x2654;','D' => '&#x2655;','T' => '&#x2656;','L' => '&#x2657;','S' => '&#x2658;','B' => '&#x2659;',
            'k' => '&#x265A;','d' => '&#x265B;', 't' => '&#x265C;', 'l' => '&#x265D;', 's' => '&#x265E;', 'b' => '&#x265F;'
        ];

        /* initial grid array */
        $startGrid = [
            ['t','l','s','d','k','s','l','t'],
            ['b','b','b','b','b','b','b','b',],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['','','','','','','',''],
            ['B','B','B','B','B','B','B','B'],
            ['T','L','S','D','K','S','L','T'],
            [''],
        ];

        if(file_exists('grid.txt')) {
            $file = file_get_contents('grid.txt', true);
            $grid = json_decode($file, true);
        } else {
            file_put_contents('grid.txt', json_encode($startGrid, JSON_PRETTY_PRINT));
            $grid = $startGrid;
        }

        /* moving pieces */
        $piece = 'x';
        if(isset($_POST['piece'])&&($_POST['move'])) {
            $oldPos = str_split($_POST['piece']);
            $piece = $grid[$oldPos[0][0]][$oldPos[1][0]];
            $newPos = str_split($_POST['move']);
            $grid[$newPos[0][0]][$newPos[1][0]] = $piece;
            /* remove old position */
            $grid[$oldPos[0][0]][$oldPos[1][0]] = '';

            /* check players turn */

            /* save players turn */
            $grid[8][0] = $piece;
            file_put_contents('grid.txt', json_encode($grid, JSON_PRETTY_PRINT));
        }

        if(ctype_upper($piece)) {
            $turn = "It's black's turn";
        } else {
            $turn = "It's white's turn";
        }


        echo "<br>";
        var_dump($grid[8][0]);
        echo "<br>";
        var_dump($piece);
        echo "<br>";
        var_dump(ctype_upper($piece));

        /* reset */
        if (isset($_POST['reset'])) {
            unlink('grid.txt');
        }

        /* chess board loop */
        function showGrid($grid, $pieces) {
            for($i=0; $i <= 7; $i++)
            {
                echo "<div class='rownum'>$i</div>";
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
    </div>


</section>
