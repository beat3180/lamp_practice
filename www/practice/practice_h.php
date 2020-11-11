<?php

//エスケープ
function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
