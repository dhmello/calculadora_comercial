<div class="tab-pane fade show active" id="mercadopago" role="tabpanel" aria-labelledby="mercadopago-tab">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">MERCADO PAGO CÁLCULO</h6>
        </div>
        <div class="card-body">
            <form id="mercadopago-calculo-form">
                <div class="form-group">
                    <label for="valor-item-mercadopago">Valor do Item:</label>
                    <input type="number" class="form-control" id="valor-item-mercadopago" placeholder="R$ 0,00">
                </div>
                <div class="form-group text-center">
                    <img src="assets/img/mercadopago.png" alt="Logo do Mercado Pago" id="mercadopago-logo" style="max-width: 250px; margin-top: 10px;">
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
                <tbody id="mercadopago-calculo-result">
                    <!-- Resultados aparecerão aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function calculateMercadoPago(juros) {
    const valorItem = parseFloat(document.getElementById('valor-item-mercadopago').value) || 0;
    const resultContainer = document.getElementById('mercadopago-calculo-result');
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

function loadJurosMercadoPago() {
    fetch('juros_mercadopago.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('valor-item-mercadopago').addEventListener('input', function() {
                calculateMercadoPago(data);
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    loadJurosMercadoPago();
});
</script>
