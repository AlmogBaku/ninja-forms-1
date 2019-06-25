<?php
$I = new AcceptanceTester( $scenario );

// Login to wp-admin
$I->loginAsAdmin();

$I->wantTo( 'confirm that the dashboard loads properly' );
$I->amOnPage( '/wp-admin/plugins.php' );
$I->see( 'Ninja Forms' );
$I->see( 'Ninja Forms is a webform builder with unparalleled ease of use and features.' );

$I->wantTo( 'confirm that the dashboard loads properly' );
$I->amOnPage( '/wp-admin/admin.php?page=ninja-forms' );
$I->waitForText( 'Add New' );
