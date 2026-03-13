<?php 
// PHP 8.X versión moderna
// 1.  TIPOS ESTRICTOS

//
 // symfony trabaja con clases y servicios que usan tipos en todos lados

 declare(strict_types=1);

 // esto hace que php verifique los tipos correctamente

 // Ejemplo simple


 function sumarInt(int $a, int $b): int {
    return $a + $b;
 }

 function sumar($a, $b){
    return $a + $b;
 }

 echo sumarInt(2, 3); 

?>