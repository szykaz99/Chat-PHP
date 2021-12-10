<?php 
  session_start();
  $from = $_GET['from'];
  $to = $_GET['to'];
  $_SESSION['frame'] = "chat.php?from=$from&to=$to";
  require_once "connect.php";
  $conn = @new mysqli($host, $db_user, $db_password, $db_name);

  if ($conn->connect_errno!=0) {
    echo "Error: DataBase shutdown Key:".$conn->connect_errno;
  } else {
    $conn->query('SET NAMES utf8');
    $conn->query('SET CHARACTER_SET utf8_unicode_ci');
    $sql = "SELECT * FROM `users` WHERE Id=$to";
    $rezultat = $conn->query($sql);
    if ($rezultat->num_rows > 0) {
      while($row = $rezultat->fetch_assoc()) {
        $title_name = $row['Fname']." ".$row['Lname'];
      }
    } else {
      $title_name = "unknown id=$to";
    }
    $conn->close();
  } 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Chatting with: <?php echo $title_name; ?></title>
    <link rel="icon" href="/favicon/favicon.ico">
    <link href="css/chat.css?v=0" rel="stylesheet"/>
  </head>
<body onload='refresh(<?php echo $from.",".$to;?>)'>
<?php
//echo $_SESSION['start_rows'];
  require_once('Message.php');
  $message = new Message(0);
  $data = $message->display('`Message`, `Id_from`, `Id_to`, `Send-DATE`',$from ,$to);
  $user = $message->display_user($from);
  $interlocutor = $message->display_interlocutor($to);
  foreach ($user as $row){
    $your_name=$row['Fname'];
  }
  foreach ($interlocutor as $row){
    $interlocutor_name=$row['Fname'];
    $interlocutor_name2 = $row['Fname']." ".$row['Lname'];
  }
?>
  <div class='header'>
    <div class='header-img'> 
      <img src="img\avatar_default.jpg" />
    </div>
    <div class='header-text'>
      <?php echo $interlocutor_name2;?>
    </div>
  </div>
  <div id="messages">
    <?php
      foreach ($data as $row){
        $msg = htmlentities($row['Message'], ENT_QUOTES, 'UTF-8');
        $fr=$row['Id_from'];
        $t=$row['Id_to'];
        $me=$row['Message'];

        $ti=$row['Send-DATE'];
        $timestamp = strtotime($ti);
        $conv_time = date("m-d-Y-H-i-s", $timestamp);
        list($day,$month,$year,$hour,$min,$sec) = explode("-",$conv_time); 
        $s_date = $month.' / '.$day.' / '.$year;
        $s_time = $hour.':'.$min;
          
        if ($row['Id_from'] == $from){
          echo "<div class='msg-r'><p>$your_name</p>$me<span>$s_time</span></div>";
        } else {
           echo "<div class='msg'><p>$interlocutor_name</p>$me<span>$s_time</span></div>";
        }
      } 
    ?>
  </div>
  <div class='sender'>
    <input id='input' type='text' name='discount' value='' />
    <button id='send' onclick='sender(<?php echo $from.",".$to;?>)'><i class="fas fa-chevron-circle-right"></i></button>
  <div>
    <script>
      document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
    </script>
    <script type='text/javascript' src='js/sender.js'></script>
    <script type='text/javascript' src='js/refresh.js'></script>
    <script type='text/javascript' src='https://kit.fontawesome.com/704b3392df.js' crossorigin='anonymous'></script>
  </body>
</html>
