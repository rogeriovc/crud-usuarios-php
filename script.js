// Aguarda o DOM estar completamente carregado
document.addEventListener('DOMContentLoaded', function() {
    initializePageAnimations();
    setupDeleteConfirmation();
    setupRippleEffect();
    setupTableAnimations();
});

/**
 * Inicializa animações da página
 */
function initializePageAnimations() {
    // Animação suave das linhas da tabela ao carregar
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach((row, index) => {
        // Define estado inicial
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        // Aplica animação com delay progressivo
        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 100);
    });
}

/**
 * Configura confirmação aprimorada para exclusão
 */
function setupDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Busca o nome do usuário na linha atual
            const row = this.closest('tr');
            const userName = row.querySelector('td:nth-child(2)').textContent.trim();
            
            // Mensagem de confirmação personalizada
            const confirmMessage = `🗑️ Confirmar Exclusão\n\n` +
                                 `Deseja realmente excluir o usuário "${userName}"?\n\n` +
                                 `⚠️ Esta ação não pode ser desfeita!`;
            
            // Previne a ação se o usuário cancelar
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            
            // Adiciona efeito visual de loading no botão
            addLoadingEffect(this);
        });
    });
}

/**
 * Adiciona efeito de loading ao botão
 */
function addLoadingEffect(button) {
    const originalText = button.innerHTML;
    const loadingText = `<svg class="icon spin" viewBox="0 0 24 24">
                            <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                         </svg> Excluindo...`;
    
    button.innerHTML = loadingText;
    button.style.pointerEvents = 'none';
    
    // Adiciona CSS para animação de rotação
    if (!document.querySelector('#spin-style')) {
        const style = document.createElement('style');
        style.id = 'spin-style';
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Configura efeito ripple nos botões
 */
function setupRippleEffect() {
    const buttons = document.querySelectorAll('.btn, .btn-add-main');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            createRippleEffect(e, this);
        });
    });
}

/**
 * Cria o efeito ripple
 */
function createRippleEffect(e, element) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;
    
    // Configurações do ripple
    ripple.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        position: absolute;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        pointer-events: none;
        animation: ripple-effect 0.6s ease-out;
        z-index: 1;
    `;
    
    element.appendChild(ripple);
    
    // Remove o ripple após a animação
    setTimeout(() => {
        if (ripple.parentNode) {
            ripple.remove();
        }
    }, 600);
}

/**
 * Configura animações da tabela
 */
function setupTableAnimations() {
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        // Efeito hover aprimorado
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.zIndex = '10';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.zIndex = 'auto';
        });
    });
}

/**
 * Utilitário para mostrar notificações
 */
function showNotification(message, type = 'info') {
    // Remove notificação existente se houver
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Estilos da notificação
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
        max-width: 300px;
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove após 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
    
    // Remove ao clicar no botão fechar
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    });
}

/**
 * Adiciona estilos dinâmicos para animações
 */
function addDynamicStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .notification-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        /* Melhoria na acessibilidade */
        .btn:focus-visible,
        .btn-add-main:focus-visible {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }
        
        /* Animação de pulse para botões importantes */
        .btn-add-main.pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
            }
            50% {
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.6);
            }
            100% {
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
            }
        }
    `;
    document.head.appendChild(style);
}

/**
 * Função para destacar o botão "Adicionar" quando a tabela está vazia
 */
function highlightAddButtonIfEmpty() {
    const tableBody = document.querySelector('tbody');
    const addButton = document.querySelector('.btn-add-main');
    
    if (tableBody && addButton) {
        const hasData = tableBody.querySelector('tr td:not([colspan])');
        
        if (!hasData) {
            addButton.classList.add('pulse');
        }
    }
}

/**
 * Função para suavizar transições de página
 */
function smoothPageTransitions() {
    // Intercepta cliques em links para adicionar transição
    const links = document.querySelectorAll('a[href^="form.php"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Adiciona efeito de fade out
            document.body.style.transition = 'opacity 0.3s ease-out';
            document.body.style.opacity = '0.7';
            
            // Navega para a página após um breve delay
            setTimeout(() => {
                window.location.href = this.href;
            }, 150);
        });
    });
}

/**
 * Monitora mudanças na URL para feedback visual
 */
/**
 * Monitora mudanças na URL para feedback visual
 */
