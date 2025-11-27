<aside class="sidebar">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-info">
            <?php
            $userAvatarPath = BASE_URL . '/Images/Imagens-perfil/' . htmlspecialchars($_SESSION['user_avatar']);
            ?>
            <img src="<?php echo $userAvatarPath; ?>" alt="Avatar do Usuário" class="profile-avatar">
            
            <h3><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $userStats['total_atividades'] ?? 0; ?></span>
                    <span>Qtd. Atividades</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $userStats['total_calorias'] ?? 0; ?></span>
                    <span>Qtd. Calorias</span>
                </div>
            </div>
        </div>

        <a href="<?php echo BASE_URL; ?>/index.php?action=showCreateActivityPage" class="btn-create-activity">Criar Atividade</a>

    <?php else: ?>
        <div class="profile-info">
            <img src="<?php echo BASE_URL; ?>/Images/Logo/SAEPSaude.png" alt="Logo SAEP Saúde" class="profile-avatar">
            <h3>SAEPSaúde</h3>
        </div>

        <a href="#" class="btn-create-activity disabled">Criar Atividade</a>

    <?php endif; ?>

    <footer class="sidebar-footer">
        <div class="footer-logo">SAEPSaúde</div>
        <div class="social-icons">
            <a href="#"><img src="<?php echo BASE_URL; ?>/Images/Icones/instagram.svg" alt="Instagram"></a>
            <a href="#"><img src="<?php echo BASE_URL; ?>/Images/Icones/twitter.svg" alt="Twitter"></a>
            <a href="#"><img src="<?php echo BASE_URL; ?>/Images/Icones/tiktok.svg" alt="TikTok"></a>
        </div>
        <p class="copyright">Copyright - 2025/2026</p>
    </footer>
</aside>
