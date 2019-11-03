<select class="form-control input-sm pagesize" data-toggle="tooltip" data-placement="top" id="noRegs" title="{{Lang::get('leyendas.tamanoPagina')}}" style="width:60px;" >
	<option value="10"  <?php if ($noRegs== 10){ echo "selected";} ?>>10</option>
	<option value="25"  <?php if ($noRegs== 25){ echo "selected";} ?>>25</option>
	<option value="50"  <?php if ($noRegs== 50){ echo "selected";} ?>>50</option>
	<option value="100" <?php if($noRegs== 100){ echo "selected";} ?>>100</option>
</select>
