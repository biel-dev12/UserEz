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

//funcao de carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionarAoCarrinho'])) {
    // Verifica se os campos necessários estão definidos no POST
    if (isset($_POST['produtoId'])) {
        $produtoId = $_POST['produtoId'];

        // Inicializa a quantidade padrão
        $quantidade = 1;

        // Verifica se o produto já está no carrinho
        $produtoNoCarrinho = false;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['produtoId'] == $produtoId) {
                    // Se o produto já está no carrinho, incrementa a quantidade
                    $item['quantidade'] += $quantidade;
                    $produtoNoCarrinho = true;
                    break;
                }
            }
        }

        // Se o produto não estava no carrinho, adiciona como um novo item
        if (!$produtoNoCarrinho) {
            // Adiciona o item ao carrinho
            $_SESSION['carrinho'][] = [
                'produtoId' => $produtoId,
                'quantidade' => $quantidade,
                // Adicione outros detalhes do produto conforme necessário
            ];
        }

        // Redireciona de volta para a mesma página
    ?>
        <script>
            location.href = "./main.php";
        </script>
        <?php
        exit();
    }
}

//funcao de endereco
$emailUsuario = $_SESSION['emailE'];
// Substitua os nomes das colunas e tabelas pelos reais do seu banco de dados
$query = "SELECT tb_user.*, tb_user_address.* FROM tb_user
          LEFT JOIN tb_user_address ON tb_user.id_user = tb_user_address.cd_user_id
          WHERE tb_user.nm_user_email = :email";
