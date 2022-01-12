
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


<h2><i class="fa fa-id-card-o"></i>Relatórios de Gastos com Materiais</h2>

    Obra: {{$construction}} <br>
    Fornecedor: {{$provider}} <br>
    Material: {{$material}} <br>
    Nota: {{$invoice}} <br>
    Período: {{$dtRange}} <br> <br>

	<div class="wraper-table">
	<table>
		<tr>
			<th>Material</th>
			<th>Unid</th>
			<th>Quant</th>
			<th>Vlr Unit</th>
			<th>Vlr Total</th>
		</tr>

		<?php

			foreach ($invoices as $key => $value) {
		?>
		<tr>
			<td><?php echo $value['material_name']; ?></td>
			<td><?php echo $value['material_unid']; ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_qt'], 2); ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_unit_value'], 2); ?></td>
			<td class="vlr_field"><?php echo number_format($value['material_qt'] * $value['material_unit_value'], 2); ?></td>
		</tr>

		<?php } ?>

	</table>
</div>
