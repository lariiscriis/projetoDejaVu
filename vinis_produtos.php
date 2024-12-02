<?php
session_start(); // Inicia a sessão
require 'conexao.php'; // Conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/deja_vu 1.svg" type="image/x-icon">
    <link rel="stylesheet" href="css/vinisProdutos.css"> 
    <title>- Vinis -</title>
</head>
<body>

    <header>
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
                    <li> <a href="minhaConta.php"><button href="#" class="perfil_usuario"><img src="assets/images/minha conta.svg" ><p>Minha Conta</p></button></li></a>
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#sobre-nos">Sobre </a></li>
                    <li><a href="index.php#contato" class="especial2">Contato</a></li>
                    <li><a href="index.php#cards_generosMusicas" class="especial" onclick="menuShow()">Gêneros</a></li> 
                    <li><a href="artistas.php">Artistas</a></li>
                    <li><a href="vinis_produtos.php">Vinis</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!--Filtros para pesquisa, comentário Larissa-->
<div class="containerVinis">
   <div class="fundo">
    <div class="filtros">
        <h3>Filtros</h3>
        <h4>Gênero</h4>
        <input type="checkbox" id="pop" value="pop">
        <label for="pop" class="labelfiltro">Pop</label><br>
        <input type="checkbox" id="rock" value="rock">
        <label for="rock" class="labelfiltro">Rock</label><br>
        <input type="checkbox" id="hiphop" value="hiphop">
        <label for="pop" class="labelfiltro">Hiphop</label><br>
        <input type="checkbox" id="classica" value="classica">
        <label for="classica" class="labelfiltro">Clássica</label><br>

     

        <h4>Preço</h4>
        <input type="range" name="preco" value="0" min="0" max="500"
            oninput="display.value=value" onchange="display.value=value">
            <div class="preco-display">
                <span>R$</span>
                <input type="text" id="display" value="0" readonly>
            </div>
            <button id="filtrar">Aplicar Filtros</button>
        </div>
    </div>

        <section class="Vinis" >
                <!-- <h2>Mais Populares</h2> -->
        <div class="produto-cards" style="margin-top: 4em;">

<!-- foreach começa aqui -->
            <?php
            $itens_por_pagina = 6;

            $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

            $deslocamento = ($pagina_atual - 1) * $itens_por_pagina;

            try {
                $sql_contagem = "SELECT COUNT(*) FROM vinil";
                $stmt_contagem = $pdo->prepare($sql_contagem);
                $stmt_contagem->execute();
                $total_itens = $stmt_contagem->fetchColumn();
                $total_paginas = ceil($total_itens / $itens_por_pagina);

                $sql = "SELECT vinil.*, artista.nm_artista 
                        FROM vinil
                        JOIN vinil_artista ON vinil.cd_vinil = vinil_artista.cd_vinil
                        JOIN artista ON vinil_artista.cd_artista = artista.cd_artista
                        LIMIT :limite OFFSET :deslocamento";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':limite', $itens_por_pagina, PDO::PARAM_INT);
                $stmt->bindParam(':deslocamento', $deslocamento, PDO::PARAM_INT);
                $stmt->execute();
                $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($produtos as $produto) {
                    echo "
                <div class='produto-card'>
                    <div class='produto-imagem'>
                    <img src='" . $produto['im_vinil'] . "'      
                    </div>
                    <div class='produto-detalhes'>
                        <h3 class='produto-nome'>" . $produto['nm_titulo_vinil'] . "</h3>
                        <p class='produto-descricao'>" . $produto['ds_informacoes_vinil'] . "</p>
                        <p class='produto-categoria'><strong>Categoria:</strong><span class='cat'> " . $produto['cd_vinil'] . "</span></p>

                        <p class='produto-artista'><strong>Artista:</strong><span class='art'> " . $produto['nm_artista'] . "</span>

                                    <p class='produto-preco'><strong> R$" . $produto['vl_preco_unitario_vinil'] . ",00</strong></p>

                                <div class='produto-avaliacao'>
                                    <img src='assets/images/estrela_generos.svg' alt='Estrela cheia'>
                                    <img src='assets/images/estrela_generos.svg'  alt='Estrela cheia'>
                                    <img src='assets/images/estrela_generos.svg'  alt='Estrela cheia'>
                                    <img src='assets/images/estrela_generos.svg'  alt='Estrela cheia'>
                                    <img src='assets/images/estrela_generos.svg' alt='Estrela cheia'>
                                    <span>(5)</span>
                                </div>
                        <div class='produto-botoes'>
                        <button class='btn-comprar' 
                            id='btn-comprar' 
                                onclick='abrirModal(
                                    \"" . addslashes($produto['nm_titulo_vinil']) . "\", 
                                    \"" . addslashes($produto['vl_preco_unitario_vinil']) . "\", 
                                    \"" . addslashes($produto['ds_informacoes_vinil']) . "\", 
                                    \"" . addslashes($produto['cd_vinil']) . "\", 
                                    \"" . addslashes($produto['nm_artista']) . "\", 
                                    \"" . addslashes($produto['im_vinil']) . "\")'>
                        Adicionar ao Carrinho
                    </button>
                    <button class='btn-favoritar'><img src='assets/images/favoritar.png' alt='Favoritar'></button>
                        </div>
                    </div>
                </div>
            </div>";

        }  } catch (PDOException $e) {
        echo "Erro ao buscar dados: " . $e->getMessage();
    } ?>

</section>



<div class="paginacao" style="margin-top: 4em;">
    <ul>
        <?php if ($pagina_atual > 1): ?>
            <li><a href="?pagina=<?= $pagina_atual - 1 ?>">Anterior</a></li>
        <?php endif; ?>

        <?php if ($pagina_atual < $total_paginas): ?>
            <li><a href="?pagina=<?= $pagina_atual + 1 ?>">Próxima</a></li>
        <?php endif; ?>
    </ul>
</div>



<!-- Modal -->
<div id="modal" class="modal">
    <div class="conteudo-modal">
    <div class="modal-esquerda">
            <img id="imagem-modal" src="" alt="Vinil" class="imagem-modal">
        </div>
        <div class="modal-direita">
        <span class="close" id="close" onclick="fecharModal()"><img src="assets/images/cancelar.svg" alt=""></span>

            <div class="nome-preco-container">
            <h2 id="nome-modal"></h2>
            <p><span id="preco-modal"></span></p>
            </div>

            <p><span id="descricao-modal"></span></p>
            
            <div class="info-container">
            <p class="nome_l"> <strong>Categoria:</strong> <span id="categoria-modal"></span></p>
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" min="1" value="1">        
            </div>

            <div class="quantidade-container">
            <p class="nome_l"><strong>Artista:</strong> <span id="artista-modal"></span></p>

            </div>
            <form id="form-carrinho" method="POST" action="carrinho.php">
                <input type="hidden" name="produto_id" id="produto-id"> <!-- ID do produto -->
                <input type="hidden" name="quantidade" id="quantidade-form"> <!-- Quantidade do produto -->
                <button type="submit" id="adicionar-carrinho" class="btn-adicionar-carrinho">Adicionar ao Carrinho</button>
            </form>
            <button id="adicionar-carrinho" class="btn-adicionar-carrinho">Adicionar ao Carrinho</button>
        </div>
    </div>
</div>




<script src="js/nav.js"></script>
<script src="js/modalProdutos.js"></script>



    
</body>
</html>