try {
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $emailUsuario);
    $stmt->execute();
    // Obtém os resultados como um array associativo
    $informacoesUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $enderecoAtual = [
        'rua' => $informacoesUsuario['nm_street'],
        'numero' => $informacoesUsuario['ds_num']
    ];
    if ($_POST && isset($_POST['salvarEndereco'])) {
        // Verifica se os campos necessários estão definidos no POST
        if (isset($_POST['editCep'], $_POST['editRua'], $_POST['editCidade'], $_POST['editBairro'], $_POST['editEstado'], $_POST['editNumero'], $_POST['editComplemento'])) {
            $cep = $_POST['editCep'];
            $rua = $_POST['editRua'];
            $cidade = $_POST['editCidade'];
            $bairro = $_POST['editBairro'];
            $estado = $_POST['editEstado'];
            $numero = $_POST['editNumero'];
            $complemento = $_POST['editComplemento'];

            $updateQuery = "UPDATE tb_user_address SET cd_cep_user = :cep, nm_street = :rua, nm_city = :cidade, nm_neighbor = :bairro, uf_user = :estado, ds_num = :numero, ds_complemento = :complemento WHERE cd_user_id = :userId";
            try {
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':cep', $cep);
                $updateStmt->bindParam(':rua', $rua);
                $updateStmt->bindParam(':cidade', $cidade);
                $updateStmt->bindParam(':bairro', $bairro);
                $updateStmt->bindParam(':estado', $estado);
                $updateStmt->bindParam(':numero', $numero);
                $updateStmt->bindParam(':complemento', $complemento);
                $updateStmt->bindParam(':userId', $informacoesUsuario['id_user']);
                $updateStmt->execute();
                $enderecoAtual = [
                    'rua' => $rua,
                    'numero' => $numero
                ];
        ?>
                <script>
                    location.href = "main.php";
                </script>
<?php
            } catch (PDOException $e) {
                echo "Erro ao atualizar o endereço: " . $e->getMessage();
            }
        }
    }
} catch (PDOException $e) {
    echo "Erro ao buscar informações do usuário: " . $e->getMessage();
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
    <link rel="stylesheet" href="./css/style4.css">
    <title>Home - EzPets</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary px-3 pt-3 d-flex flex-column" style="overflow-y: hidden;">
        <div class="container-fluid cont-nav" style="gap: 1rem;">
            <a class="navbar-brand d-flex" href="#" style="width: 7rem;"><img src="./imgs/logo-ezpets.svg" alt="Logo EzPts" style="width: 100%; height: 100%;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="mb-2" role="search">
                    <input class="form-control" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success border" type="submit" id="btn-search"><i class="bi bi-search"></i></button>
                </form>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item fs-5 fw-bold" data-bs-toggle="modal" data-bs-target="#editAddressModal" style="cursor:pointer;">
                        <?php
                        if ($enderecoAtual['rua'] == "-" && $enderecoAtual['numero'] == "0") {
                            echo 'Clique para adicionar endereço';
                        } else {
                            echo $enderecoAtual['rua'] . ', ' . $enderecoAtual['numero'];
                        }
                        ?>
                    </li>
                    <li class="nav-item car">
                        <button class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#carrinhoModal">
                            <i class="bi bi-cart2"></i>
                        </button>
                    </li>


                    <li class="nav-item profile">
                        <button class="nav-link" href="#"><i class="bi bi-person-circle"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal para exibir o carrinho -->
<div class="modal fade" id="carrinhoModal" tabindex="-1" aria-labelledby="carrinhoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carrinhoModalLabel">Seu Carrinho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Seção para exibir os produtos no carrinho -->
                <div id="carrinhoProdutos">
                    <div class="row row-cols-1 row-cols-2 row-cols-md-4 g-4">

                        <?php
                        if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
                            $subtotal = 0;
                            foreach ($_SESSION['carrinho'] as $item) {
                                // Realize um SELECT no banco de dados para obter informações detalhadas do produto
                                $produtoId = $item['produtoId']; // Substitua por uma variável que represente o ID do produto
                                $produtoQuery = $conn->prepare("SELECT * FROM tb_product WHERE id_pdc = :produtoId");
                                $produtoQuery->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);
                                $produtoQuery->execute();
                                $produto = $produtoQuery->fetch(PDO::FETCH_ASSOC);
                                if ($produto) {
                                    $petshopId = $produto["cd_ps"];
                                    $petshopQuery = $conn->prepare("SELECT vl_delivery FROM tb_petshop WHERE id_petshop = :petshopId");
                                    $petshopQuery->bindParam(':petshopId', $petshopId, PDO::PARAM_INT);
                                    $petshopQuery->execute();
                                    $petshopInfo = $petshopQuery->fetch(PDO::FETCH_ASSOC);
                                    $taxaEntrega = isset($petshopInfo['vl_delivery']) ? $petshopInfo['vl_delivery'] : 0;
                                    $_SESSION["vl_dlv"] = $taxaEntrega;
                                    // Exiba as informações detalhadas do produto em um card
                        ?>
                                    <div class="col">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <img src="../PetshopEz/php/<?php echo $produto['img_pdc']; ?>" class="card-img-left img-fluid img-sm" style="width: 70%;" alt="<?php echo $produto['nm_pdc']; ?>">
                                                <h5 class="card-title mt-1"><?php echo $produto['nm_pdc']; ?></h5>
                                                <p class="card-text">Quantidade: <?php echo $item['quantidade']; ?></p>
                                                <p class="card-text">Valor: R$ <?php echo $produto['vl_pdc']; ?></p>
                                                <!-- Adicione outros detalhes do produto conforme necessário -->
                                            </div>
                                        </div>
                                    </div>

                        <?php
                                    $subtotal += $produto['vl_pdc'] * $item['quantidade'];
                                }
                            }
                            $total = $subtotal + $_SESSION['vl_dlv'];
                            echo "</div>";
                        } else {
                            echo '<p>Seu carrinho está vazio.</p>';
                        }
                        ?>
                    </div>
                </div>
            <!-- </div> -->
            <!--<div class="modal-footer d-flex p-3" style="justify-content: space-between;"> -->
                <form action="main.php" method="post">
                    <!-- Opções de pagamento -->
                    <div class="d-flex flex-column">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcaoPagamento" id="opcao1" value="dinheiro">
                            <label class="form-check-label" for="opcao1">Dinheiro</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcaoPagamento" id="opcao2" value="cartao">
                            <label class="form-check-label" for="opcao2">Cartão(débito / crédito)</label>
                        </div>
                    </div>
                    <!-- Subtotal, taxa de entrega e total -->
                    <div id="carrinhoFooter">
                        <p>Subtotal: R$ <?php echo number_format($subtotal, 2, ',', ''); ?></p>
                        <p>Taxa de Entrega: R$ <?php echo $_SESSION["vl_dlv"]; ?></p>
                        <h4>Total: R$ <?php echo number_format($total, 2, ',', ''); ?></h4>
                        <button type="submit" name="enviarPedido" class="btn btn-primary">Enviar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php
            // Se o formulário de pedido for enviado
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviarPedido'])) {
                // Verifique se há itens no carrinho
                if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
                    // Iniciar uma transação para garantir consistência nos dados
                    $conn->beginTransaction();

                    try {
                        // Inserir dados na tabela tb_order
                        $dataPedido = date('Y-m-d H:i:s'); // Data e hora atual
                        $horaPedido = date('H:i:s');
                        $userId = $informacoesUsuario['id_user'];
                        $statusP = "Pendente";
                        // Obtendo a petshop com base nos produtos do carrinho
                        $petshopId = obterPetshopIdDoCarrinho($_SESSION['carrinho'], $conn);

                        // Inserir dados na tabela tb_order
                        $insertOrderQuery = "INSERT INTO tb_order (dt_us_order, hr_order, vl_order_subtotal, cd_petshop, cd_user, ds_status, ds_payment) VALUES (:dataPedido, :horaPedido, :subtotal, :petshopId, :userId, :ds_status, :paymentMethod)";
                        $insertOrderStmt = $conn->prepare($insertOrderQuery);
                        $paymentMethod = isset($_POST['opcaoPagamento']) ? $_POST['opcaoPagamento'] : 'Nao selecionado';
                        $insertOrderStmt->bindParam(':dataPedido', $dataPedido);
                        $insertOrderStmt->bindParam(':horaPedido', $horaPedido);
                        $insertOrderStmt->bindParam(':subtotal', $subtotal);
                        $insertOrderStmt->bindParam(':petshopId', $petshopId);
                        $insertOrderStmt->bindParam(':userId', $userId);
                        $insertOrderStmt->bindParam(':ds_status', $statusP);
                        $insertOrderStmt->bindParam(':paymentMethod', $paymentMethod);
                        $insertOrderStmt->execute();

                        // Obter o ID da última ordem inserida
                        $orderId = $conn->lastInsertId();

                        // Inserir itens na tabela tb_item
                        $insertItemQuery = "INSERT INTO tb_item (qt_item, cd_pdc, cd_order) VALUES (:quantity, :productId, :orderId)";
                        $insertItemStmt = $conn->prepare($insertItemQuery);

                        foreach ($_SESSION['carrinho'] as $item) {
                            $productId = $item['produtoId'];
                            $quantity = $item['quantidade'];

                            $insertItemStmt->bindParam(':quantity', $quantity);
                            $insertItemStmt->bindParam(':productId', $productId);
                            $insertItemStmt->bindParam(':orderId', $orderId);

                            $insertItemStmt->execute();
                        }

                        // Commit da transação se tudo ocorrer bem
                        $conn->commit();

                        // Limpar carrinho após o pedido ser concluído
                        unset($_SESSION['carrinho']);
                    } catch (PDOException $e) {
                        // Se ocorrer um erro, faça o rollback da transação
                        $conn->rollBack();
                        echo "Erro ao enviar pedido: " . $e->getMessage();
                    }
                } else {
                    echo "Seu carrinho está vazio.";
                }
            }

            // Função para obter o ID da petshop com base nos produtos do carrinho
            function obterPetshopIdDoCarrinho($carrinho, $conn)
            {
                $productIdList = array_column($carrinho, 'produtoId');
                $placeholders = implode(',', array_fill(0, count($productIdList), '?'));

                $query = "SELECT DISTINCT p.cd_ps
          FROM tb_product p
          WHERE p.id_pdc IN ($placeholders)";

                $stmt = $conn->prepare($query);
                $stmt->execute($productIdList);

                // Aqui, você pode ajustar a lógica se houver mais de uma petshop associada aos produtos
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['cd_ps'];
                }

                // Retorne um valor padrão ou lide com a situação de outra forma
                return null;
            }
            ?>

    <!-- Modal de Adição/Edição de Endereço -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressModalLabel">Adicionar/Editar Endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de Adição/Edição de Endereço -->
                    <form id="editAddressForm" method="post" action="#">
                        <div class="mb-3">
                            <label for="editCep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="editCep" name="editCep" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRua" class="form-label">Rua</label>
                            <input type="text" class="form-control" id="editRua" name="editRua" readonly desabled>
                        </div>
                        <div class="mb-3">
                            <label for="editCidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="editCidade" name="editCidade" desabled readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editBairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="editBairro" name="editBairro" desabled readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="editEstado" name="editEstado" desabled readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editNumero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="editNumero" name="editNumero" required>
                        </div>
                        <div class="mb-3">
                            <label for="editComplemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="editComplemento" name="editComplemento">
                        </div>
                        <button type="submit" name="salvarEndereco" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <script>
        $(document).ready(function() {
            // Quando o campo de CEP fr preenchido
            $("#editCep").on("blur", function() {
                // Exemplo: Realiza uma requisição AJAX para buscar os dados do CEP via API Via CEP
                var cep = $(this).val();
                $.ajax({
                    url: 'https://viacep.com.br/ws/' + cep + '/json/',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Preenche os campos automaticamente
                        $("#editRua").val(data.logradouro);
                        $("#editCidade").val(data.localidade);
                        $("#editBairro").val(data.bairro);
                        $("#editEstado").val(data.uf);
                    }
                });
            });
        });
    </script>
</body>

</html>