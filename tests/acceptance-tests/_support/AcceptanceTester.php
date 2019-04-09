<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * addAField function adds a single field in the Ninja Forms builder.
    * $textIdentity expects a string and wants the text name of the field in the drawer.
    * $dataIdentity expects a string and wants the data-id attribute of the element.
    * $addedElement expects a string and wants the class of the added element to the builder.
    * $needToScroll expects a boolean and  is used for if you need to scroll 
    * to access the field in the builder drawer.
    */
    public function addAField( $textIdentity, $dataIdentity, $needToScroll ){
        $I = $this;
        if($needToScroll){
            $I->executeJS( "jQuery( '#nf-drawer' ).scrollTop( 600 );" );
            $I->waitForText( $textIdentity );
            $I->click( '[data-id="' . $dataIdentity . '"]');
            $I->waitForElement( 'div.' . $dataIdentity . ' span.nf-field-label');
        }else{
            $I->waitForText( $textIdentity );
            $I->click( '[data-id="' . $dataIdentity . '"]');
            $I->waitForElement( '.' . $dataIdentity );
        }
    }

}
