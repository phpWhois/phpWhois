<?php
function ascii127 ( $str )
{
return strtr($str,"байинмутъщьс'БАЙИНМУТЪЩЬєЄ","aaeeiioouuun AAEEIIOOUUUoa");
}
?>