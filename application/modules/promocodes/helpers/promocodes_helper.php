<?php defined('BASEPATH') OR exit('No direct script access allowed');

function promocodesTabAsStatus($status = 'active') {
	$statuses = [
		'active'  => 'Active',
		'expired' => 'Expired',
	];

	$html = '<ul class="nav nav-tabs admintab">';
	foreach ($statuses as $key => $label) {
		$html .= '<li><a href="' . Backend_URL . "promocodes?status={$key}\"";
		$html .= ($key == $status) ? ' class="active"' : '';
		$html .= '>' . $label . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}

function promocodesTabs($id, $active_tab) {
	$html = '<ul class="tabsmenu">';
	$tabs = [
                'read'   => 'Details',
                'update' => 'Update',
                'delete' => 'Delete',
            ];

	foreach ($tabs as $link=>$tab) {
		$html .= '<li><a href="' . Backend_URL ."promocodes/{$link}/{$id}\"";
		$html .= ($link == $active_tab ) ? ' class="active"' : '';
		$html .= '>'. $tab . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}


function promoAmount($amount, $discount_type) {

	if ($discount_type == 'Percentage') {
		return $amount . '%';
	} else {
		return GBP($amount);
	}
	
}