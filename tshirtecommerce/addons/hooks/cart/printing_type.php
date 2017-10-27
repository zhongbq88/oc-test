<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: November 26 2015
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
$data			= $GLOBALS['data'];
$lang			= $GLOBALS['lang'];
$dg				= $GLOBALS['dg'];

if (isset($data['print_type']))
{
	$printing = $dg->getPrintingType($data['print_type']);
	if (isset($printing->title)) {
		$title	= $printing->title;
	}
	else {
		$print_type_text = lang($data['print_type'], true);
		if ($print_type_text == null || empty($print_type_text))
			$title	= $data['print_type'];
		else
			$title	= $print_type_text;
	}
	
	$result = $params['result'];
	$result->options[] = array(
		'name' => $lang['designer_printing_type'],
		'type' => 'printing',
		'value' => $title,
	);
	$GLOBALS['result'] = $result;
}
?>