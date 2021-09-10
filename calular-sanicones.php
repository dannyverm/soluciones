<?php
function atrasos_vista_general() {
        $desde = date("Y/m/d", strtotime($this->input->post('desde')));
        $hasta = date("Y/m/d", strtotime($this->input->post('hasta')));
        
        $consulta = "SELECT m.nrocedula,m.nombreEmpleado, m.departamento,
			(select descripcion from departamento d, funcionario f where d.iddepartamento=f.iddepartamento
			and f.idfuncionario=m.nrocedula) as departamentos,
			(select activo from funcionario f where f.idfuncionario=m.nrocedula) as activo,
			@llegada_tardia:=(select count(*) from marcaciones where fecha between '$desde' and '$hasta' 
			and nrocedula=m.nrocedula and DAYOFWEEK(fecha) IN (2,3,4,5,6) and marcaSalida!='' 
			AND CONVERT (marcaEntrada,time)>=CONVERT(ADDTIME(horaEntrada,'00:11:00'),time) AND permiso = '')As llegada_tardia,
			@marcacion_antes:=(select count(*) from marcaciones where fecha between '$desde' and '$hasta' 
			and nrocedula=m.nrocedula and DAYOFWEEK(fecha) IN (2,3,4,5,6) and marcaSalida!='' 
			AND CONVERT (marcaSalida,time) < CONVERT(horaSalida,time) AND permiso = '')As marcacion_antes
			,@tardia:=(@llegada_tardia+@marcacion_antes) as tardia,
			@sin_marcar:=(select sum(if(falta='True',2,if(marcaEntrada='' or marcaSalida='',2,0))) 
			from marcaciones where fecha between '$desde' and '$hasta' 
			and nrocedula=m.nrocedula AND DAYOFWEEK(fecha) IN (2,3,4,5,6) AND permiso = '') as sin_marcar,FORMAT(@sin_marcar/2,0)  as sin_marcacion,
			(select count(*) from marcaciones where fecha between '$desde' and '$hasta' and nrocedula=m.nrocedula AND DAYOFWEEK(fecha) IN (2,3,4,5,6) AND permiso = '' AND falta='true') as no_vino,
			(select count(*) from marcaciones where fecha between '$desde' and '$hasta' and nrocedula=m.nrocedula AND DAYOFWEEK(fecha) IN (2,3,4,5,6) AND permiso = '' AND (marcaEntrada='' or marcaSalida='') and falta='') as no_marco,
			if((@tardia-3)<0,@tres_faltas:=0,@tres_faltas:=2) as calculo,if((@tardia-3)<0,@res_tardia:=0,@res_tardia:=@tardia-3) as resta,
			@cantidad:=@res_tardia+@tres_faltas+@sin_marcar as suma,
			case when (@cantidad/2)>=1 and (@cantidad/2)<2 then '1 jornal' 
        		when (@cantidad/2)>=2 and (@cantidad/2)<3 then '2 jornales' 
        		when (@cantidad/2)>=3 and (@cantidad/2)<4 then '3 jornales' 
        		when (@cantidad/2)>=4 and (@cantidad/2)<5 then '4 jornales'
        		when (@cantidad/2)>=5 and (@cantidad/2)<6 then '5 jornales'
       		 	when (@cantidad/2)>=6 and (@cantidad/2)<7 then '6 jornales'
        		when (@cantidad/2)>=7 and (@cantidad/2)<8 then '7 jornales'
        		when (@cantidad/2)>=8 and (@cantidad/2)<9 then '8 jornales'
        		when (@cantidad/2)>=9 and (@cantidad/2)<10 then '9 jornales'
        		when (@cantidad/2)>=10 and (@cantidad/2)<11 then '10 jornales'
        		when (@cantidad/2)>=11 and (@cantidad/2)<12 then '11 jornales'
        		when (@cantidad/2)>=12 and (@cantidad/2)<13 then '12 jornales'
        		when (@cantidad/2)>=13 and (@cantidad/2)<14 then '13 jornales'
        		when (@cantidad/2)>=14 and (@cantidad/2)<15 then '14 jornales'
        		when (@cantidad/2)>=15 and (@cantidad/2)<16 then '15 jornales'
        		when (@cantidad/2)>=16 and (@cantidad/2)<17 then '16 jornales'
        		when (@cantidad/2)>=17 and (@cantidad/2)<18 then '17 jornales'
        		when (@cantidad/2)>=18 and (@cantidad/2)<19 then '18 jornales'
        		when (@cantidad/2)>=19 and (@cantidad/2)<20 then '19 jornales'
        		when (@cantidad/2)>=20 and (@cantidad/2)<21 then '20 jornales'
				when (@cantidad/2)>=21 and (@cantidad/2)<22 then '21 jornales'
				when (@cantidad/2)>=22 and (@cantidad/2)<23 then '22 jornales'
        		end as sancion  
			from marcaciones m where m.fecha between '$desde' and '$hasta'
			AND DAYOFWEEK(m.fecha) IN (2,3,4,5,6) AND permiso = '' group by nrocedula";
}