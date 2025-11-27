<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'SAEP Saúde'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/Templates/Assets/CSS/Style.css">
</head>
<body>
    <div id="base-url-data" data-url="<?php echo BASE_URL; ?>" style="display: none;"></div>
    <div class="app-container">
        
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <main class="main-content">
            <?php
            if (isset($view_file) && file_exists(__DIR__ . '/' . $view_file)) {
                require_once __DIR__ . '/' . $view_file;
            } else {
                echo "<p>Erro: A view de conteúdo não foi encontrada.</p>";
            }
            ?>
        </main>

        <div id="login-popup" class="popup-overlay" style="display: none;">
            <div class="popup-content">
                <button class="close-popup" id="close-login-popup">&times;</button>
                <h2>Login</h2>
                <form id="login-form" method="POST" action="<?php echo BASE_URL; ?>/index.php?action=login">
                    <?php if (isset($_GET['error']) && $_GET['error'] === 'login_failed'): ?>
                        <p class="error-message">Email ou senha inválidos.</p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn-login">Login</button>
                        <button type="button" class="btn-cancel" id="cancel-login-popup">Cancelar</button>
                    </div>
                </form>
                <p class="register-link">Não tem uma conta? <a href="<?php echo BASE_URL; ?>/index.php?action=showRegisterPage">Cadastre-se</a></p>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/Templates/Assets/JS/popup.js"></script>
    <script src="<?php echo BASE_URL; ?>/Templates/Assets/JS/feed.js"></script>
</body>
</html>
