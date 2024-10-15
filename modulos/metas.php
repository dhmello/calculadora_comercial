<script>
    // Função para formatar números para o formato monetário brasileiro
    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function calcularResultados() {
        // Obtenha os valores das metas e realizados
        var metaFaturado = parseFloat(document.getElementById('meta-faturado').value.replace(/\./g, '').replace(',', '.')) || 0;
        var realizadoFaturado = parseFloat(document.getElementById('realizado-faturado').value.replace(/\./g, '').replace(',', '.')) || 0;
        var metaMC = parseFloat(document.getElementById('meta-mc').value.replace(/\./g, '').replace(',', '.')) || 0;
        var realizadoMC = parseFloat(document.getElementById('realizado-mc').value.replace(/\./g, '').replace(',', '.')) || 0;
        var metaCampanha = parseFloat(document.getElementById('meta-campanha').value.replace(/\./g, '').replace(',', '.')) || 0;
        var realizadoCampanha = parseFloat(document.getElementById('realizado-campanha').value.replace(/\./g, '').replace(',', '.')) || 0;
        var capilaridadeMeta = parseFloat(document.getElementById('capilaridade-meta').value) || 0;
        var capilaridadeRealizado = parseFloat(document.getElementById('capilaridade-realizado').value) || 0;
        var metaTop10 = parseFloat(document.getElementById('meta-top10').value.replace(/\./g, '').replace(',', '.')) || 0;
        var realizadoTop10 = parseFloat(document.getElementById('realizado-top10').value.replace(/\./g, '').replace(',', '.')) || 0;

        // Obtenha os valores dos aceleradores
        var aceleradorMC = parseFloat(document.getElementById('acelerador-mc').value) / 100 || 0;
        var aceleradorFA = parseFloat(document.getElementById('acelerador-fa').value) / 100 || 0;
        var aceleradorTop10 = parseFloat(document.getElementById('acelerador-top10').value) / 100 || 0;
        var aceleradorExtraFilial = 0.25; // Fixado em 25%

        // Calcule o progresso
        var progressoFaturado = (realizadoFaturado / metaFaturado) * 100 || 0;
        var progressoMC = (realizadoMC / metaMC) * 100 || 0;
        var progressoCampanha = (realizadoCampanha / metaCampanha) * 100 || 0;
        var progressoCapilaridade = (capilaridadeRealizado / capilaridadeMeta) * 100 || 0;
        var progressoTop10 = (realizadoTop10 / metaTop10) * 100 || 0;

        // Calcule a margem
        var margem = (realizadoMC / realizadoFaturado) * 100 || 0;

        // Atualize os campos de progresso
        document.getElementById('progresso-faturado').innerText = progressoFaturado.toFixed(2) + '%';
        document.getElementById('progresso-mc').innerText = progressoMC.toFixed(2) + '%';
        document.getElementById('progresso-campanha').innerText = progressoCampanha.toFixed(2) + '%';
        document.getElementById('progresso-capilaridade').innerText = progressoCapilaridade.toFixed(2) + '%';
        document.getElementById('progresso-top10').innerText = progressoTop10.toFixed(2) + '%';

        // Atualize o campo de margem
        document.getElementById('margem').innerText = margem.toFixed(2) + '%';

        // Calcule os campos de falta
        document.getElementById('falta-faturado').innerText = formatCurrency(metaFaturado - realizadoFaturado);
        document.getElementById('falta-mc').innerText = formatCurrency(metaMC - realizadoMC);
        document.getElementById('falta-campanha').innerText = formatCurrency(metaCampanha - realizadoCampanha);
        document.getElementById('falta-capilaridade').innerText = capilaridadeMeta - capilaridadeRealizado;
        document.getElementById('falta-top10').innerText = formatCurrency(metaTop10 - realizadoTop10);

        // Calcule os valores extras com aceleradores
        var calculoMC = (realizadoMC * aceleradorMC).toFixed(2);
        var calculoFA = (realizadoMC * aceleradorFA).toFixed(2);
        var calculoTop10 = (realizadoTop10 * aceleradorTop10).toFixed(2);

        // Calcule o valor do extra filial com acelerador fixo de 25%
        var extraFilial = (parseFloat(calculoMC) * aceleradorExtraFilial).toFixed(2);

        // Verifique se a filial bateu a meta
        if (!document.getElementById('filial-bateu-meta').checked) {
            extraFilial = 0;
        }

        // Calcule o valor da bonificação
        var bonificacao = (parseFloat(calculoMC) + parseFloat(calculoFA) + parseFloat(calculoTop10)).toFixed(2);
        if (document.getElementById('filial-bateu-meta').checked) {
            bonificacao = (parseFloat(bonificacao) + parseFloat(extraFilial)).toFixed(2);
        }

        // Atualize os campos de resultados
        document.getElementById('calculo-mc').innerText = formatCurrency(parseFloat(calculoMC));
        document.getElementById('calculo-fa').innerText = formatCurrency(parseFloat(calculoFA));
        document.getElementById('calculo-top10').innerText = formatCurrency(parseFloat(calculoTop10));
        document.getElementById('extra-filial').innerText = formatCurrency(parseFloat(extraFilial));
        document.getElementById('bonificacao').innerText = formatCurrency(parseFloat(bonificacao));
    }

    // Função para formatar o valor enquanto digita
    function formatInput(input) {
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        value = (value / 100).toFixed(2) + ''; // Converte para centavos e formata com 2 casas decimais
        value = value.replace('.', ','); // Substitui ponto por vírgula
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'); // Adiciona os pontos de milhar
        input.value = value; // Define o valor formatado no campo
    }

    // Adicione um event listener para recalcular quando a fórmula TOP 10 mudar
    document.getElementById('top10-calculo-form').addEventListener('input', calcularResultados);
