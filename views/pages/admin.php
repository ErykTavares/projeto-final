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

    .title {
        margin-bottom: 2rem
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
    <h2 class="title">Novos chamados</h2>

    <div class="asks-wrapper">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reply'])) {
                $token = $_POST['token'];
                $email = $_POST['email'];
                $calledResponse = $_POST['calledResponse'];

                $sql = \MySql::startConnection()->prepare("INSERT INTO call_response VALUES (null,'answered',?,?,'admin')");
                $sql->execute([$token, $calledResponse]);

                echo '<script>alert("Mensagem enviada!")</script>';
            } elseif (isset($_POST['reply_response'])) {
                $token = $_POST['token'];
                $replayResponse = $_POST['replayResponse'];
                $id = intval($_POST['id']);

                $update = \MySql::startConnection()->prepare("UPDATE call_response SET status = 'answered' WHERE id = ?");
                $update->execute([$id]);

                $sql = \MySql::startConnection()->prepare("INSERT INTO call_response VALUES (null,'answered',?,?,'admin')");
                $sql->execute([$token, $replayResponse]);

                echo '<script>alert("Respondido enviada!")</script>';
            }

            header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/admin');
            exit;
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
                <form action="" method="post">
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
    <h2 class="title">Últimas interações:</h2>
    <?php

    $getCallResponses = \MySql::startConnection()->prepare("SELECT * FROM call_response WHERE user_position = 'user' AND status = 'unanswered' ORDER BY id DESC");
    $getCallResponses->execute();
    $getCallResponses = $getCallResponses->fetchAll();


    foreach ($getCallResponses as $key => $value) {
        ?>
        <div class="called-card">
            <h2><?php echo $value["message"]; ?></h2>
            <form action="" method="post">
                <textarea placeholder="Sua Resposta" name="replayResponse"></textarea>
                <br>
                <br>
                <input class="submit-button" type="submit" name="reply_response" value="Responder">
                <input type="hidden" name="token" value="<?php echo $value['id_called']; ?>">
                <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
            </form>

        </div>

    <?php } ?>
</div>