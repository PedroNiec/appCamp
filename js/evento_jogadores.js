document.addEventListener('DOMContentLoaded', function() {
    // 1. Obter o ID do jogo do campo oculto no HTML
    const jogoIdInput = document.getElementById('jogoIdInput');
    const jogoId = jogoIdInput ? jogoIdInput.value : null;

    if (jogoId) {
        console.log("UUID do Jogo (lido do HTML):", jogoId);
        // 2. Chamar a função para carregar os times assim que a página estiver pronta
        carregarTimes(jogoId);
    } else {
        console.error("Erro: Não foi possível encontrar o ID do jogo no HTML.");
        // Opcional: Desabilitar o select se o ID do jogo não estiver disponível
        const timeSelect = document.getElementById('timeSelect');
        if (timeSelect) {
            timeSelect.innerHTML = '<option>ID do Jogo não encontrado</option>';
            timeSelect.disabled = true;
        }
    }

    /**
     * Função para carregar os times usando o ID do jogo fornecido.
     * @param {string} idDoJogo - O UUID do jogo.
     */
    function carregarTimes(idDoJogo) {
        console.log("Chamando carregarTimes com o UUID:", idDoJogo);
        const timeSelect = document.getElementById('timeSelect');

        // Verificar se o elemento 'timeSelect' existe no DOM
        if (!timeSelect) {
            console.error("Erro: Elemento <select> com ID 'timeSelect' não encontrado no HTML.");
            return; // Sair da função se o elemento não for encontrado
        }

        // Mostrar um estado de carregamento inicial
        timeSelect.innerHTML = '<option>Carregando times...</option>';
        timeSelect.disabled = true; // Desabilitar enquanto carrega

        // Fazer a requisição à API para buscar os times
        // A URL da sua API já está usando 'id', que corresponde ao que você está passando
        fetch(`/appCamp/public/api/get_times.php?id=${encodeURIComponent(idDoJogo)}`)
            .then(response => {
                // Verificar se a resposta da rede foi bem-sucedida (status 2xx)
                if (!response.ok) {
                    // Lançar um erro para ser capturado pelo .catch se a resposta não for OK
                    throw new Error(`Erro HTTP: ${response.status} - ${response.statusText}`);
                }
                return response.json(); // Analisar a resposta como JSON
            })
            .then(times => {
                // Limpar o select e adicionar a opção padrão
                timeSelect.innerHTML = '<option value="">Selecione um time</option>';
                timeSelect.disabled = false; // Habilitar o select

                const uniqueTeams = new Set(); // Usar um Set para evitar times duplicados

                // Preencher o select com as equipes
                times.forEach(time => {
                    // Adicionar equipe_a se existir e não for duplicada
                    if (time.equipe_a && !uniqueTeams.has(time.equipe_a)) {
                        uniqueTeams.add(time.equipe_a);
                        const optionA = document.createElement('option');
                        optionA.value = time.equipe_a;
                        optionA.textContent = time.equipe_a;
                        timeSelect.appendChild(optionA);
                    }

                    // Adicionar equipe_b se existir e não for duplicada
                    if (time.equipe_b && !uniqueTeams.has(time.equipe_b)) {
                        uniqueTeams.add(time.equipe_b);
                        const optionB = document.createElement('option');
                        optionB.value = time.equipe_b;
                        optionB.textContent = time.equipe_b;
                        timeSelect.appendChild(optionB);
                    }
                });

                if (uniqueTeams.size === 0) {
                    timeSelect.innerHTML = '<option>Nenhum time encontrado</option>';
                    timeSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar times:', error);
                timeSelect.innerHTML = '<option>Erro ao carregar times</option>';
                timeSelect.disabled = true; // Manter desabilitado em caso de erro
            });
    }

    function carregarJogadores(timeId) {
    const jogadorSelect = document.getElementById('jogadorSelect');
    jogadorSelect.innerHTML = '<option>Carregando...</option>';

    if (timeId) {
        fetch(`/appCamp/public/api/get_jogadores_por_time.php?time_id=${timeId}`)
            .then(response => response.json())
            .then(jogadores => {
                jogadorSelect.innerHTML = '<option value="">Selecione um jogador</option>';
                jogadores.forEach(jogador => {
                    const option = document.createElement('option');
                    option.value = jogador.id;
                    option.textContent = jogador.nome;
                    jogadorSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erro ao buscar jogadores:', error);
                jogadorSelect.innerHTML = '<option>Erro ao carregar</option>';
            });
    } else {
        jogadorSelect.innerHTML = '<option value="">Selecione um jogador</option>';
    }
}
});



// === Função para impedir valores negativos no input de gols ===
function impedirValorNegativo() {
    const input = document.getElementById('gols');
    if (!input) return;

    input.addEventListener('input', function () {
        if (parseInt(this.value) < 0) {
            this.value = 0;
        }
    });
}

// === Função principal que será executada ao carregar a página ===
function inicializarPagina() {
    carregarTimes();
    impedirValorNegativo();

    const timeSelect = document.getElementById('timeSelect');
    if (timeSelect) {
        timeSelect.addEventListener('change', function () {
            const timeId = this.value;
            carregarJogadores(timeId);
        });
    }
}

// === Inicia o script quando a página terminar de carregar ===
document.addEventListener('DOMContentLoaded', inicializarPagina);
