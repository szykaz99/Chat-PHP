<?php

class Message{
    private $start_rows, $refresh_cnt, $limit = 400;
    // Tworzenie tabeli chat jeśli nie istnieje + zalogowanie do bazy
    function __construct($time = null) {
        if (is_null($time)) {
            $this->time = time();
        } else {
            $this->time = $time;
        }

        require "connect.php";
        $this->conn = new mysqli($host, $db_user, $db_password, $db_name);
        if ($this->conn->connect_errno!=0) {
            echo "Error: ".$this->conn->connect_errno;
        } else {
            $this->conn->query('SET NAMES utf8');
            $this->conn->query('SET CHARACTER_SET utf8_unicode_ci');
            if (!$this->table_exists('chat')) {
                $this->conn->query("CREATE TABLE `newchat`.`chat` ( `Id` INT NOT NULL AUTO_INCREMENT ,  `Message` TEXT NOT NULL ,  `Id_from` INT NOT NULL ,  `Id_to` INT NOT NULL ,  `Send-DATE` TIMESTAMP NOT NULL ,    PRIMARY KEY  (`Id`)) ENGINE = InnoDB;");
            }
        }
    }

    // Sprawdzenie czy jest tabela chat
    private function table_exists($table) {
        $data = $this->conn->query("SELECT * FROM `newchat`.`chat` WHERE 1");
        return count(array($data)) > 0;
    }
    // Pierwsze wyświetlenie danych dla danego czatu
    public function display($values, $id_from, $id_to) {
        $cnt = $this->conn->query("SELECT COUNT(*) FROM `chat` WHERE (`Id_from` = '$id_from' AND `Id_to` = '$id_to') OR (`Id_from` = '$id_to' AND `Id_to` = '$id_from')");
        $row = mysqli_fetch_array($cnt);
        $this->start_rows = $row['COUNT(*)'];
        $_SESSION['start_rows'] = $this->start_rows;
        return $this->conn->query("SELECT $values FROM `chat` WHERE (`Id_from` = '$id_from' AND `Id_to` = '$id_to') OR (`Id_from` = '$id_to' AND `Id_to` = '$id_from') ORDER BY `Send-DATE`");
    }
    // Dane kto wysłał wiadomość 
    public function display_user($id_user) {
        return $this->conn->query("SELECT `Fname` FROM `users` WHERE `Id` = '$id_user'");
    }

    // Dane kto wysłał wiadomość 
    public function display_interlocutor($id_interlocutor) {
        return $this->conn->query("SELECT `Fname`,`Lname` FROM `users` WHERE `Id` = '$id_interlocutor'");
    }
    // Odświerzanie wiadomości po wysłaniu
    public function display_refresh($from,$to,$t_r){
        $values = '`Message`, `Id_from`, `Id_to`, `Send-DATE`';
        $sql = "SELECT $values FROM `chat` WHERE (`Id_from` = '$from' AND `Id_to` = '$to') OR (`Id_from` = '$to' AND `Id_to` = '$from') ORDER BY `Send-DATE` DESC LIMIT $t_r";
        $sql1 = "SELECT `Fname` FROM `users` WHERE `Id` = '$from'";
        $sql2 = "SELECT `Fname` FROM `users` WHERE `Id` = '$to'";
        foreach ($this->conn->query($sql1) as $row){
            $your_name=$row['Fname'];
          }
          foreach ($this->conn->query($sql2) as $row){
            $interlocutor_name=$row['Fname'];
          }
        foreach ($this->conn->query($sql) as $row){
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
    }

    function refresh($from,$to){
        $rcnt = $this->conn->query("SELECT COUNT(*) FROM `chat` WHERE (`Id_from` = '$from' AND `Id_to` = '$to') OR (`Id_from` = '$to' AND `Id_to` = '$from')");
        $row = mysqli_fetch_array($rcnt);
        $this->refresh_cnt = $row['COUNT(*)'];
        $this->checkRefresh($this->refresh_cnt,$from,$to);
    }

    private function checkRefresh($r_cnt,$from,$to){
        //$this->start_rows = $_SESSION['start_rows'];;
        $s_cnt = $_SESSION['start_rows'];
        if ($s_cnt != $r_cnt){
            $total_rows = $r_cnt - $s_cnt;
            $_SESSION['start_rows'] = $r_cnt;
            $this->display_refresh($from,$to,$total_rows);
        } else {
            //echo "==";
            //$this->start_rows = $r_cnt;
            //echo $s_cnt." == ".$r_cnt. " -- ";
        }
    }

    // Dodanie nowej wiadomości
    function newMessage($id_from, $id_to, $message) {
        if (strlen($message) > $this->limit) {
            $message = substr($message, 0, $this->limit) . "...";
        }
        $this->conn->query('SET NAMES utf8');
        $this->conn->query('SET CHARACTER_SET utf8_unicode_ci');
        return $this->conn->query("INSERT INTO `chat` (`Message`, `Id_from`, `Id_to`, `Send-DATE`) VALUES ('$message','$id_from','$id_to',now())");
    }

}