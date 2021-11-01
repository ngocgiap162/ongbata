<?php 
$weekday = date("l");
$weekday = strtolower($weekday);
switch($weekday) {
	case 'monday':
		$weekday = 'Thứ hai';
		break;
	case 'tuesday':
		$weekday = 'Thứ ba';
		break;
	case 'wednesday':
		$weekday = 'Thứ tư';
		break;
	case 'thursday':
		$weekday = 'Thứ năm';
		break;
	case 'friday':
		$weekday = 'Thứ sáu';
		break;
	case 'saturday':
		$weekday = 'Thứ bảy';
		break;
	default:
		$weekday = 'Chủ nhật';
		break;
}
?>