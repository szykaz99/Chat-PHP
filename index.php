<?php

    session_start();
    if ((isset($_SESSION['Login-status'])) && ($_SESSION['Login-status']==true)) {
        header('Location: chatpanel.php');
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <title>Panel Race4Car</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="icon" href="img/icon.png" sizes="64x64">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/content.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="window">
            <div class="logo-top">
                <img alt="" class="logo-top_img" src="img/race4car_logo-w.png">
            </div>
            <div class="form-place">
                        <form action='login.php' method='post'>
                            <div class='form-group'>
                                <i class='fa fa-user'></i>
                                <input type='text' name='Username' class='form-control' placeholder='Username' required='required'>
                            </div>
                            <div class='form-group'>
                                <i class='fa fa-lock'></i>
                                <input type='password' name='Password' class='form-control' placeholder='Password' required='required'>					
                            </div>
                            <div class='form-group'>
                                <label class='checkbox-inline'><input type='Checkbox' name='Checkbox'>Remember me</label>
                            </div>
                            <div class='form-group-submit'>
                                <input type='submit' class='btn btn-primary btn-block btn-lg' value='Login'>
                            </div>
                        </form>				
                        <?php if(isset($_SESSION['e_login'])) echo $_SESSION['e_login']; unset($_SESSION['e_login']); ?>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>