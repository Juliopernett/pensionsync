<?php
function paginate($reload, $page, $tpages, $adjacents) {
	$prevlabel = "&lsaquo; Prev";
	$nextlabel = "Next &rsaquo;";
	$out = '<ul class="pagination pagination-large">';

	if($page == 1) {
		$out.= "<li class='disabled'><a>$prevlabel</a></li>";
	} else {
		$out.= "<li><a href='javascript:void(0);' onclick='load(".($page-1).")'>$prevlabel</a></li>";
	}
	
	// Primera página
	if($page > ($adjacents + 1)) {
		$out.= "<li><a href='javascript:void(0);' onclick='load(1)'>1</a></li>";
	}
	
	// Intervalo
	if($page > ($adjacents + 2)) {
		$out.= "<li><a>...</a></li>";
	}

	// Páginas intermedias
	$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
	$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
	for($i = $pmin; $i <= $pmax; $i++) {
		if($i == $page) {
			$out.= "<li class='active'><a>$i</a></li>";
		} else {
			$out.= "<li><a href='javascript:void(0);' onclick='load(".$i.")'>$i</a></li>";
		}
	}

	// Intervalo
	if($page < ($tpages - $adjacents - 1)) {
		$out.= "<li><a>...</a></li>";
	}

	// Última página
	if($page < ($tpages - $adjacents)) {
		$out.= "<li><a href='javascript:void(0);' onclick='load($tpages)'>$tpages</a></li>";
	}

	// Siguiente
	if($page < $tpages) {
		$out.= "<li><a href='javascript:void(0);' onclick='load(".($page+1).")'>$nextlabel</a></li>";
	} else {
		$out.= "<li class='disabled'><a>$nextlabel</a></li>";
	}
	
	$out.= "</ul>";
	return $out;
}
?>