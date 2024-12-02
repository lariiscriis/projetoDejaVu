<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/deja_vu 1.svg" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/adm.css">
    <title>- Administrador -</title>

</head>
<body>
<!-- nav -->
<header id="home">
    <div class="top">
        <div class="container">
            <ul class="lang">
                <div class="sociais">
                <li><a href="#"><img src="assets/images/redes1.svg"></a></li>
                <li><a href="#"><img src="assets/images/redes2.svg"></a></li>
                <li><a href="#"><img src="assets/images/redes3.svg"></a></li>
            </div> 
                <div class="barra_pesquisa">
                  <li>   
                <input type="search" class="input_pesquisa"placeholder="Pesquise o seu vinil favorito...">
                <button type="submit" class="botao_pesquisa"><img src="assets/images/pesquisa_img.svg" alt=""></button>
                </li>
                 </div>
            </ul>
            <div class="botao_container">
            <ul class="botoes_user">
                <li> <a href="paginLoginCadastro.php"><button href="#" class="perfil_usuario"><img src="assets/images/minha conta.svg" ><p>Minha Conta</p></button></li></a>
                <li> <button href="#" class="carrinho_compras"><img src="assets/images/minhas_compras.svg" ><p>Minhas Compras</p></button></li>
            </ul>
            </div>
        </div>
    </div>
    <nav>
        <div class="container_nav">
            <a href="" class="logo">
                <img src="assets/images/deja_vu 1.svg" >
            </a>
            <div class="menu-btn">
                <img src="assets/images/barraDeMenu.svg" class="menuHamburguer" onclick="menuShow()"></label>
            </div>
            <ul class="menuLista" >
                <li><a href="index.php#home" onclick="menuShow()">Home</a></li>
                <li><a href="index.php#sobre-nos" onclick="menuShow()">Sobre </a></li>
                <li><a href="index.php#contato" class="especial2" onclick="menuShow()">Contato</a></li>
                <li><a href="index.php#cards_generosMusicas" class="especial" onclick="menuShow()">Gêneros</a></li> 
                <li><a href="artistas.php" onclick="menuShow()">Artistas</a></li>
                <li><a href="vinis_produtos.php" onclick="menuShow()">Vinis</a></li>
            </ul>
        </div>
    </nav>
</header>


<div class="table-container">
    <h2>Gerenciar Vinis</h2>
    <a href="cadastrarVinis.php"> <button class="btn" id="btnCadastrar">Cadastrar Novo Vinil</button></a><br>
    <form action="destroy.php" method="POST">
        <button type="submit" class="btn-logout">Sair</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Gênero</th>
                <th>Artista</th>
                <th>Data de Lançamento</th>
                <th>Estoque</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'conexao.php';

            // Consulta os vinis
            $sql = "
                SELECT v.im_vinil, v.nm_titulo_vinil, v.cd_vinil, a.nm_artista, v.dt_lancamento_vinil, v.qt_estoque_vinil, v.vl_preco_unitario_vinil 
                FROM vinil AS v 
                JOIN vinil_artista AS va ON v.cd_vinil = va.cd_vinil 
                JOIN artista AS a ON va.cd_artista = a.cd_artista
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $vinis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($vinis as $vinil): 
            ?>
                <tr>
                    <td><img src="<?= $vinil['im_vinil']; ?>" alt="Vinil" style="width: 50px; height: 50px;"></td>
                    <td><?= $vinil['nm_titulo_vinil']; ?></td>
                    <td><?= $vinil['cd_vinil']; ?></td>
                    <td><?= $vinil['nm_artista']; ?></td>
                    <td><?= $vinil['dt_lancamento_vinil']; ?></td>
                    <td><?= $vinil['qt_estoque_vinil']; ?></td>
                    <td>R$ <?= number_format($vinil['vl_preco_unitario_vinil'], 2, ',', '.'); ?></td>
                    <td>
                    <a href="updateVinil.php?vinilId=<?= $vinil['cd_vinil']; ?>">
                     <button class="btnEditar" id="editarVinil">Editar</button>
                         </a>
                         <a href="excluirVinil.php?vinilId=<?= $vinil['cd_vinil']; ?>" onclick="return confirm('Tem certeza que deseja excluir este vinil?')">
                             <button class="btnExcluir">Excluir</button>
                                </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Modal de Edição, comentário: Larissa -->
<?php
if (isset($_GET['vinilId'])) {
    // Buscar os dados do vinil no banco para preencher os campos do formulário
    $vinilId = $_GET['vinilId'];
    
    // Conectar ao banco e buscar as informações do vinil
    $sqlVinil = "SELECT * FROM vinis WHERE id_vinil = :vinilId";
    $stmt = $pdo->prepare($sqlVinil);
    $stmt->bindParam(':vinilId', $vinilId);
    $stmt->execute();
    $vinil = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!-- Modal de Edição -->
<div class="modal">
    <div class="modal-content">
        <span class="close">
            <a href="vinis_produtos.php">X</a>
        </span>
        <h3>Editar Vinil</h3>
        
        <form action="editar_vinil.php" method="POST">
            <input type="hidden" name="vinilId" value="<?= $vinil['id_vinil'] ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($vinil['nome']) ?>" required>

            <label for="data_lancamento">Data de Lançamento:</label>
            <input type="text" id="data_lancamento" name="data_lancamento" value="<?= htmlspecialchars($vinil['data_lancamento']) ?>" required>

            <label for="artista">Artista:</label>
            <input type="text" id="artista" name="artista" value="<?= htmlspecialchars($vinil['artista']) ?>" required>

            <label for="estoque">Estoque:</label>
            <input type="number" id="estoque" name="estoque" value="<?= htmlspecialchars($vinil['estoque']) ?>" required>

            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($vinil['preco']) ?>" required>

            <button type="submit" class="btnSalvar">Salvar Edição</button>
        </form>
    </div>
</div>
    <script src="js/nav.js"></script>
<script src="js/admModal.js"></script>

</body>
</html>