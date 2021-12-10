<?php
    session_start();
    if ((!isset($_SESSION['Login-status'])) && ($_SESSION['Login-status']!=true)) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <title>Chat Panel</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="icon" href="img/icon.png" sizes="64x64">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/panel.css?v=0" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="header">
            <div class="header_logo">
                <img alt="" class="header_logo_img" src="img/race4car_logo-w.png">
            </div>
            <div class="header-flex">
            </div>
            <div class="header-user">
                <div class="header-user-center">
                    <div class="mini-profile-avatar">
                        <img class="mini-profile-avatar-photo" src="img\<?php //echo $_SESSION['avatar']; ?>">
                    </div>
                    <div class="mini-profile-name">
                        <span class="mini-profile-name-user" onclick="settings()" title="Settings <?php echo $_SESSION['Login']; ?>">
                            <?php echo '<p class="mini-profile-name-par">'.$_SESSION['Login'].'</p>'; ?>
                        </span>
                    </div>
                    <button><a href="logout.php">Wyloguj</a></button>
                </div>
            </div>
        </div>
        <div class="menu">
            <?php 
                $your_id = $_SESSION['Id'];
                require_once "connect.php";
                $conn = @new mysqli($host, $db_user, $db_password, $db_name);

                if ($conn->connect_errno!=0) {
                    echo "Error: DataBase shutdown Key:".$conn->connect_errno;
                }
                else {
                    $conn->query('SET NAMES utf8');
                    $conn->query('SET CHARACTER_SET utf8_unicode_ci');
                    $sql = "SELECT * FROM `friendlist`, `users` WHERE (friendlist.Id_from = '$your_id' AND friendlist.Status = 'FRIEND' AND users.Id != friendlist.Id_from AND users.Id = friendlist.Id_to) OR (friendlist.Id_to = '$your_id' AND friendlist.Status = 'FRIEND' AND users.Id != friendlist.Id_to AND users.Id = friendlist.Id_from)";
                    $rezultat = $conn->query($sql);
                    if ($rezultat->num_rows > 0) {
                        while($row = $rezultat->fetch_assoc()) {
                            $u_id = $row['Id'];
                            $u_name = $row['Fname']." ".$row['Lname'];
                            echo "<div class='menu-list'>
                            <div onclick='chat($your_id,$u_id)' class='menu-list-cont'>
                                <i class='fa fa-dashboard m-l-i'></i>
                                <p class='menu-list-text'>$u_name</p>
                            </div>
                        </div>
                            ";
                        }
                    $conn->close();
                    } else {
                        echo "";
                    }
                } 
            ?>
        </div>
        <div class="page">
            <iframe id="frame-id" class="frame-id" src="<?php if (!isset($_SESSION['frame'])) {echo 'chat.php';} else {echo $_SESSION['frame'];}?>"></iframe>
        </div>
        <script src="js/frame.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>