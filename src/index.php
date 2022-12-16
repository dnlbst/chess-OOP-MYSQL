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
        include('tepmlates/tests.php');

        echo "<div class='input'>
                <h4>{$message}</h4>
                <form action='' method='post'>
                    <label for='from'>from</label>
                    <input type='text' id='from' name='from' maxlength='2' placeholder='' autofocus>
                    <label for='to'>to</label>
                    <input type='text' id='to' name='to' maxlength='2' placeholder='' >
                    <input type='submit' value='Submit'>
                    <label for='reset'></label>
                    <input id='reset' type='submit' name='reset' value='Reset'>
                </form>
            </div>"
        ;
    ?>
</body>
</html>