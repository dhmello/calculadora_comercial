<div class="tab-pane fade show active" id="mazer" role="tabpanel" aria-labelledby="mazer-tab">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">BOLETO MAZER CÁLCULO</h6>
        </div>
        <div class="card-body">
            <form id="mazer-calculo-form">
                <div class="form-group">
                    <label for="valor-item">Valor do Item:</label>
                    <input type="number" class="form-control" id="valor-item" placeholder="R$ 0,00">
                </div>
                <div class="form-group text-center">
                    <img src="https://www.mazer.com.br/imagem/logo-store" alt="Logo do mazer" id="mazer-logo" style="max-width: 250px; margin-top: 10px;">
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
                <tbody id="mazer-calculo-result">
                    <!-- Resultados aparecerão aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Função para carregar juros do banco de dados
function loadJuros() {
    fetch('juros_mazer.php')
        .then(response => response.json())
        .then(jurosmazer => {
            document.getElementById('valor-item').addEventListener('input', function() {
                calculatemazer(jurosmazer);
            });

            // Calcular imediatamente caso já tenha um valor preenchido
            calculatemazer(jurosmazer);
        })
        .catch(error => console.error('Erro ao carregar os dados dos juros:', error));
}

function calculatemazer(jurosmazer) {
    const valorItem = parseFloat(document.getElementById('valor-item').value) || 0;
    const resultContainer = document.getElementById('mazer-calculo-result');
    resultContainer.innerHTML = '';

    if (valorItem > 0) {
        jurosmazer.forEach(item => {
            const parcelas = item.parcelas; // Utilizando a quantidade de parcelas do banco de dados
            const valorTotal = valorItem * (1 + item.juros / 100);
            const valorParcela = valorTotal / parcelas;

            resultContainer.innerHTML += `
                <tr>
                    <td>${item.descricao}</td>
                    <td>R$ ${valorTotal.toFixed(2).replace('.', ',')}</td>
                    <td>R$ ${valorParcela.toFixed(2).replace('.', ',')}</td>
                </tr>`;
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadJuros();  // Carregar os juros ao carregar a página
});
</script>

</body>
</html>
