class AdicionarEquipe {
    constructor() {
        this.form = document.getElementById('formEquipe');
        this.competicaoSelect = document.getElementById('competicaoSelect');
        this.timeASelect = document.getElementById('timeASelect');
        this.timeBSelect = document.getElementById('timeBSelect');

        this.init();
    }

    async init() {
        await this.carregarCompeticoes();
        await this.carregarTimes(this.timeASelect);
        await this.carregarTimes(this.timeBSelect);

        this.timeASelect.addEventListener('change', () => this.atualizarTimesB());
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    async carregarCompeticoes() {
        try {
            const res = await fetch('/appCamp/public/api/get_competicoes.php')
            const data = await res.json();
            this.competicaoSelect.innerHTML = '<option value="">Selecione</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nome;
                this.competicaoSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erro ao carregar competições:', error);
        }
    }

    async carregarTimes(select) {
        try {
            const res = await fetch('/appCamp/public/api/get_times.php')
            const data = await res.json();
            select.innerHTML = '<option value="">Selecione</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nome;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Erro ao carregar times:', error);
        }
    }

    handleSubmit(event) {
        event.preventDefault();
    
        const formData = {
            competicao_id: this.competicaoSelect.value,
            time_a_id: this.timeASelect.value,
            time_b_id: this.timeBSelect.value,
            rodada: this.form.querySelector('[name="rodada"]').value
        };
    
        // ✅ Validação antes de enviar
        if (!formData.competicao_id || !formData.time_a_id || !formData.time_b_id) {
            alert('Por favor, preencha todos os campos obrigatórios antes de cadastrar o jogo.');
            return;
        }
    
        fetch('/appCamp/public/api/cadastrar_jogo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData),
            credentials: 'include'
        })
        .then(res => res.json())
        .then(data => {
            console.log("Retorno do servidor:", data); // 🩺 DEBUG
            if (data.success) {
                alert(data.message);
                if (this.form) {
                    this.form.reset();
                }
            } else {
                alert('Erro: ' + data.message);
            }
        })
        
        .catch(error => {
            console.error('Erro na requisição:', error); // 🩺 DEBUG
            alert('Erro ao cadastrar jogo.');
        });
        
    }    
}

document.addEventListener('DOMContentLoaded', () => {
    new AdicionarEquipe();
});
