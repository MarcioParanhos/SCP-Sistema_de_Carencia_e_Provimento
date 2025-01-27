@extends('layout.main')

@section('title', 'SCP - Status Diário')

@section('content')
<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">STATUS GERAL DIÁRIO</h3>
</div>
<!-- <div class="mb-2 print-btn">
    <a class="mb-2 btn bg-primary text-white" target="_blank" href="#"><i class="ti-download"></i> EXCEL</a>
</div> -->
<div class="table">
    <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th class="text-center" scope="col"></th>
                <th class="text-center" scope="col" colspan="2">VAGA REAL</th>
                <th class="text-center" scope="col" colspan="2">VAGA TEMPORÁRIA</th>
                <th class="text-center" scope="col" colspan="2">VAGA ED. ESPECIAL</th>
                <th class="text-center" scope="col" colspan="2">PROVIMENTO</th>
                <th class="text-center" scope="col" colspan="2">PROV. EM TRÂMITE</th>
            </tr>
            <tr class="bg-primary text-white">
                <th class="text-center" scope="col">DATA</th>
                <th class="text-center" scope="col">BÁSICA</th>
                <th class="text-center" scope="col">PROFISS.</th>
                <th class="text-center" scope="col">BÁSICA</th>
                <th class="text-center" scope="col">PROFISS.</th>
                <th class="text-center" scope="col">REAL</th>
                <th class="text-center" scope="col">TEMP.</th>
                <th class="text-center" scope="col">REAL</th>
                <th class="text-center" scope="col">TEMP.</th>
                <th class="text-center" scope="col">REAL</th>
                <th class="text-center" scope="col">TEMP.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($status_diarios as $status_diario)
            <tr>
                <td class="text-center" scope="row"><strong>{{ \Carbon\Carbon::parse($status_diario->data)->format('d/m/Y') }}</strong></td>
                <td class="text-center vaga-real-basica"><strong>{{ number_format($status_diario->carenciaBasicaReal, 0, ',', '.') }} h </strong></i></td>
                <td class="text-center vaga-real-profi"><strong>{{ number_format($status_diario->carenciaProfReal, 0, ',', '.') }} h </strong></td>
                <td class="text-center vaga-temp-basica"><strong>{{ number_format($status_diario->carenciaBasicaTemp, 0, ',', '.') }} h </strong></td>
                <td class="text-center vaga-temp-profi"><strong>{{ number_format($status_diario->carenciaProfiTemp, 0, ',', '.') }} h </strong></i></td>
                <td class="text-center vaga-esp-real"><strong>{{ number_format($status_diario->carenciaRealEdEspecial, 0, ',', '.') }} h </strong></td>
                <td class="text-center vaga-esp-temp"><strong>{{ number_format($status_diario->carenciaTempEdEspecial, 0, ',', '.') }} h </strong></i></td>
                <td class="text-center prov-real"><strong>{{ number_format($status_diario->provimentoReal, 0, ',', '.') }} h </strong></td>
                <td class="text-center prov-temp"><strong>{{ number_format($status_diario->provimentoTemp, 0, ',', '.') }} h </strong></i></td>
                <td class="text-center prov-tramite-real"><strong>{{ number_format($status_diario->provimentoTramiteReal, 0, ',', '.') }} h </strong></td>
                <td class="text-center prov-tramite-temp"><strong>{{ number_format($status_diario->provimentoTramiteTemp, 0, ',', '.') }} h </strong></i></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    const active_relatorios = document.getElementById("active_relatorios")
    active_relatorios.classList.add('active')
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cells = document.querySelectorAll(".vaga-real-basica");

        const firstCell = cells[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cells.length; i++) {
            const currentCell = cells[i];
            const previousCell = cells[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsProfi = document.querySelectorAll(".vaga-real-profi");
        const firstCell = cellsProfi[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsProfi.length; i++) {
            const currentCell = cellsProfi[i];
            const previousCell = cellsProfi[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsTempBasica = document.querySelectorAll(".vaga-temp-basica");
        const firstCell = cellsTempBasica[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsTempBasica.length; i++) {
            const currentCell = cellsTempBasica[i];
            const previousCell = cellsTempBasica[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsTempProfi = document.querySelectorAll(".vaga-temp-profi");
        const firstCell = cellsTempProfi[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsTempProfi.length; i++) {
            const currentCell = cellsTempProfi[i];
            const previousCell = cellsTempProfi[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsEspReal = document.querySelectorAll(".vaga-esp-real");
        const firstCell = cellsEspReal[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsEspReal.length; i++) {
            const currentCell = cellsEspReal[i];
            const previousCell = cellsEspReal[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsEspTemp = document.querySelectorAll(".vaga-esp-temp");
        const firstCell = cellsEspTemp[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsEspTemp.length; i++) {
            const currentCell = cellsEspTemp[i];
            const previousCell = cellsEspTemp[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "green";
                currentCell.appendChild(icon);
                currentCell.style.color = "green";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "red";
                currentCell.appendChild(icon); 
                currentCell.style.color = "red";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsProvReal = document.querySelectorAll(".prov-real");
        const firstCell = cellsProvReal[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsProvReal.length; i++) {
            const currentCell = cellsProvReal[i];
            const previousCell = cellsProvReal[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "red";
                currentCell.appendChild(icon);
                currentCell.style.color = "red";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "green";
                currentCell.appendChild(icon); 
                currentCell.style.color = "green";
            }
        }
    });


    document.addEventListener("DOMContentLoaded", function () {
        const cellsProvTemp = document.querySelectorAll(".prov-temp");
        const firstCell = cellsProvTemp[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsProvTemp.length; i++) {
            const currentCell = cellsProvTemp[i];
            const previousCell = cellsProvTemp[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "red";
                currentCell.appendChild(icon);
                currentCell.style.color = "red";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "green";
                currentCell.appendChild(icon); 
                currentCell.style.color = "green";
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const cellsProvTramiteReal = document.querySelectorAll(".prov-tramite-real");
        const firstCell = cellsProvTramiteReal[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsProvTramiteReal.length; i++) {
            const currentCell = cellsProvTramiteReal[i];
            const previousCell = cellsProvTramiteReal[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "red";
                currentCell.appendChild(icon);
                currentCell.style.color = "red";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "green";
                currentCell.appendChild(icon); 
                currentCell.style.color = "green";
            }
        }
    });


    document.addEventListener("DOMContentLoaded", function () {
        const cellsProvTramiteTemp = document.querySelectorAll(".prov-tramite-temp");
        const firstCell = cellsProvTramiteTemp[0];
        const iconFirst = document.createElement("i");
        iconFirst.className = "ti-layout-line-solid";
        iconFirst.style.color = "grey";
        firstCell.appendChild(iconFirst);

        for (let i = 1; i < cellsProvTramiteTemp.length; i++) {
            const currentCell = cellsProvTramiteTemp[i];
            const previousCell = cellsProvTramiteTemp[i - 1];

            const currentValue = parseFloat(currentCell.textContent.replace(" h", "").replace(",", "."));
            const previousValue = parseFloat(previousCell.textContent.replace(" h", "").replace(",", "."));
            console.log(currentValue);

            if (currentValue < previousValue) {
                const icon = document.createElement("i");
                icon.className = "ti-stats-down";
                icon.style.color = "red";
                currentCell.appendChild(icon);
                currentCell.style.color = "red";
            } else if (currentValue === previousValue || !currentValue) {
                const icon = document.createElement("i");
                icon.className = "ti-layout-line-solid";
                icon.style.color = "grey";
                currentCell.appendChild(icon);
            } else {
                const icon = document.createElement("i");
                icon.className = "ti-stats-up";
                icon.style.color = "green";
                currentCell.appendChild(icon); 
                currentCell.style.color = "green";
            }
        }
    });

</script>

@endsection