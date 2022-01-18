
<style>
    * {
        color:#222;
    }
    table {
        width:100%;
        border: 1px solid #000;
    }
    table td, table th {
        border: 1px solid #000;
        padding:5px;
    }
    table th {
        font-size:14px;
        font-weight:bold;
        background-color:#eee;
    }
    table .vlr_field {
        text-align: right;
    }
</style>


<h1><i class="fa fa-id-card-o"></i>Relatórios de Gastos com Materiais</h1>

    <h3>Obra: {{$construction}}</h3>
    {{-- Fornecedor: {{$provider}} <br> --}}
    <h3>Material: {{$material}}</h3>
    {{-- Nota: {{$invoice}} <br> --}}
    <h3>Período: {{$dtRange}}</h3>

	<div class="wraper-table">
	<table>
		<tr>
            <th>Nota</th>
            <th>Data</th>
			<th>Material</th>
			<th>Unid</th>
			<th>Quant</th>
			<th>Vlr Unit</th>
			<th>Vlr Total</th>
		</tr>
        <tr>
            <th colspan="4">TOTAIS</th>
            <th>{{number_format($total_materials, 2, ',', '.')}}</th>
            <th></th>
            <th>{{number_format($total_cost, 2, ',', '.')}}</th>
        </tr>

		<?php

			foreach ($invoices as $key => $value) {
		?>
		<tr>
            <td>{{$value['invoice_number']}}</td>
            <td class="vlr_field">{{date('d/m/Y', strtotime($value['invoice_date']))}}</td>
			<td><?php echo $value['material_name']; ?></td>
			<td><?php echo $value['material_unid']; ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_qt'], 2); ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_unit_value'], 2); ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_qt'] * $value['material_unit_value'], 2); ?></td>
		</tr>

		<?php } ?>

	</table>
</div>
