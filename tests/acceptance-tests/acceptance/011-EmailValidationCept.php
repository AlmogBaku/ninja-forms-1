<?php
$I = new AcceptanceTester( $scenario );

$I->wantTo( 'make sure required fields are working' );
// Login to wp-admin
$I->loginAsAdmin();

if ($I->haveWordPressDatabaseUpdates()) {
    $I->click('Update WordPress Database');
    $I->wait(5);
}

$I->amOnPage( '/?nf_preview_form=1' );
$I->waitForElementVisible( '.nf-form-content', 30 );

$I->fillField( 'Email', 'me.net' );

$I->click( 'Submit' );
$I->wait( 1 );
$I->see( 'Please enter a valid email address!' );