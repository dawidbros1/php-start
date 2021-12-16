<html lang="pl">

<head>
    <title>Widok główny</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <link href="public/css/style.css" rel="stylesheet">

    <?php if ($style): ?>
        <link href="public/css/<?=$style?>.css" rel="stylesheet">
    <?php endif;?>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <ul class="navbar-nav">
                <li class="nav-item ps-sm-2">
                    <!-- <a class="nav-link" href="<?=$route['home']?>">Strona główna</a> -->
                    <a class="nav-link" href="./">Strona główna</a>
                </li>

                <?php if ($user) {require_once "templates/menu/user.php";}?>
                <?php if (!$user) {require_once "templates/menu/guest.php";}?>
            </ul>
        </nav>

        <div class="content">
            <?php require_once "templates/messages.php";?>
            <?php require_once "templates/$page.php";?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>