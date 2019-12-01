create view view_ejercicio as select candidato_proyecto_ejercicio.idcandidato_proyecto_ejercicio, candidato_proyecto_ejercicio.idejercicio, 
candidato_proyecto_ejercicio.idcandidato_proyecto, candidato_proyecto_ejercicio.iduser, candidato_proyecto_ejercicio.estatus, 
candidato_proyecto_ejercicio.idtipo_ejercicio_cliente, ejercicio.nombre as ejercicio, 
candidato.idcandidato as idcandidato,candidato.nombre as nombre, candidato.paterno as paterno, candidato.materno as materno, candidato.idcliente,
proyecto.idproyecto as idproyecto, proyecto.nombre as proyecto,  DATE_FORMAT(proyecto.fecha_fin, "%d-%m-%Y") as fecha_fin, 
DATEDIFF("2019-11-23 23:15:10",proyecto.fecha_fin) as dias,
tipo_ejercicio.idtipo_ejercicio, tipo_ejercicio.nombre as tipoejercicio,
CASE
    WHEN DATEDIFF("2019-11-23 23:15:10",proyecto.fecha_fin) > 10 THEN "#dff0d8"
    WHEN DATEDIFF("2019-11-23 23:15:10",proyecto.fecha_fin) > 5 AND DATEDIFF("2019-11-23 23:15:10",proyecto.fecha_fin) <=10 THEN "#fcf8e3"
    ELSE "#f2dede"
END as clase,candidato_proyecto_ejercicio.observaciones
 from candidato_proyecto_ejercicio 
 inner join candidato_proyecto on candidato_proyecto.idcandidato_proyecto = candidato_proyecto_ejercicio.idcandidato_proyecto 
 inner join ejercicio on ejercicio.idejercicio = candidato_proyecto_ejercicio.idejercicio 
 inner join tipo_ejercicio_cliente on tipo_ejercicio_cliente.idtipo_ejercicio_cliente = candidato_proyecto_ejercicio.idtipo_ejercicio_cliente 
 inner join candidato on candidato.idcandidato = candidato_proyecto.idcandidato 
 inner join proyecto on proyecto.idproyecto = candidato_proyecto.idproyecto 
 inner join tipo_ejercicio on tipo_ejercicio.idtipo_ejercicio = ejercicio.idtipo_ejercicio 
 order by proyecto.idproyecto, ejercicio.idejercicio,candidato.nombre, candidato.paterno, candidato.materno asc;


 alter table detalle_resultado_candidato_ejercicio  add  idcalificacion_comportamiento int(3);



