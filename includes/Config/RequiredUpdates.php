<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_required_updates', array(

//	'OrphanSubs' => array(
//		'class' => 'NF_Updates_OrphanSubs',
//		'requires' => array(),
//	),
	'CCActions' => array(
		'class' => 'NF_Updates_CCActions',
		'requires' => array(),
	),
//	'CCFields' => array(
//		'class' => 'NF_Updates_CCFields',
//		'requires' => array(),
//	),

));
