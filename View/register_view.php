<div class="register-container">
    <h2>Crie sua Conta</h2>
    <p>Junte-se à nossa comunidade e comece a registrar suas atividades!</p>

    <?php if (isset($_GET['error'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form class="register-form" method="POST" action="<?php echo BASE_URL; ?>/index.php?action=registerUser">
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="nome_usuario">Nome de Usuário</label>
            <input type="text" id="nome_usuario" name="nome_usuario" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn-register">Cadastrar</button>
        </div>
    </form>
    <p class="login-link">Já tem uma conta? <a href="<?php echo BASE_URL; ?>/index.php">Faça login</a>.</p>
</div>
