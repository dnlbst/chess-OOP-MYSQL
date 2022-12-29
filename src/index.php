<html>
<head>
    <meta charset="UTF-8">
    <title>Chessboard</title>
    <link rel="stylesheet" href="css/board.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

    <script language="JavaScript" type="text/javascript" >
        function jump(that)
        {
            switch(that.id)
            {
                case 'from':
                    nextone = document.getElementById("to");
                    break;
            }
            if(that.value.length > 0)
            {
                nextone.focus();
            }
        }
    </script>

</head>
<body>
    <?php
        include('tepmlates/board.php');
        include('tepmlates/tests.php');

        echo "<div class='input'>
                <h4>{$message}</h4>
                <form action='' method='post'>
                    <label for='from'>von</label>
                    <input type='text' id='from' name='from' maxlength='2' value='' autofocus onkeypress='return jump(this);'>
                    <label for='to'>auf</label>
                    <input type='text' id='to' name='to' maxlength='2'      value='' ' >
                    <input type='submit' value='Submit'>
                    <label for='reset'></label>
                    <input id='reset' type='submit' name='reset' value='Reset'>
                </form>
            </div>"
        ;
    ?>

</body>
</html>