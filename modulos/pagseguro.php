<div class="tab-pane fade show active" id="pagseguro" role="tabpanel" aria-labelledby="pagseguro-tab">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">PAGSEGURO CÁLCULO</h6>
        </div>
        <div class="card-body">
            <form id="pagseguro-calculo-form">
                <div class="form-group">
                    <label for="valor-item-pagseguro">Valor do Item:</label>
                    <input type="number" class="form-control" id="valor-item-pagseguro" placeholder="R$ 0,00">
                </div>
                <div class="form-group text-center">
                    <img src="assets/img/pagseguro.png" alt="Logo do PagSeguro" id="pagseguro-logo" style="max-width: 250px; margin-top: 10px;">
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
                <tbody id="pagseguro-calculo-result">
                    <!-- Resultados aparecerão aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function calculatePagSeguro(juros) {
    const valorItem = parseFloat(document.getElementById('valor-item-pagseguro').value) || 0;
    const resultContainer = document.getElementById('pagseguro-calculo-result');
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

function loadJurosPagSeguro() {
    fetch('juros_pagseguro.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-item-pagseguro').addEventListener('input', function() {
                calculatePagSeguro(data);
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    loadJurosPagSeguro();
});
</script>
