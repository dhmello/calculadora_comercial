<div class="container">
    <div class="row">
        <!-- Campo Global para o Valor do Item -->
        <div class="col-md-12">
            <div class="form-group">
                <label for="valor-item-global-exibicao">Valor do Item (BOL 28D):</label>
                <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Coloque o valor que abre como padrão boleto 28D no sistema, e iremos calcular para as demais condições">!</span>
                <!-- Campo exibido ao usuário (formato de texto para permitir a formatação) -->
                <input type="text" class="form-control" id="valor-item-global-exibicao" placeholder="R$ 0,00">
                <!-- Campo oculto que armazenará o valor numérico -->
                <input type="number" class="form-control" id="valor-item-global" style="display:none;">
            </div>
        </div>

        <!-- Contêiner BOLETO MAZER -->
        <div class="col-md-6">
            <div class="tab-pane fade show active" id="mazer" role="tabpanel" aria-labelledby="mazer-tab">
                <div class="card full-width-card" style="max-width: 800px; margin: 0 auto;">
                    <div class="card-header header-row">
                        <h6 class="mb-0">BOLETO MAZER</h6>
                            <div class="form-group text-center">
                                <img src="assets/img/mazer.png" alt="Logo da Mazer" id="mazer-logo" style="max-width: 175px">
                            </div>                        
                    </div>
                    <div class="card-body">
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
        </div>

        <!-- Contêiner BOLETO MBC -->
        <div class="col-md-6">
            <div class="tab-pane fade show active" id="mbc" role="tabpanel" aria-labelledby="mbc-tab">
                <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
                    <div class="card-header header-row">
                        <h6 class="mb-0">BOLETO MBC</h6>
                            <div class="form-group text-center">
                                <img src="assets/img/mbc.png" alt="Logo do mbc" id="mbc-logo" style="max-width: 250px">
                            </div>  
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>Parcelas</th>
                                    <th>Valor Total</th>
                                    <th>Valor da Parcela</th>
                                </tr>
                            </thead>
                            <tbody id="mbc-calculo-result">
                                <!-- Resultados aparecerão aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para formatar valores no formato de reais (R$)
function formatarMoeda(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// Atualizar valor global conforme o valor formatado
function atualizarValorGlobal(valorFormatado) {
    const valorNumerico = parseFloat(valorFormatado.replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
    document.getElementById('valor-item-global').value = valorNumerico;

    // Chama os cálculos após atualizar o valor
    triggerCalculations();
}

// Formatar o campo de exibição do valor global
document.getElementById('valor-item-global-exibicao').addEventListener('input', function (e) {
    let input = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
    let valor = (parseFloat(input) / 100).toFixed(2); // Transforma em valor decimal
    let valorFormatado = formatarMoeda(parseFloat(valor)); // Formata como moeda BRL
    e.target.value = valorFormatado;
    atualizarValorGlobal(valorFormatado);
});

// Função para carregar juros para o Mazer
function loadJurosMazer() {
    fetch('juros_mazer.php')
        .then(response => response.json())
        .then(jurosmazer => {
            localStorage.setItem('jurosMazer', JSON.stringify(jurosmazer)); // Armazenar localmente
            calculatemazer(jurosmazer); // Calcula imediatamente ao carregar
        })
        .catch(error => console.error('Erro ao carregar os dados dos juros Mazer:', error));
}

// Função para carregar juros para o MBC
function loadJurosMbc() {
    fetch('juros_mbc.php')
        .then(response => response.json())
        .then(jurosmbc => {
            localStorage.setItem('jurosMbc', JSON.stringify(jurosmbc)); // Armazenar localmente
            calculatembc(jurosmbc); // Calcula imediatamente ao carregar
        })
        .catch(error => console.error('Erro ao carregar os dados dos juros MBC:', error));
}

// Cálculo para BOLETO MAZER
function calculatemazer(jurosmazer) {
    const valorItem = parseFloat(document.getElementById('valor-item-global').value) || 0;
    const resultContainer = document.getElementById('mazer-calculo-result');
    resultContainer.innerHTML = '';

    if (valorItem > 0) {
        jurosmazer.forEach(item => {
            const parcelas = item.parcelas;
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
}

// Cálculo para BOLETO MBC
function calculatembc(jurosmbc) {
    const valorItem = parseFloat(document.getElementById('valor-item-global').value) || 0;
    const resultContainer = document.getElementById('mbc-calculo-result');
    resultContainer.innerHTML = '';

    if (valorItem > 0) {
        jurosmbc.forEach(item => {
            const parcelas = item.parcelas;
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
}

// Função para chamar todos os cálculos (Mazer e MBC)
function triggerCalculations() {
    const jurosmazer = JSON.parse(localStorage.getItem('jurosMazer'));
    const jurosmbc = JSON.parse(localStorage.getItem('jurosMbc'));

    if (jurosmazer) {
        calculatemazer(jurosmazer);
    }

    if (jurosmbc) {
        calculatembc(jurosmbc);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Carregar os dados de juros para Mazer e MBC ao carregar a página
    loadJurosMazer();  
    loadJurosMbc();
});
</script>
