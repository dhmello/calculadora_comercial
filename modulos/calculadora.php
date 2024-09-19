<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Boletos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Contêiner 1: Dias até o Vencimento -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-row">
                    <h6 class="mb-0">JANELAS: DIAS PARA VENCIMENTO
                        <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Digite as datas que o seu cliente precisa que caia o boleto, e ao lado mostrará qual prazo de boleto você deve por no pedido.">!</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tooltip-content" style="display: none;">
                        Digite as datas que o seu cliente precisa que caia o boleto, e ao lado mostrará qual prazo de boleto você deve por no pedido.
                    </div>
                    <form id="dias-para-vencimento-form">
                        <label>Insira as Datas de Vencimento:</label>
                        <div id="dias-para-vencimento-container">
                            <div class="form-group">
                                <input type="date" class="form-control data-vencimento" placeholder="Data de Vencimento">
                            </div>
                        </div>
                        <span class="add-btn" onclick="addField('dias-para-vencimento-container', 'date')">
                            <i class="fas fa-plus"></i>
                        </span>
                    </form>

                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>VENCE DIA</th>
                                <th>BOLETO (D)</th>
                            </tr>
                        </thead>
                        <tbody id="dias-para-vencimento-result">
                            <!-- Resultados aparecem aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contêiner 2: Data de Vencimento a partir de Dias -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-row">
                    <h6 class="mb-0">JANELAS: DATA PARA VENCIMENTO
                        <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Digite os dias para o vencimento do boleto, e ao lado mostrará a data exata de vencimento.">!</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tooltip-content" style="display: none;">
                        Digite os dias para o vencimento do boleto, e ao lado mostrará a data exata de vencimento.
                    </div>
                    <form id="data-para-vencimento-form">
                        <label>Insira os Dias para o Vencimento:</label>
                        <div id="data-para-vencimento-container">
                            <div class="form-group">
                                <input type="number" class="form-control dias-boleto" placeholder="Dias">
                            </div>
                        </div>
                        <span class="add-btn" onclick="addField('data-para-vencimento-container', 'number')">
                            <i class="fas fa-plus"></i>
                        </span>
                    </form>

                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>BOLETO (D)</th>
                                <th>VENCE DIA</th>
                            </tr>
                        </thead>
                        <tbody id="data-para-vencimento-result">
                            <!-- Resultados aparecem aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Contêiner 3: Cálculo da Data de Vencimento com base na Compra e Dias -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-row">
                    <h6 class="mb-0">VENCIMENTO POR DATA DO PEDIDO
                        <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Informe a data de compra e os dias para vencimento, e veja ao lado a data exata de vencimento do boleto.">!</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tooltip-content" style="display: none;">
                        Informe a data de compra e os dias para vencimento, e veja ao lado a data exata de vencimento do boleto.
                    </div>
                    <form id="calculo-vencimento-form">
                        <label>Insira a Data de Compra e Dias para Vencimento:</label>
                        <div id="calculo-vencimento-container">
                            <div class="form-group">
                                <input type="date" class="form-control data-compra" placeholder="Data de Compra">
                                <input type="number" class="form-control dias-boleto-compra" placeholder="Dias do Boleto">
                            </div>
                        </div>
                        <span class="add-btn" onclick="addField('calculo-vencimento-container', 'compra')">
                            <i class="fas fa-plus"></i>
                        </span>
                    </form>

                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>Compra</th>
                                <th>Boleto (D)</th>
                                <th>VENCE DIA</th>
                            </tr>
                        </thead>
                        <tbody id="calculo-vencimento-result">
                            <!-- Resultados aparecem aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contêiner 4: Cálculo de Preço IPI -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-row">
                    <h6 class="mb-0">CÁLCULO DE PREÇO IPI
                        <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Informe o valor do sistema e a porcentagem de IPI para calcular o valor sem IPI e o valor do IPI.">!</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tooltip-content" style="display: none;">
                        Informe o valor do sistema e a porcentagem de IPI para calcular o valor sem IPI e o valor do IPI.
                    </div>
                    <form id="calculo-ipi-form">
                        <div class="form-group">
                            <label for="valor-sistema">Valor do Sistema:</label>
                            <input type="number" class="form-control" id="valor-sistema" placeholder="R$ 0,00">
                        </div>
                        <div class="form-group">
                            <label for="ipi-percentage">IPI %:</label>
                            <input type="number" class="form-control" id="ipi-percentage" placeholder="15,00" value="15.00">
                        </div>
                    </form>

                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>Valor Sem IPI</th>
                                <th>Valor do IPI</th>
                            </tr>
                        </thead>
                        <tbody id="calculo-ipi-result">
                            <tr>
                                <td id="valor-sem-ipi" class="green-cell">R$ 0,00</td>
                                <td id="valor-ipi" class="orange-cell">R$ 0,00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Função para adicionar campos dinamicamente
    function addField(containerId, fieldType) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'form-group';

        if (fieldType === 'date') {
            div.innerHTML = `<input type="date" class="form-control data-vencimento" placeholder="Data de Vencimento">`;
            attachVencimentoListener(div.querySelector('.data-vencimento'));
        } else if (fieldType === 'number') {
            div.innerHTML = `<input type="number" class="form-control dias-boleto" placeholder="Dias">`;
            attachDiasListener(div.querySelector('.dias-boleto'));
        } else if (fieldType === 'compra') {
            div.innerHTML = `
                <input type="date" class="form-control data-compra" placeholder="Data de Compra">
                <input type="number" class="form-control dias-boleto-compra" placeholder="Dias do Boleto">
            `;
            attachCompraListener(div.querySelector('.data-compra'), div.querySelector('.dias-boleto-compra'));
        }

        container.appendChild(div);
    }

    function attachVencimentoListener(input) {
        input.addEventListener('input', function() {
            updateVencimentoResults();
        });
    }

    function attachDiasListener(input) {
        input.addEventListener('input', function() {
            updateDiasResults();
        });
    }

    function attachCompraListener(compraInput, diasInput) {
        compraInput.addEventListener('input', function() {
            updateCompraResults();
        });
        diasInput.addEventListener('input', function() {
            updateCompraResults();
        });
    }

    function updateVencimentoResults() {
        const inputs = document.querySelectorAll('.data-vencimento');
        const resultContainer = document.getElementById('dias-para-vencimento-result');
        resultContainer.innerHTML = '';

        inputs.forEach(input => {
            if (input.value) {
                const vencimentoDate = new Date(input.value + 'T00:00:00');
                const dayOfWeek = vencimentoDate.toLocaleDateString('pt-BR', { weekday: 'long' });
                const today = new Date();
                const diffTime = vencimentoDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                resultContainer.innerHTML += `<tr><td class="green-cell">${input.value.split('-').reverse().join('/')} - ${capitalizeFirstLetter(dayOfWeek)}</td><td>${diffDays}</td></tr>`;
            }
        });
    }

    function updateDiasResults() {
        const inputs = document.querySelectorAll('.dias-boleto');
        const resultContainer = document.getElementById('data-para-vencimento-result');
        resultContainer.innerHTML = '';

        inputs.forEach(input => {
            if (input.value) {
                const days = parseInt(input.value, 10);
                const today = new Date();
                today.setDate(today.getDate() + days);
                const vencimentoDate = today.toISOString().split('T')[0];
                const dayOfWeek = today.toLocaleDateString('pt-BR', { weekday: 'long' });
                resultContainer.innerHTML += `<tr><td>${days}</td><td class="green-cell">${vencimentoDate.split('-').reverse().join('/')} - ${capitalizeFirstLetter(dayOfWeek)}</td></tr>`;
            }
        });
    }

    function updateCompraResults() {
        const resultContainer = document.getElementById('calculo-vencimento-result');
        resultContainer.innerHTML = ''; // Limpar os resultados anteriores

        const compraInputs = document.querySelectorAll('.data-compra');
        const diasInputs = document.querySelectorAll('.dias-boleto-compra');

        compraInputs.forEach((compraInput, index) => {
            const diasInput = diasInputs[index];
            if (compraInput.value && diasInput.value) {
                const compraDate = new Date(compraInput.value + 'T00:00:00'); // Ajusta a data para evitar problemas de fuso horário
                const days = parseInt(diasInput.value, 10);
                compraDate.setDate(compraDate.getDate() + days);
                const vencimentoDate = compraDate.toISOString().split('T')[0];
                const dayOfWeek = compraDate.toLocaleDateString('pt-BR', { weekday: 'long' });
                resultContainer.innerHTML += `<tr><td class="orange-cell">${compraInput.value.split('-').reverse().join('/')}</td><td>${days}</td><td class="green-cell">${vencimentoDate.split('-').reverse().join('/')} - ${capitalizeFirstLetter(dayOfWeek)}</td></tr>`;
            }
        });
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Função para calcular o valor sem IPI
    function updateIPIResults() {
        const valorSistema = parseFloat(document.getElementById('valor-sistema').value) || 0;
        const ipiPercentage = parseFloat(document.getElementById('ipi-percentage').value) || 0;

        const valorSemIPI = valorSistema - (valorSistema * (ipiPercentage / 100));
        const valorIPI = valorSistema - valorSemIPI;

        document.getElementById('valor-sem-ipi').textContent = `R$ ${valorSemIPI.toFixed(2)}`;
        document.getElementById('valor-ipi').textContent = `R$ ${valorIPI.toFixed(2)}`;
    }

    // Inicializar os listeners no campo original
    document.addEventListener('DOMContentLoaded', function() {
        attachVencimentoListener(document.querySelector('.data-vencimento'));
        attachDiasListener(document.querySelector('.dias-boleto'));
        attachCompraListener(document.querySelector('.data-compra'), document.querySelector('.dias-boleto-compra'));

        // Listeners para o cálculo de IPI
        document.getElementById('valor-sistema').addEventListener('input', updateIPIResults);
        document.getElementById('ipi-percentage').addEventListener('input', updateIPIResults);

        // Inicializar tooltips customizados
        document.querySelectorAll('.tooltip-icon').forEach(function(icon) {
            icon.addEventListener('click', function() {
                var content = this.closest('.card').querySelector('.tooltip-content');
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
            });
        });
    });
</script>
</body>
</html>
