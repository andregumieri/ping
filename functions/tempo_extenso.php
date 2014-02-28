<?php 
	function tempo_extenso($tempo_num) {
		$escalas = array(
			"dia" => 60*60*24,
			"hora" => 60*60,
			"minuto" => 60, 
			"segundo" => 1
		);

		$tempo_extenso = "";
		$resto = $tempo_num;
		foreach($escalas as $escala=>$segundos) {
			$tempo = intval($resto/$segundos);
			if($tempo>0) {
				$resto -= $tempo*$segundos;	
				$tempo_extenso .= ' ' . $tempo . ' ' . $escala;
				if($tempo>1) $tempo_extenso.='s';
			}
		}
		$tempo_extenso = substr($tempo_extenso, 1);

		return $tempo_extenso;
	}
?>