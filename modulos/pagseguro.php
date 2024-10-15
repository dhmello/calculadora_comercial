<div class="tab-pane fade show active" id="pagseguro" role="tabpanel" aria-labelledby="pagseguro-tab">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">PAGSEGURO CÁLCULO</h6>
        </div>
        <div class="card-body">
            <form id="pagseguro-calculo-form">
                <div class="form-group">
                    <label for="valor-item-pagseguro-exibicao">Valor do Item:</label>
                    <!-- Campo exibido ao usuário com formatação de reais -->
                    <input type="text" class="form-control" id="valor-item-pagseguro-exibicao" placeholder="R$ 0,00">
                    <!-- Campo oculto para armazenar o valor numérico -->
                    <input type="number" class="form-control" id="valor-item-pagseguro" style="display:none;">
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
// Função para formatar valores no formato de reais (R$)
function formatarMoeda(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// Atualizar valor global conforme o valor formatado
function atualizarValorPagSeguro(valorFormatado) {
    const valorNumerico = parseFloat(valorFormatado.replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
    document.getElementById('valor-item-pagseguro').value = valorNumerico;

    // Chama os cálculos após atualizar o valor
    triggerPagSeguroCalculation();
}

// Formatar o campo de exibição do valor global
document.getElementById('valor-item-pagseguro-exibicao').addEventListener('input', function (e) {
    let input = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
    let valor = (parseFloat(input) / 100).toFixed(2); // Transforma em valor decimal
    let valorFormatado = formatarMoeda(parseFloat(valor)); // Formata como moeda BRL
    e.target.value = valorFormatado;
    atualizarValorPagSeguro(valorFormatado);
});

// Função para calcular o PagSeguro
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
                <td>${formatarMoeda(valorTotal)}</td>
                <td>${formatarMoeda(valorParcela)}</td>
            </tr>`;
    });
}

// Função para carregar juros PagSeguro
function loadJurosPagSeguro() {
    fetch('juros_pagseguro.php')
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('jurosPagSeguro', JSON.stringify(data)); // Armazenar localmente
            calculatePagSeguro(data); // Calcula imediatamente ao carregar
        })
        .catch(error => console.error('Erro ao carregar os dados dos juros PagSeguro:', error));
}

// Função para chamar os cálculos do PagSeguro
function triggerPagSeguroCalculation() {
    const jurosPagSeguro = JSON.parse(localStorage.getItem('jurosPagSeguro'));

    if (jurosPagSeguro) {
        calculatePagSeguro(jurosPagSeguro);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadJurosPagSeguro(); // Carrega os dados de juros PagSeguro ao carregar a página
});
</script>
