<?php
    $token = $_GET['token'];

    echo '<h2>pagina do chamado, token: '.$token.'</h2>';

?>


<h2>Pergunta do suporte: <?php echo $info["ask"] ?> </h2>

<?php
    echo "<hr/>";

    $interactions = \MySql::startConnection()->prepare("SELECT * from call_response WHERE id_called = ? "); 
    $interactions->execute([$token]);
    $interactions = $interactions->fetchAll();

   

    foreach($interactions as $key => $value){
        if($value['user_position'] == "admin"){
            echo "<p><b>Admin: </b>".$value["message"]."</p>";
         
        }else{
            echo "<p><b>Você: </b>".$value["message"]."</p>";
        }
        echo "<hr/>";
    }
   
?>


<?php
    if(isset($_POST["action"])){
        $postMessage = $_POST["message"];
        $sql = \MySql::startConnection()->prepare("INSERT INTO call_response VALUES (NULL,?,?,?)");
        $sql->execute([$token,$postMessage, "user"]);

        echo "<script>alert('Sua resposta foi enviada! Aguarde uma resposta do administrador.')</script>";
        echo "<script>location.href='".BASE."chamado?token=".$token."'</script>";
        die();
    }


    $sql = \MySql::startConnection()->prepare("SELECT * FROM call_response WHERE id_called = ? ORDER BY id DESC");
    $sql->execute([$token]);

    $message = "<h5>Aguarde uma resposta do Administrador</h5>";

    if($sql->rowCount() == 0){
        echo  $message;
    }else{
        $info = $sql->fetchAll();
        if($info[0]['user_position'] !== "admin"){
             echo  $message;
        }else{
                echo '  <form method="post">
                            <textarea name="message" placeholder="Sua Resposta"></textarea>
                            <br />
                            <input type="submit" name="action" value="Enviar">
                        </form>';
        }
        
    }
?>
