document.addEventListener('DOMContentLoaded', function () {
    const jogoIdInput = document.getElementById('jogoIdInput');
    const timeSelect = document.getElementById('timeSelect');
    const jogadorSelect = document.getElementById('jogadorSelect');
    const inputGols = document.getElementById('gols');

    const jogoId = jogoIdInput ? jogoIdInput.value : null;

    if (!jogoId) {
        console.error("Erro: ID do jogo não encontrado.");
        if (timeSelect) {
            timeSelect.innerHTML = '<option>ID do Jogo não encontrado</option>';
            timeSelect.disabled = true;
        }
        return;
    }

    carregarTimes(jogoId);

    if (timeSelect && jogadorSelect) {
        timeSelect.addEventListener('change', function () {
            const timeSelecionado = this.value;
            if (timeSelecionado) {
                carregarJogadoresDoTime(timeSelecionado);
            } else {
                jogadorSelect.innerHTML = '<option>Selecione um time primeiro</option>';
                jogadorSelect.disabled = true;
            }
        });
    }

    if (inputGols) {
        inputGols.addEventListener('input', function () {
            if (parseInt(this.value) < 0) {
                this.value = 0;
            }
        });
    }

    function carregarTimes(idDoJogo) {
        timeSelect.innerHTML = '<option>Carregando times...</option>';
        timeSelect.disabled = true;

        fetch(`/public/api/get_times.php?id=${encodeURIComponent(idDoJogo)}`)
            .then(response => {
                if (!response.ok) throw new Error(`Erro HTTP: ${response.status}`);
                return response.json();
            })
            .then(times => {
                timeSelect.innerHTML = '<option value="">Selecione um time</option>';
                timeSelect.disabled = false;

                const uniqueTeams = new Set();
                times.forEach(time => {
                    if (time.equipe_a && !uniqueTeams.has(time.equipe_a)) {
                        uniqueTeams.add(time.equipe_a);
                        const optionA = document.createElement('option');
                         optionA.value = time.equipe_a;
                            optionA.textContent = time.equipe_a;
                        timeSelect.appendChild(optionA);
                    }

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
                timeSelect.disabled = true;
            });
    }

    function carregarJogadoresDoTime(nomeDoTime) {
        jogadorSelect.innerHTML = '<option>Carregando jogadores...</option>';
        jogadorSelect.disabled = true;

        fetch(`/public/api/get_jogadores_por_time.php?time=${encodeURIComponent(nomeDoTime)}`)
            .then(response => {
                if (!response.ok) throw new Error(`Erro HTTP: ${response.status}`);
                return response.json();
            })
            .then(jogadores => {
                jogadorSelect.innerHTML = '<option value="">Selecione um jogador</option>';
                jogadores.forEach(jogador => {
                    const option = document.createElement('option');
                    option.value = jogador.id;
                    option.textContent = jogador.nome;
                    jogadorSelect.appendChild(option);
                });
                jogadorSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erro ao carregar jogadores:', error);
                jogadorSelect.innerHTML = '<option>Erro ao carregar jogadores</option>';
                jogadorSelect.disabled = true;
            });
    }

    const btnSalvar = document.getElementById('btnSalvar');

    if (btnSalvar) {
        btnSalvar.addEventListener('click', function () {
            const id_jogo = jogoIdInput?.value;
            const nome_time = timeSelect?.value;
            const jogador_id = jogadorSelect?.value;
            const gols = inputGols?.value;
            const gols_sofridos = inputGols?.value;

            // Pode incluir mais campos, se necessário
            const cartao_amarelo = document.getElementById('cartaoAmarelo')?.value || 0;
            const cartao_vermelho = document.getElementById('cartaoVermelho')?.value || 0;

            // Validação simples
            if (!id_jogo || !nome_time || !jogador_id) {
                alert("Preencha todos os campos obrigatórios.");
                return;
            }

            const formData = new FormData();
            formData.append("id_jogo", id_jogo);
            formData.append("nome_time", nome_time);
            formData.append("jogador_id", jogador_id);
            formData.append("gols", gols);
            formData.append("gols_sofridos", gols_sofridos);
            formData.append("cartao_amarelo", cartao_amarelo);
            formData.append("cartao_vermelho", cartao_vermelho);

            fetch("/public/api/update_evento_jogadores.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Alterações salvas com sucesso!");
                    // opcional: resetar campos ou redirecionar
                } else {
                    alert("Erro ao salvar: " + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error("Erro na requisição:", error);
                alert("Erro de comunicação com o servidor.");
            });
        });
    }
});


