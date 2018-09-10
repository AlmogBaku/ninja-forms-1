<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_required_updates', array(

//	'OrphanSubs' => array(
//		'class_name' => 'NF_Updates_OrphanSubs',
//		'requires' => array(),
//	),
    'CacheCollateForms' => array(
        'class_name' => 'NF_Updates_CacheCollateForms',
        'requires' => array(),
    ),
//	'CacheCollateActions' => array(
//		'class_name' => 'NF_Updates_CacheCollateActions',
//		'requires' => array( 'CacheCollateForms' ),
//	),
	'CacheCollateFields' => array(
		'class_name' => 'NF_Updates_CacheCollateFields',
		'requires' => array(),
	),

));
