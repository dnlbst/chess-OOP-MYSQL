<?php

require_once 'autoload.php';

$board = new Board();
$board->resetAction($_POST);
$board->initPieces();
$board->moveAction($_POST);
//var_dump($board->fieldUnderAttack(2, 6));
$board->saveGrid();
$message = $board->getMessage();

?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Chessboard</title>
    <link rel="stylesheet" href="css/board.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">


    <script language="JavaScript" type="text/javascript">
        function jump(that) {
            switch (that.id) {
                case 'from':
                    nextOne = document.getElementById("to");
                    break;
                case 'to':
                    nextOne = document.getElementById("submit");
                    break;
            }
            if (that.value.length > 0) {
                nextOne.focus();
            }
        }
    </script>

</head>
<body>
<div class="board">
    <div>
        <?php
        $board->showGrid();

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
</div>
<?php

echo "<div class='input'>
                <h4>$message</h4>
                <form action='' method='post'>
                    <label for='from'>von</label>
                    <input type='text' id='from' name='from' maxlength='2' value='' autofocus onkeypress='return jump(this);'>
                    <label for='to'>nach</label>
                    <input type='text' id='to' name='to' maxlength='2' value='' onkeypress='return jump(this);'>
                    <input type='submit' id='submit' value='Submit'>
                    <label for='reset'></label>
                    <input id='reset' type='submit' name='reset' value='Reset'>
                </form>
            </div>";

?>

</body>
</html>
