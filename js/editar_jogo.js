document.getElementById('timeSelect').addEventListener('change', function () {
    const timeId = this.value;
    const jogadorSelect = document.getElementById('jogadorSelect');

    jogadorSelect.innerHTML = '<option>Carregando...</option>';

    if (timeId) {
        fetch('/appCamp/public/api/get_jogadores_por_time.php?time_id=' + timeId)
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

    function timeSelect() {
    const timeSelect = document.getElementById('timeSelect');
    timeSelect.innerHTML = '<option>Carregando...</option>';

    fetch('/appCamp/public/api/get_times.php')
        .then(response => response.json())
        .then(times => {
            timeSelect.innerHTML = '<option value="">Selecione um time</option>';
            times.forEach(time => {
                const option = document.createElement('option');
                option.value = time.id;
                option.textContent = time.nome;
                timeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar times:', error);
            timeSelect.innerHTML = '<option>Erro ao carregar</option>';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
    timeSelect();
});

});