function monitorURLChanges() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('deleted')) {
        showNotification('Usuário excluído com sucesso!', 'success');
    }

    if (urlParams.has('added')) {
        showNotification('Usuário adicionado com sucesso!', 'success');
    }

    if (urlParams.has('updated')) {
        showNotification('Usuário atualizado com sucesso!', 'success');
    }
}


// Inicializa funcionalidades adicionais
document.addEventListener('DOMContentLoaded', function() {
    addDynamicStyles();
    highlightAddButtonIfEmpty();
    smoothPageTransitions();
    monitorURLChanges();
});

// Função para performance - debounce
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Busca em tempo real na tabela (se implementada no futuro)
 */
function setupTableSearch() {
    const searchInput = document.querySelector('#search-input');
    
    if (searchInput) {
        const debouncedSearch = debounce(function(searchTerm) {
            filterTableRows(searchTerm);
        }, 300);
        
        searchInput.addEventListener('input', function() {
            debouncedSearch(this.value);
        });
    }
}

/**
 * Filtra linhas da tabela baseado no termo de busca
 */
function filterTableRows(searchTerm) {
    const rows = document.querySelectorAll('tbody tr');
    const term = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const shouldShow = text.includes(term);
        
        row.style.display = shouldShow ? '' : 'none';
        
        if (shouldShow) {
            row.style.animation = 'fadeIn 0.3s ease-in';
        }
    });
}

/**
 * Gerenciamento de temas (modo claro/escuro)
 */
function setupThemeToggle() {
    const themeToggle = document.querySelector('#theme-toggle');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            toggleTheme();
        });
        
        // Carrega tema salvo
        const savedTheme = localStorage.getItem('crud-theme');
        if (savedTheme) {
            document.body.className = savedTheme;
        }
    }
}

/**
 * Alterna entre tema claro e escuro
 */
function toggleTheme() {
    const body = document.body;
    const isDark = body.classList.contains('dark-theme');
    
    if (isDark) {
        body.classList.remove('dark-theme');
        body.classList.add('light-theme');
        localStorage.setItem('crud-theme', 'light-theme');
    } else {
        body.classList.remove('light-theme');
        body.classList.add('dark-theme');
        localStorage.setItem('crud-theme', 'dark-theme');
    }
}

/**
 * Configurações de acessibilidade
 */
function setupAccessibility() {
    // Navegação por teclado aprimorada
    document.addEventListener('keydown', function(e) {
        // ESC para fechar modais ou cancelar ações
        if (e.key === 'Escape') {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => notification.remove());
        }
        
        // Enter para confirmar ações em botões focados
        if (e.key === 'Enter' && e.target.classList.contains('btn')) {
            e.target.click();
        }
    });
    
    // Adiciona indicadores visuais para foco
    document.addEventListener('keyup', function(e) {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
    });
}

/**
 * Validação do lado cliente para formulários
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showNotification('Por favor, corrija os erros no formulário', 'error');
            }
        });
    });
}

/**
 * Valida campos do formulário
 */
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            markFieldAsInvalid(input);
            isValid = false;
        } else {
            markFieldAsValid(input);
        }
        
        // Validação específica para email
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                markFieldAsInvalid(input);
                isValid = false;
            }
        }
        
        // Validação específica para telefone
        if (input.type === 'tel' && input.value) {
            const phoneRegex = /^[\d\s\-\(\)]+$/;
            if (!phoneRegex.test(input.value)) {
                markFieldAsInvalid(input);
                isValid = false;
            }
        }
    });
    
    return isValid;
}

/**
 * Marca campo como inválido
 */
function markFieldAsInvalid(input) {
    input.style.borderColor = '#f44336';
    input.style.boxShadow = '0 0 5px rgba(244, 67, 54, 0.3)';
}

/**
 * Marca campo como válido
 */
function markFieldAsValid(input) {
    input.style.borderColor = '#4CAF50';
    input.style.boxShadow = '0 0 5px rgba(76, 175, 80, 0.3)';
}

/**
 * Lazy loading para melhor performance
 */
function setupLazyLoading() {
    if ('IntersectionObserver' in window) {
        const lazyElements = document.querySelectorAll('.lazy-load');
        
        const lazyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('loaded');
                    lazyObserver.unobserve(entry.target);
                }
            });
        });
        
        lazyElements.forEach(element => {
            lazyObserver.observe(element);
        });
    }
}

