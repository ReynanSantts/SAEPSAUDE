<div class="tabs-container">
    <nav class="activity-tabs">
        <a href="#" class="tab active" data-type="corrida">Corrida</a>
        <a href="#" class="tab" data-type="caminhada">Caminhada</a>
        <a href="#" class="tab" data-type="trilha">Trilha</a>
    </nav>
</div>

<header class="main-header">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo BASE_URL; ?>/index.php?action=logout" class="btn-logout">Logout</a>
    <?php else: ?>
        <button id="open-login-popup" class="btn-login">Login</button>
    <?php endif; ?>
</header>

<div class="feed" id="activity-feed">
    <?php
    if (!empty($atividades)) {
        foreach ($atividades as $atividade) {
            $activityUserAvatarPath = BASE_URL . '/Images/Imagens-perfil/' . htmlspecialchars($atividade['usuario_imagem']);
            echo '
            <div class="activity-card">
                <img src="' . $activityUserAvatarPath . '" alt="Avatar" class="user-avatar">
                
                <div class="activity-header">
                    <div class="user-info">
                        <strong>' . htmlspecialchars($atividade['nome_usuario']) . '</strong>
                        <span class="activity-type">' . htmlspecialchars(ucfirst($atividade['tipo_atividade'])) . '</span>
                    </div>
                </div>
                <span class="activity-time">' . date('H:i - d/m/Y', strtotime($atividade['createdAt'])) . '</span>

                <div class="activity-stats">
                    <span><strong>' . ($atividade['distancia_percorrida'] / 1000) . '</strong> km  
Distância</span>
                    <span><strong>' . $atividade['duracao_atividade'] . '</strong> min  
Duração</span>
                    <span><strong>' . $atividade['quantidade_calorias'] . '</strong>  
Calorias</span>
                </div>

                <div class="activity-actions">
                    <button class="action-btn"><img src="' . BASE_URL . '/Images/Icones/coracao.svg" alt="Curtir"> ' . rand(2, 15) . '</button>
                    <button class="action-btn"><img src="' . BASE_URL . '/Images/Icones/comentario.svg" alt="Comentar"> ' . rand(0, 8) . '</button>
                </div>
            </div>';
        }
    } else {
        echo '<p class="empty-feed">Nenhuma atividade encontrada para esta categoria.</p>';
    }
    ?>
</div>

<footer class="feed-footer">
    <nav class="pagination">
    </nav>
</footer>
