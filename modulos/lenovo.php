<div class="tab-pane fade show active" id="lenovo" role="tabpanel" aria-labelledby="lenovo-tab">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">Lenovo CÁLCULO</h6>
        </div>
        <div class="card-body">
            <form id="lenovo-calculo-form">
                <div class="form-group">
                    <label for="valor-item-lenovo">Valor do Item:</label>
                    <input type="number" class="form-control" id="valor-item-lenovo" placeholder="R$ 0,00">
                </div>
                <div class="form-group text-center">
                    <img src="https://p3-ofp.static.pub/fes/cms/2023/03/22/8hjmcte754uauw07ypikjkjtx0m5ib450914.svg" alt="Logo do lenovo" id="lenovo-logo" style="max-width: 250px; margin-top: 10px;">
                </div>
            </form>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Parcelas</th>
                        <th>Valor Total</th>
                        <th>Valor da Parcela</th>
                    </tr>
                </thead>
                <tbody id="lenovo-calculo-result">
                    <!-- Resultados aparecerão aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function calculateLenovo(juros) {
    const valorItem = parseFloat(document.getElementById('valor-item-lenovo').value) || 0;
    const resultContainer = document.getElementById('lenovo-calculo-result');
    resultContainer.innerHTML = '';

    juros.forEach(item => {
        const parcelas = parseInt(item.descricao.replace(/\D/g, '')) || 1; // Extrai o número de parcelas da descrição

        // Calcular o valor total e o valor da parcela
        const valorTotal = valorItem * (1 + item.juros / 100);
        const valorParcela = valorTotal / parcelas;

        resultContainer.innerHTML += `
            <tr>
                <td>${item.descricao}</td>
                <td>R$ ${valorTotal.toFixed(2)}</td>
                <td>R$ ${valorParcela.toFixed(2)}</td>
            </tr>`;
    });
}

function loadJuroslenovo() {
    fetch('juros_lenovo.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-item-lenovo').addEventListener('input', function() {
                calculateLenovo(data);
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    loadJuroslenovo();
});
</script>
