<?php

// reserved words
// request type:  
// reqType
    // can be:
    // ajax -> ajaxController
        // POST 
            // action
        // GET 
            // action
        // POST GET REQ
    // pdf/api
    // etc

$A = ['a', 'b', 'c'];

// $B = new Queue($A);
// while(!$B->isEmpty()){
//     $b = $B->next();
//     echo "{$b}";
//     echo '<br>';
// }
// echo 'Done';

$B = $A;
$C = &$A;

$A[1] = 'FOO';

print_r($B);
echo '<br>';
print_r($C);
echo '<br>';

$C[0] = "BAR";

print_r($B);
echo '<br>';
print_r($A);

echo '<pre>';
print_r($_SERVER);
echo '</pre>';

