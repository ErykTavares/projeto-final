<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: start;
        padding: 0 1rem;
        overflow-x: hidden;
    }

    .asks-wrapper {
        width: 100%;
        display: grid;
        grid-template-columns: max-content;
        grid-template-rows: auto;
        place-items: start;
        place-content: center;
        padding: 0 2rem;
        overflow-x: hidden;
    }

    .called-card {
        width: 400px;
        margin-bottom: 1rem;
        padding: .5rem;
        border-radius: .8rem;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }



    .submit-button {
        background-color: #13aa52;
        border: 2px solid #13aa52;
        border-radius: .5rem;
        box-shadow: rgba(0, 0, 0, .1) 0 2px 4px 0;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        font-family: "Akzidenz Grotesk BQ Medium", -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 16px;
        font-weight: 400;
        outline: none;
        outline: 0;
        padding: 10px 25px;
        text-align: center;
        transform: translateY(0);
        transition: transform 150ms, box-shadow 150ms;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        font-weight: 600;
        transition: background-color 0.8s ease, color 0.8s ease, transform 0.5s ease
    }

    .submit-button:hover {
        box-shadow: rgba(0, 0, 0, .15) 0 3px 9px 0;
        background: #ffff;
        color: #13aa52;
        transform: translateY(-2px);
    }

    @media (min-width: 768px) {
        .submit-button {
            padding: 10px 30px;
        }
    }

    textarea,
    input {
        width: 100%;
        margin-top: .5rem;
    }

    textarea {
        height: 120px;
    }
</style>


<div class="container">
    <h2>Novos chamados</h2>

    <div class="asks-wrapper">
        <?php
        if (isset($_POST['reply'])) {
            $token = $_POST['token'];
            $email = $_POST['email'];
            $calledResponse = $_POST['calledResponse'];

            $sql = \MySql::startConnection()->prepare("INSERT INTO call_response VALUES (null,?,?,? )");
            $sql->execute([$token, $calledResponse, "admin"]);

            echo '<script>alert("Mensagem enviada!")</script>';
        }


        ?>

        <?php

        $getCalleds = \MySql::startConnection()->prepare("SELECT * FROM called ORDER BY id DESC");
        $getCalleds->execute();
        $getCalleds = $getCalleds->fetchAll();


        foreach ($getCalleds as $key => $value) {
            $checkCalled = \MySql::startConnection()->prepare("SELECT * FROM call_response WHERE id_called = '$value[called_token]'");
            $checkCalled->execute();
            if ($checkCalled->rowCount() >= 1) {
                continue;
            }


            ?>
            <div class="called-card">
                <h2><?php echo $value["ask"]; ?></h2>
                <form method="post">
                    <textarea placeholder="Sua Resposta" name="calledResponse"></textarea>
                    <br>
                    <br>
                    <input class="submit-button" type="submit" name="reply" value="Responder">
                    <input type="hidden" name="token" value="<?php echo $value['called_token']; ?>">
                    <input type="hidden" name="email" value="<?php echo $value["email"]; ?>">
                </form>

            </div>

        <?php } ?>
    </div>
    <hr>
    <h2>Últimas interações:</h2>
</div>