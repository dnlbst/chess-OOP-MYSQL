<html>
<head>
    <meta charset="UTF-8">
    <title>Chessboard</title>
    <link rel="stylesheet" href="css/board.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>
<body>
    <?php
        include('tepmlates/board.php');

        echo "<div class='input'>
                <h4>{$turn}</h4>
                <form action='' method='post'>
                    <label for='move'>from</label>
                    <input type='text' id='piece' name='piece' value='' placeholder='move from E.g 10' >
                    <label for='move'>to</label>
                    <input type='text' id='move' name='move' value='' placeholder='to E.g 20' >
                    <input type='submit' value='Submit'>
                    <label for='reset'></label>
                    <input id='reset' type='submit' name='reset' value='Reset'>
                </form>
            </div>"
        ;
    ?>
</body>
</html>