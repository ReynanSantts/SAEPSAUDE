document.addEventListener('DOMContentLoaded', function() {
    const baseUrlData = document.getElementById('base-url-data');
    if (!baseUrlData) {
        return;
    }
    const BASE_URL = baseUrlData.dataset.url;

    const tabs = document.querySelectorAll('.activity-tabs .tab');
    const feedContainer = document.getElementById('activity-feed');
    const paginationContainer = document.querySelector('.pagination');
    
    if (!feedContainer || !paginationContainer) {
        return;
    }

    let currentPage = 1;
    let currentType = 'corrida';

    function renderActivities(activities) {
        feedContainer.innerHTML = '';
        if (activities.length === 0) {
            feedContainer.innerHTML = '<p class="empty-feed">Nenhuma atividade encontrada para esta categoria.</p>';
            return;
        }
        activities.forEach(activity => {
            const activityDate = new Date(activity.createdAt);
            const formattedDate = `${activityDate.getHours().toString().padStart(2, '0')}:${activityDate.getMinutes().toString().padStart(2, '0')} - ${activityDate.getDate().toString().padStart(2, '0')}/${(activityDate.getMonth() + 1).toString().padStart(2, '0')}/${activityDate.getFullYear()}`;
            
            const activityCard = `
            <div class="activity-card" data-activity-id="${activity.id}">
                <img src="${BASE_URL}/Images/Imagens-perfil/${activity.usuario_imagem}" alt="Avatar" class="user-avatar">
                
                <div class="activity-header">
                    <div class="user-info">
                        <strong>${activity.nome_usuario}</strong>
                        <span class="activity-type">${activity.tipo_atividade.charAt(0).toUpperCase() + activity.tipo_atividade.slice(1)}</span>
                    </div>
                </div>
                <span class="activity-time">${formattedDate}</span>

                <div class="activity-stats">
                    <span><strong>${(activity.distancia_percorrida / 1000).toFixed(1)}</strong> km  
Distância</span>
                    <span><strong>${activity.duracao_atividade}</strong> min  
Duração</span>
                    <span><strong>${activity.quantidade_calorias}</strong>  
Calorias</span>
                </div>

                <div class="activity-actions">
                    <button class="action-btn like-btn">
                        <img src="${BASE_URL}/Images/Icones/coracao.svg" alt="Curtir"> 
                        <span class="like-count">${activity.like_count}</span>
                    </button>
                    <button class="action-btn comment-btn">
                        <img src="${BASE_URL}/Images/Icones/comentario.svg" alt="Comentar"> 
                        <span class="comment-count">${activity.comment_count}</span>
                    </button>
                </div>
            </div>
            `;
            feedContainer.insertAdjacentHTML('beforeend', activityCard);
        });
    }

    function renderPagination(totalPages, currentPage) {
        paginationContainer.innerHTML = '';
        if (totalPages <= 1) return;

        let paginationHTML = '';
        paginationHTML += `<a href="#" class="page-link ${currentPage === 1 ? 'disabled' : ''}" data-page="${currentPage - 1}">Anterior</a>`;
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<a href="#" class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
        }
        paginationHTML += `<a href="#" class="page-link ${currentPage === totalPages ? 'disabled' : ''}" data-page="${currentPage + 1}">Próximo</a>`;
        paginationContainer.innerHTML = paginationHTML;
    }

    function fetchAndRenderActivities(type, page) {
        fetch(`${BASE_URL}/index.php?controller=activity&action=filter&type=${type}&page=${page}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                renderActivities(data.activities);
                renderPagination(data.pagination.totalPages, data.pagination.currentPage);
                currentPage = data.pagination.currentPage;
                currentType = type;
            })
            .catch(error => {
                console.error('Erro ao buscar atividades:', error);
                feedContainer.innerHTML = '<p class="error-message">Ocorreu um erro ao carregar as atividades.</p>';
            });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.type;
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            fetchAndRenderActivities(type, 1); 
        });
    });

    paginationContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.page-link');
        if (link && !link.classList.contains('disabled') && !link.classList.contains('active')) {
            e.preventDefault();
            const page = parseInt(link.dataset.page, 10);
            fetchAndRenderActivities(currentType, page);
        }
    });

    const initialTab = document.querySelector('.activity-tabs .tab.active');
    if (initialTab) {
        const initialType = initialTab.dataset.type;
        fetchAndRenderActivities(initialType, 1);
    }
});
