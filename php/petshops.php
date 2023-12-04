<?php
$sql = $conn->prepare("SELECT * FROM tb_petshop");
$sql->execute();
$petshops = $sql->fetchAll(PDO::FETCH_ASSOC);
// Exibe os cards para cada petshop
foreach ($petshops as $petshop) {
?>
    <div class="col col-md-3 col-6 mb-4">
        <div class="card petshop-card" data-bs-toggle="modal" data-bs-target="#modalPetshop<?php echo $petshop['id_petshop']; ?>">
            <img src="../../petshopes/php/<?php echo $petshop['img_ps']; ?>" class="card-img-top" style="width: 100%; min-height:50%; background-color: #F27649;" alt="<?php echo "logo" . $petshop['nm_fantasy']; ?>">
            <div class="card-body">
                <h5 class="card-title fs-4"><?php echo $petshop['nm_fantasy']; ?></h5>

                <?php
                $cep = $petshop['cd_ps_cep'];
                $cepInfo = json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"), true);
                // Exibe o bairro se encontrado
                if (isset($cepInfo['bairro'])) {
                ?>
                    <p class="card-text"><strong>Bairro:</strong> <?php echo $cepInfo['bairro']; ?></p>
                <?php
                } else {
                ?>
                    <p class="card-text"><strong>Bairro:</strong> Não disponível</p>
                <?php
                }
                ?>
                <p class="card-text text-success fw-bold">Entrega: R$<?php echo $petshop['vl_delivery']; ?></p>
            </div>
        </div>
    </div>

    <!-- Modal para cada petshop -->
    <div class="modal fade" id="modalPetshop<?php echo $petshop['id_petshop']; ?>" tabindex="-1" aria-labelledby="modalPetshop<?php echo $petshop['id_petshop']; ?>Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-2 fw-bold" style="color: #F27649;" id="modalPetshop<?php echo $petshop['id_petshop']; ?>Label"><?php echo $petshop['nm_fantasy']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded">
                    <!-- Consulta SQL para obter categorias e produtos -->
                    <?php
                    $petshopId = $petshop['id_petshop'];
                    $categoriesQuery = $conn->prepare("SELECT * FROM tb_class WHERE cd_ps = :petshopId");
                    $categoriesQuery->bindParam(':petshopId', $petshopId, PDO::PARAM_INT);
                    $categoriesQuery->execute();
                    $categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($categories as $category) {
                        echo '<h4 class="my-3 fs-3 text-white" style="position: -webkit-sticky;
                        position: sticky;
                        top: 0;
                        background-color: #1190CB;
                         padding: 10px;
                         z-index: 100; border-radius: 5px;
                         ">' . $category['nm_class'] . '</h4>';

                        $categoryId = $category['id_class'];
                        $productsQuery = $conn->prepare("SELECT * FROM tb_product WHERE cd_ps = :petshopId AND cd_class = :categoryId");
                        $productsQuery->bindParam(':petshopId', $petshopId, PDO::PARAM_INT);
                        $productsQuery->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                        $productsQuery->execute();
                        $products = $productsQuery->fetchAll(PDO::FETCH_ASSOC);

                        echo '<div class="row row-cols-1 row-cols-2 row-cols-md-4 g-4">';
                        foreach ($products as $product) {
                    ?>
                            <div class="col">
                                <div class="card text-center">
                                    <img src="../petshopes/php/<?php echo $product['img_pdc']; ?>" class="card-img-top img-fluid img-sm" alt="<?php echo $product['nm_pdc']; ?>">
                                    <div class="card-body">
                                        <p class="card-text text-success fw-bold fs-4">R$ <?php echo $product['vl_pdc']; ?></p>
                                        <h6 class="card-title fs-4 mt-1"><?php echo $product['nm_pdc']; ?></h6>
                                        <p class="card-text"><?php echo $product['ds_pdc']; ?></p>
                                        <button class="btn" style="background-color: #F27649;"><i class="bi bi-plus-circle text-white fs-3"></i></button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}

?>