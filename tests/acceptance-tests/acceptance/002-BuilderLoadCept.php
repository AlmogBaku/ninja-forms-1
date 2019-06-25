<?php
$I = new AcceptanceTester( $scenario );

// Login to wp-admin
$I->loginAsAdmin();

$I->wantTo( 'confirm that the dashboard loads properly' );
$I->amOnPage( '/wp-admin/admin.php?page=ninja-forms' );
$I->waitForText( 'Add New' );

// $I->amOnPage( '/wp-admin/admin.php?page=ninja-forms&form_id=new' );
$I->wantTo( 'confirm that the builder loads properly' );
$I->click('button.add');
$I->waitForText( 'Emails & Actions' );
