document.addEventListener('DOMContentLoaded', function() {
    const feedContainer = document.getElementById('activity-feed');
    const baseUrlData = document.getElementById('base-url-data');

    if (!feedContainer || !baseUrlData) {
        return;
    }
    
    const BASE_URL = baseUrlData.dataset.url;

    feedContainer.addEventListener('click', function(e) {
        const likeButton = e.target.closest('.like-btn');
        if (!likeButton) return;

        e.preventDefault();

        const activityCard = likeButton.closest('.activity-card');
        const activityId = activityCard.dataset.activityId;

        if (!activityId) return;

        fetch(`${BASE_URL}/index.php?controller=like&action=toggleLike`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ activityId: activityId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Falha na resposta do servidor.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const likeCountSpan = likeButton.querySelector('.like-count');
                likeCountSpan.textContent = data.newLikeCount;

                const likeIcon = likeButton.querySelector('img');
                if (data.wasLiked) {
                    likeButton.classList.add('liked');
                } else {
                    likeButton.classList.remove('liked');
                }
            } else {
                console.error('Erro ao curtir:', data.error);
                if (data.error === 'Usuário não autenticado.') {
                    alert('Você precisa estar logado para curtir uma atividade.');
                }
            }
        })
        .catch(error => console.error('Erro na requisição de like:', error));
    });
});
