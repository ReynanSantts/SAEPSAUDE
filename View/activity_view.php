<?php // View/activity_view.php ?>
<header class="main-header">
    <h1>Crie sua atividade</h1>
    <a href="/index.php?action=logout" class="btn-logout">Logout</a>
</header>

<div class="form-container">
    <form action="/index.php?action=createActivity" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="tipo">Tipo da atividade</label>
                <input type="text" id="tipo" name="tipo" placeholder="Ex: Caminhada" required>
            </div>
            <div class="form-group">
                <label for="distancia">Distância percorrida</label>
                <input type="text" id="distancia" name="distancia" placeholder="Ex: 1000 metros" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="duracao">Duração da atividade</label>
                <input type="text" id="duracao" name="duracao" placeholder="Ex: 120 min" required>
            </div>
            <div class="form-group">
                <label for="calorias">Quantidade de Calorias</label>
                <input type="number" id="calorias" name="calorias" placeholder="Ex: 300" required>
            </div>
        </div>
        <button type="submit" class="btn-create-activity">Criar Atividade</button>
    </form>
</div>

<div class="user-activities">
    <h2>Suas Atividades</h2>
    <div class="activity-card">
        <div class="activity-header">
            <img src="/Images/Imagens-perfil/usuario03.jpg" alt="Avatar" class="user-avatar">
            <div class="user-info">
                <strong>Usuário_03</strong>
                <span class="activity-type">Caminhada</span>
            </div>
            <span class="activity-time">05:30 - 09/07/2024</span>
        </div>
        <div class="activity-stats">
            <span><strong>5 km</strong> Distância</span>
            <span><strong>50 min</strong> Duração</span>
            <span><strong>350</strong> Calorias</span>
        </div>
        <div class="activity-actions">
            <button class="action-btn"><img src="/Images/Icones/coracao.svg" alt="Curtir"> 5</button>
            <button class="action-btn"><img src="/Images/Icones/comentario.svg" alt="Comentar"> 4</button>
        </div>
    </div>
</div>
