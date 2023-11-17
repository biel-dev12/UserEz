<?php
session_start();
include_once("./sistema/config/connection.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./imgs/favicon-cropped.svg" type="image/x-icon">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/login-page.css">
    <script src="./js/login.js" defer></script>
    <title>Login - EzPets</title>
</head>

<body>
    <main>
        <div class="login-container move" id="login-container">
            <div class="form-container">
                <form class="form form-login" action="#" method="POST">
                    <img src="./imgs/logo-ezpets.svg" alt="Logo">
                    <h1 class="form-title">Entrar</h1>
                    <div class="form-text">Sua vida mais easy!</div>
                    <div class="form-input-container">
                        <div class="inp-box">
                            <input type="email" name="emailE" class="form-inp" placeholder="E-mail" required>
                        </div>
                        <div class="inp-box">
                            <input type="password" name="passwE" class="form-inp" placeholder="Senha" required>
                        </div>
                    </div>
                    <!-- <a href="#" class="form-link">Esqueceu a senha?</a> -->
                    <button class="form-btn">Entrar</button>
                    <?php
                    if (isset($_POST['emailE'], $_POST['passwE'])) {

                        $emailE = $_POST['emailE'];
                        $passwE = $_POST['passwE'];

                        $sql = $conn->query("SELECT id_user, nm_user, cd_user_tel, cd_user_cpf, nm_user_email, cd_user_password
        FROM tb_user WHERE nm_user_email='$emailE' AND cd_user_password='$passwE'");


                        $sql->execute();


                        $row = $sql->fetch();
                        if ($row > 0) {
                            if ($passwE == $row['cd_user_password']) {

                                $_SESSION["emailE"] = $emailE;
                                $_SESSION["name-user"] = $row["nm_user"];
                                echo "<script>location.href = './main.html';</script>"; 
                            }
                        } else {
                            // Usuário não encontrado
                            echo '<div class="alert alert-danger" role="alert">
        E-mail e/ou senha inválido(s). Por favor, verifique.
      </div>';
                        }
                    }
                    ?>

                    <p class="mobile-text">Não tem conta? <a href="#" id="signup-mobile">Cadastre-se</a></p>
                </form>
                <form class="form form-signup" action="#" method="POST">
                    <img src="./imgs/logo-ezpets.svg" alt="Logo">
                    <h1 class="form-title">Criar conta</h1>
                    <div class="form-text">Confira os produtos perto de você!</div>
                    <div class="form-input-container">
                        <div class="inp-box">
                            <input type="text" name="nameL" class="form-inp" placeholder="Nome" required>
                        </div>
                        <div class="inp-box">
                            <input type="email" name="emailL" class="form-inp" placeholder="E-mail" required>
                        </div>
                        <div class="inp-box">
                            <input type="password" name="passwL" class="form-inp" placeholder="Senha" required>
                        </div>
                    </div>
                    <button class="form-btn" style="margin-top: 10px; min-height:40px;">Cadastrar</button>
                    <?php
                    if (isset($_POST['nameL'], $_POST['emailL'], $_POST['passwL'])) {
                        if ($_POST['nameL'] != "" && $_POST['emailL'] != "" && $_POST['passwL'] != "") {
                            $nameL = $_POST['nameL'];
                            $emailL = $_POST['emailL'];
                            $passwL = $_POST['passwL'];

                            $_SESSION['nameL'] = $nameL;
                            $_SESSION['emailL'] = $emailL;
                            $_SESSION['passwL'] = $passwL;

                            try {
                                $stmt = $conn->prepare("INSERT INTO tb_user(nm_user, nm_user_email, cd_user_password) VALUES (:nameL, :emailL, :passwL)");

                                $stmt->bindParam(":nameL", $nameL);
                                $stmt->bindParam(":emailL", $emailL);
                                $stmt->bindParam(":passwL", $passwL);
                                $stmt->execute();
                                echo '<div class="alert alert-success" role="alert" style="text-align: center; margin-top: 10px;">
        Cadastro realizado com sucesso! Agora é só entrar na sua conta.
      </div>';
                            } catch (PDOException $e) {
                                echo "Erro ao cadastrar: " . $e->getMessage();
                            }
                            $conn = null;
                        }
                    }

                    ?>
                    <p class="mobile-text">Já tem conta? <a href="#" id="login-mobile">Log In</a></p>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <h2 class="form-title form-title-white">Já tem conta?</h2>
                    <p class="form-text form-text-white">Para entrar na nossa plataforma faça login com suas informações</p>
                    <button class="form-btn form-btn-white" id="btn-login">Entrar</button>
                </div>
                <div class="overlay">
                    <h2 class="form-title form-title-white">Novo por aqui?</h2>
                    <p class="form-text form-text-white">Cadastre-se e comece a usar nossa plataforma on-line!</p>
                    <button class="form-btn form-btn-white" id="btn-signup">Cadastrar</button>
                </div>
            </div>
        </div>
    </main>
</body>

</html>