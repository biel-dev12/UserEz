<?php
    $sql = $conn->prepare("SELECT * FROM tb_petshop");
    $sql->execute();
    $petshops = $sql->fetchAll(PDO::FETCH_ASSOC);
    // Exibe os cards para cada petshop
    foreach ($petshops as $petshop) {
        ?>
        <div class="col col-md-3 col-6 mb-4">
            <div class="card petshop-card" data-bs-toggle="modal" data-bs-target="#modalPetshop<?php echo $petshop['id_petshop'];?>">
                <img src="../../PetshopEz/php/img//profile/<?php echo $petshop['img_ps']; ?>" class="card-img-top w-10 h-10" alt="<?php echo "logo". $petshop['nm_fantasy']; ?>">
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPetshop<?php echo $petshop['id_petshop']; ?>Label"><?php echo $petshop['nm_fantasy']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo $petshop['detalhes']; ?></p>
                        <!-- Adicione mais informações conforme necessário -->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

?>