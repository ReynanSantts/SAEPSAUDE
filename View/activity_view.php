<div class="activity-page-container">

    <header class="main-header">
        <h1>Registrar Nova Atividade</h1>
        <a href="<?php echo BASE_URL; ?>/index.php" class="btn-back">Voltar</a>
    </header>

    <div class="form-container">
        
        <?php if (isset($_GET['error'])): ?>
            <p class="form-message error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <p class="form-message success">Atividade registrada com sucesso!</p>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/index.php?controller=activity&action=create" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_atividade">Tipo da atividade</label>
                    <select id="tipo_atividade" name="tipo_atividade" required>
                        <option value="" disabled selected>Selecione o tipo</option>
                        <option value="corrida">Corrida</option>
                        <option value="caminhada">Caminhada</option>
                        <option value="trilha">Trilha</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="distancia">Distância (em metros)</label>
                    <input type="number" id="distancia" name="distancia" placeholder="Ex: 5000" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="duracao">Duração (em minutos)</label>
                    <input type="number" id="duracao" name="duracao" placeholder="Ex: 30" required>
                </div>
                <div class="form-group">
                    <label for="calorias">Calorias Queimadas</label>
                    <input type="number" id="calorias" name="calorias" placeholder="Ex: 350" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Registrar Atividade</button>
            </div>
        </form>
    </div>

    <div class="user-activities-container">
        <h2>Suas Atividades Recentes</h2>
        <div class="activity-feed-local">
            <?php if (empty($userActivities)): ?>
                <p>Você ainda não registrou nenhuma atividade.</p>
            <?php else: ?>
                <?php foreach ($userActivities as $activity): ?>
                    <div class="activity-card">
                        <img src="<?php echo BASE_URL . '/Images/Imagens-perfil/' . htmlspecialchars($_SESSION['user_avatar']); ?>" alt="Avatar" class="user-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="user-info">
                                    <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                                    <span class="activity-type"><?php echo htmlspecialchars(ucfirst($activity['tipo_atividade'])); ?></span>
                                </div>
                                <span class="activity-time"><?php echo date('H:i - d/m/Y', strtotime($activity['createdAt'])); ?></span>
                            </div>
                            <div class="activity-stats">
                                <span><strong><?php echo number_format($activity['distancia_percorrida'] / 1000, 1, ',', '.'); ?></strong> km</span>
                                <span><strong><?php echo $activity['duracao_atividade']; ?></strong> min</span>
                                <span><strong><?php echo $activity['quantidade_calorias']; ?></strong> Calorias</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>