</script>

<div class="container">
    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>META<span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Se você deseja calcular apenas a bonificação, não é necessario preencher a META.">!</span></th>
                    <th>REALIZADO</th>
                    <th>PROGRESSO</th>
                    <th>FALTA</th>
                    <th>MARGEM</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FATURAMENTO</td>
                    <td><input type="text" id="meta-faturado" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td><input type="text" id="realizado-faturado" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td id="progresso-faturado">0,00%</td>
                    <td id="falta-faturado">R$ 0,00</td>
                    <td id="margem">0,00%</td>
                </tr>
                <tr>
                    <td>MARGEM</td>
                    <td><input type="text" id="meta-mc" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td><input type="text" id="realizado-mc" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td id="progresso-mc">0,00%</td>
                    <td id="falta-mc">R$ 0,00</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>CAMPANHA</td>
                    <td><input type="text" id="meta-campanha" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td><input type="text" id="realizado-campanha" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td id="progresso-campanha">0,00%</td>
                    <td id="falta-campanha">R$ 0,00</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>CAPILARIDADE</td>
                    <td><input type="number" id="capilaridade-meta" value="0" oninput="calcularResultados()"></td>
                    <td><input type="number" id="capilaridade-realizado" value="0" oninput="calcularResultados()"></td>
                    <td id="progresso-capilaridade">0,00%</td>
                    <td id="falta-capilaridade">0</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>MC TOP 10</td>
                    <td><input type="text" id="meta-top10" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td><input type="text" id="realizado-top10" value="0" oninput="formatInput(this); calcularResultados()"></td>
                    <td id="progresso-top10">0,00%</td>
                    <td id="falta-top10">R$ 0,00</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="table table-bordered">
        <tr>
            <td>BONUS MC.</td>
            <td colspan="4" id="calculo-mc">R$ 0,00</td>
            <td><input type="number" id="acelerador-mc" value="0" oninput="calcularResultados()"> % <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Coloque aqui qual a percentagem que voce está habilitado nos aceleradores enviado pela GV.">!</span></td>
        </tr>
        <tr>
            <td>BONUS FA.</td>
            <td colspan="4" id="calculo-fa">R$ 0,00</td>
            <td><input type="number" id="acelerador-fa" value="0" oninput="calcularResultados()"> % <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Coloque aqui qual a percentagem que voce está habilitado nos aceleradores enviado pela GV.">!</span></td>
        </tr>
        <tr>
            <td>BONUS TOP 10</td>
            <td colspan="4" id="calculo-top10">R$ 0,00</td>
            <td><input type="number" id="acelerador-top10" value="0" oninput="calcularResultados()"> % <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Lembrando que o acelerador se baseia no Negociado, porem o calculo é na MC feita no TOP 10.">!</span></td>
        </tr>
        <tr>
            <td>EXTRA FILIAL</td>
            <td colspan="5" id="extra-filial">R$ 0,00</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="5">
                <input type="checkbox" id="filial-bateu-meta" oninput="calcularResultados()"> Filial bateu meta?
            </td>
        </tr>
        <tr>
            <td>BONIFICAÇÃO FINAL</td>
            <td colspan="5" id="bonificacao">R$ 0,00</td>
        </tr>
    </table>
</div>

<div class="container">
    <div class="card full-width-card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header header-row">
            <h6 class="mb-0">TOP 10 <span class="tooltip-icon" data-toggle="tooltip" data-tooltip="Preencha conforme a quantidade de clientes que fechou em cada categoria.">!</span></h6>
        </div>
        <div class="card-body">
            <form id="top10-calculo-form">
                <div class="form-group">
                    <label for="a3">< 20%</label>
                    <input type="number" class="form-control" id="a3" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="b3">20% A 34%</label>
                    <input type="number" class="form-control" id="b3" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="c3">35% A 49%</label>
                    <input type="number" class="form-control" id="c3" placeholder="0">
                </div>
                <div class="form-group">
                    <label for="d3">> 50%</label>
                    <input type="number" class="form-control" id="d3" placeholder="0">
                </div>
            </form>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>SOMA</th>
                        <th>FORMULA</th>
                    </tr>
                </thead>
                <tbody id="top10-calculo-result">
                    <tr>
                        <td id="soma">0</td>
                        <td id="formula" class="green-cell">0,00%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['a3', 'b3', 'c3', 'd3'];
    
    inputs.forEach(id => {
        document.getElementById(id).addEventListener('input', updateTop10Results);
    });

    function updateTop10Results() {
        const a3 = parseInt(document.getElementById('a3').value) || 0;
        const b3 = parseInt(document.getElementById('b3').value) || 0;
        const c3 = parseInt(document.getElementById('c3').value) || 0;
        const d3 = parseInt(document.getElementById('d3').value) || 0;

        const soma = a3 + b3 + c3 + d3;
        document.getElementById('soma').textContent = soma;

        let formula = 0;
        if (a3 === 2 && d3 >= 5 && (b3 + c3 + d3) === 8 && (c3 + d3) >= 7) {
            formula = 95;
        } else if (a3 === 1 && d3 >= 5 && (b3 + c3 + d3) === 9 && (c3 + d3) >= 7) {
            formula = 100;
        } else if (a3 === 0 && d3 >= 5 && (b3 + c3 + d3) === 10 && (c3 + d3) >= 7) {
            formula = 110;
        }

        document.getElementById('formula').textContent = formula > 0 ? `${formula.toFixed(2)}%` : `0,00%`;
    }
});
</script>
