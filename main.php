<?php
session_start();
include_once("./sistema/config/connection.php");

if (!isset($_SESSION['emailE'])) {
?>
    <script>
        location.href = "./index.php";
    </script>
<?php

}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./imgs/favicon-cropped.svg" type="image/x-icon" />

    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style2.css">
    <title>Home - EzPets</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary px-3 pt-3 d-flex flex-column">
        <div class="container-fluid cont-nav" style="gap: 1rem;">
            <a class="navbar-brand d-flex" href="#" style="width: 7rem;"><img src="./imgs/logo-ezpets.svg" alt="Logo EzPts" style="width: 100%; height: 100%;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex" role="search">
                    <input class="form-control" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success border" type="submit" id="btn-search"><i class="bi bi-search"></i></button>
                </form>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Rua Jose Bonifacio, 5555
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item mb-0" href="#">Rua João Calixto Alvez, 809</a></li>
                            <li><button class="w-100" id="add-address"><i class="bi bi-plus-circle ms-1"></i></button></li>
                        </ul>
                    </li>
                    <li class="nav-item car">
                        <button class="nav-link" href="#"><i class="bi bi-cart2"></i></button>
                    </li>
                    <li class="nav-item profile">
                        <button class="nav-link" href="#"><i class="bi bi-person-circle"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main id="main-content">
        <div id="carousel-content" class="bg-primary">
            <div id="carouselExampleIndicators" class="carousel slide carr" data-bs-ride="carousel" data-bs-interval="5000">

                <!-- Início indicadores para navegar nos slides do carousel -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>
                <!-- Fim indicadores para navegar nos slides do carousel -->

                <!-- Início slide carousel -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="./imgs/1.png" class="d-block carr-img" alt="Categoria 1">
                    </div>
                    <div class="carousel-item">
                        <img src="./imgs/2.png" class="d-block carr-img" alt="Categoria 2">
                    </div>

                </div>
                <!-- Fim slide carousel -->

                <!-- Início anterior e próximo slide carousel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <!-- Fim anterior e próximo slide carousel -->

            </div>
        </div>

        <!-- Petshops -->
        <div class="container mt-4 px-3">
            <h2 class="text-center mb-4 fw-bold fs-2">Petshops</h2>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php include_once("./php/petshops.php"); ?>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JavaScript e Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
</body>

</html>