<p>A quien corresponda:</p>
Por medio del presente, se informa que se han registrado {{count($errores)}} errores en la plataforma:
<br>


  
@foreach($errores as $error)
<table style="border: 1px solid;">
  <tr style="border: 1px solid;">
    <th style="border: 1px solid;">Fecha</th>
    <th style="border: 1px solid;">Excepción</th>
    <th style="border: 1px solid;">Clase</th>
    <th style="border: 1px solid;">Metodo</th>
    <th style="border: 1px solid;">Linea</th>
  </tr>
	<tr style="border: 1px solid;">
		<td style="border: 1px solid;">{{$error->fecha}}</td>
		<td style="border: 1px solid;">{{$error->excepcion}}</td>
		<td style="border: 1px solid;">{{$error->clase}}</td>
		<td style="border: 1px solid;">{{$error->metodo}}</td>
		<td style="border: 1px solid;">{{$error->linea}}</td>
	</tr>
	<tr style="border: 1px solid;">
		<td colspan="5" style="border: 1px solid;">{{$error->archivo}}</td>
	</tr>
	<tr>
		<td colspan="5" style="border: 1px solid;">{{$error->mensaje}}</td>
	</tr>
</table>
<br>
@endforeach




Saludos