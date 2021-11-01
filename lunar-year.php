<?php
$nam_dl = '2021';

$mang_can = array("Quý", "Giáp", "Ất", "Bính", "Đinh", "Mậu", "Kỉ", "Canh", "Tân", "Nhâm");
$mang_chi = array("Hợi", "Tý", "Sửu", "Dần", "Mão", "Thìn", "Tỵ", "Ngọ", "Mùi", "Thân", "Dậu", "Tuất");

$nam_dl = $nam_dl - 3;
$can = $nam_dl % 10;
$chi = $nam_dl % 12;

$nam_al = $mang_can[$can] . " " . $mang_chi[$chi];
echo $nam_al;