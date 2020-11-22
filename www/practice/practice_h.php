<?php

//エスケープ
function h($s){
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$html = h($s);
$hello = 'hello';

print $hello;





?>
