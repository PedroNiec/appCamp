document.addEventListener('DOMContentLoaded', function () {
    const rodadas = window.RODADAS_DISPONIVEIS;
    let currentIndex = 0;

    const rodadaLabel = document.getElementById('rodadaAtualLabel');
    const prevButton = document.getElementById('prevRodada');
    const nextButton = document.getElementById('nextRodada');
    const cardsContainer = document.getElementById('cardsContainer');
    const cards = Array.from(cardsContainer ? cardsContainer.children : []);

    function updateView() {
        if (!rodadas || rodadas.length === 0) {
            rodadaLabel.textContent = "Nenhuma rodada cadastrada";
            prevButton.disabled = true;
            nextButton.disabled = true;
            cards.forEach(card => card.classList.remove('hidden')); // mostra tudo se não há rodadas
            return;
        }

        const currentRodada = rodadas[currentIndex];
        rodadaLabel.textContent = `Rodada ${currentRodada}`;

        cards.forEach(card => {
            if (card.dataset.rodada === currentRodada) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === rodadas.length - 1;
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateView();
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < rodadas.length - 1) {
            currentIndex++;
            updateView();
        }
    });

    updateView();
});