/**
 * Sistema de confirmação modal personalizado
 */
function createCustomConfirmModal(message, onConfirm, onCancel) {
    const modal = document.createElement('div');
    modal.className = 'custom-modal-overlay';
    
    modal.innerHTML = `
        <div class="custom-modal">
            <div class="modal-header">
                <h3>Confirmação</h3>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancel" id="modal-cancel">Cancelar</button>
                <button class="btn btn-confirm" id="modal-confirm">Confirmar</button>
            </div>
        </div>
    `;
    
    // Estilos do modal
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    `;
    
    document.body.appendChild(modal);
    
    // Event listeners
    modal.querySelector('#modal-confirm').addEventListener('click', () => {
        modal.remove();
        if (onConfirm) onConfirm();
    });
    
    modal.querySelector('#modal-cancel').addEventListener('click', () => {
        modal.remove();
        if (onCancel) onCancel();
    });
    
    // Fechar ao clicar fora do modal
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
            if (onCancel) onCancel();
        }
    });
    
    // Fechar com ESC
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            modal.remove();
            document.removeEventListener('keydown', escHandler);
            if (onCancel) onCancel();
        }
    });
}

/**
 * Sistema de cache local para melhor performance
 */
const CacheManager = {
    set: (key, data, expiration = 3600000) => { // 1 hora por padrão
        const item = {
            data: data,
            timestamp: Date.now(),
            expiration: expiration
        };
        localStorage.setItem(`crud_cache_${key}`, JSON.stringify(item));
    },
    
    get: (key) => {
        const item = localStorage.getItem(`crud_cache_${key}`);
        if (!item) return null;
        
        const parsedItem = JSON.parse(item);
        const now = Date.now();
        
        if (now - parsedItem.timestamp > parsedItem.expiration) {
            localStorage.removeItem(`crud_cache_${key}`);
            return null;
        }
        
        return parsedItem.data;
    },
    
    clear: () => {
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('crud_cache_')) {
                localStorage.removeItem(key);
            }
        });
    }
};

/**
 * Exporta dados da tabela (CSV, JSON)
 */
function setupDataExport() {
    const exportBtn = document.querySelector('#export-data');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const format = this.dataset.format || 'csv';
            exportTableData(format);
        });
    }
}

/**
 * Exporta dados da tabela
 */
function exportTableData(format = 'csv') {
    const table = document.querySelector('table');
    const rows = Array.from(table.querySelectorAll('tr'));
    
    if (format === 'csv') {
        exportAsCSV(rows);
    } else if (format === 'json') {
        exportAsJSON(rows);
    }
}

/**
 * Exporta como CSV
 */
function exportAsCSV(rows) {
    const csvContent = rows.map(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        return cells.map(cell => {
            const text = cell.textContent.trim();
            return text.includes(',') ? `"${text}"` : text;
        }).join(',');
    }).join('\n');
    
    downloadFile(csvContent, 'usuarios.csv', 'text/csv');
}

/**
 * Exporta como JSON
 */
function exportAsJSON(rows) {
    const headers = Array.from(rows[0].querySelectorAll('th')).map(th => th.textContent.trim());
    const data = [];
    
    for (let i = 1; i < rows.length; i++) {
        const cells = Array.from(rows[i].querySelectorAll('td'));
        const rowData = {};
        
        headers.forEach((header, index) => {
            if (cells[index]) {
                rowData[header] = cells[index].textContent.trim();
            }
        });
        
        data.push(rowData);
    }
    
    downloadFile(JSON.stringify(data, null, 2), 'usuarios.json', 'application/json');
}

/**
 * Baixa arquivo
 */
function downloadFile(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    showNotification(`Arquivo ${filename} baixado com sucesso!`, 'success');
}

// Inicializa todas as funcionalidades quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    setupTableSearch();
    setupThemeToggle();
    setupAccessibility();
    setupFormValidation();
    setupLazyLoading();
    setupDataExport();
    monitorURLChanges();    // <-- chamada para mostrar notificações
});


// Log de inicialização para debug
console.log('🚀 Sistema CRUD inicializado com sucesso!');
console.log('📊 Funcionalidades ativas: Animações, Confirmações, Validações, Cache, Exportação');