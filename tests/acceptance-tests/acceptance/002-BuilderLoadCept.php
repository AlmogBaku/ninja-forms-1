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
$I->click('#optout');

// $I->amOnPage( '/wp-admin/admin.php?page=ninja-forms&form_id=new' );
$I->wantTo( 'confirm that the builder loads properly' );
$I->click('button.add');
$I->waitForText( 'Emails & Actions' );
