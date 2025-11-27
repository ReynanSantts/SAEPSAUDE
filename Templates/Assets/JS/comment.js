// Templates/Assets/JS/comment.js
document.addEventListener('DOMContentLoaded', function() {
    const feedContainer = document.getElementById('activity-feed');
    const baseUrlData = document.getElementById('base-url-data');

    if (!feedContainer || !baseUrlData) return;
    
    const BASE_URL = baseUrlData.dataset.url;

    // Função para formatar a data do comentário
    function formatCommentDate(dateString) {
        const date = new Date(dateString);
        return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')} - ${date.toLocaleDateString()}`;
    }

    // Função para renderizar um único comentário
    function renderSingleComment(comment) {
        return `
            <div class="comment-item">
                <img src="${BASE_URL}/Images/Imagens-perfil/${comment.usuario_imagem}" alt="Avatar">
                <div class="comment-body">
                    <strong>${comment.nome_usuario}</strong>
                    <span>${comment.comentario}</span>
                </div>
            </div>
        `;
    }

    // Delegação de eventos para o container do feed
    feedContainer.addEventListener('click', async function(e) {
        const commentButton = e.target.closest('.comment-btn');
        if (!commentButton) return;

        e.preventDefault();
        const activityCard = commentButton.closest('.activity-card');
        let commentsSection = activityCard.querySelector('.comments-section');

        // Se a seção de comentários não existe, cria
        if (!commentsSection) {
            commentsSection = document.createElement('div');
            commentsSection.className = 'comments-section';
            commentsSection.innerHTML = `
                <div class="comments-list">Carregando comentários...</div>
                <form class="comment-form">
                    <input type="text" name="commentText" placeholder="Escreva um comentário..." required>
                    <button type="submit">Enviar</button>
                </form>
            `;
            activityCard.appendChild(commentsSection);
        }

        // Alterna a visibilidade
        const isVisible = commentsSection.style.display === 'block';
        commentsSection.style.display = isVisible ? 'none' : 'block';

        // Se for para tornar visível e os comentários ainda não foram carregados
        if (!isVisible) {
            const activityId = activityCard.dataset.activityId;
            const commentsList = commentsSection.querySelector('.comments-list');

            try {
                const response = await fetch(`${BASE_URL}/index.php?controller=comment&action=getComments&activityId=${activityId}`);
                const data = await response.json();

                if (data.success) {
                    commentsList.innerHTML = '';
                    if (data.comments.length > 0) {
                        data.comments.forEach(comment => {
                            commentsList.insertAdjacentHTML('beforeend', renderSingleComment(comment));
                        });
                    } else {
                        commentsList.innerHTML = '<p>Nenhum comentário ainda.</p>';
                    }
                } else {
                    commentsList.innerHTML = '<p>Erro ao carregar comentários.</p>';
                }
            } catch (error) {
                console.error('Fetch error:', error);
                commentsList.innerHTML = '<p>Erro de conexão.</p>';
            }
        }
    });

    // Delegação de evento para o envio do formulário de comentário
    feedContainer.addEventListener('submit', async function(e) {
        if (!e.target.classList.contains('comment-form')) return;

        e.preventDefault();
        const form = e.target;
        const activityCard = form.closest('.activity-card');
        const activityId = activityCard.dataset.activityId;
        const commentInput = form.querySelector('input[name="commentText"]');
        const commentText = commentInput.value.trim();

        if (!commentText) return;

        try {
            const response = await fetch(`${BASE_URL}/index.php?controller=comment&action=addComment`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ activityId, commentText })
            });
            const data = await response.json();

            if (data.success) {
                const commentsList = activityCard.querySelector('.comments-list');
                // Remove a mensagem "Nenhum comentário ainda" se ela existir
                if (commentsList.querySelector('p')) {
                    commentsList.innerHTML = '';
                }
                commentsList.insertAdjacentHTML('beforeend', renderSingleComment(data.comment));
                commentInput.value = ''; // Limpa o campo

                // Atualiza a contagem de comentários no botão
                const commentCountSpan = activityCard.querySelector('.comment-count');
                commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1;

            } else {
                alert(data.error || 'Não foi possível adicionar o comentário.');
            }
        } catch (error) {
            console.error('Submit error:', error);
            alert('Erro de conexão ao enviar comentário.');
        }
    });
});
