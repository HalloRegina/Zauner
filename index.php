<?php
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Theater</title>
    <meta charset="utf-8">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav>
    <?php
    include 'nav.html';
    ?>
</nav>
<main>
    <?php
    if (isset($_GET['seite']))
    {
        switch($_GET['seite'])
        {
            case 'startseite':
                include 'start.php';
                break;
            case 'neuspielplan':
                include 'neuspielplan.php';
                break;
            case 'suchetheaterstuck':
                include 'suchetheaterstuck.php';
                break;
        }
    }
    else
    {
    }

    ?>
</main>
</body>
</html>
