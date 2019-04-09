<?php

$I = new AcceptanceTester( $scenario );

$I->wantTo("Check all of the fields submissions.");

$I->loginAsAdmin();

//Navigate to the form builder


//Array of fields

$FieldTypes = array(
    //Name of field
    'Address' => array(
        //class of field and whether you need to scroll or not
        'address' => false,
        //method for filling the field
        'method' => '$I->fillField("." . $className , "1234 Test Lane");',
        //What is the type of the submission edit dialog
        'type' => 'input',
        //The data you submitted in the form
        'data' => '1234 Test Lane'
    ),
    'Checkbox List' => array( 
        'listcheckbox' => false,
        'method' => '$I->checkOption(\'input[type="checkbox"][value="one"]\'); 
                     $I->checkOption(\'input[type="checkbox"][value="two"]\');',
        'type' => 'input[type="checkbox"]',
        'data' => array('one', 'two')
    ),
    'City' => array(
        'city' => false,
        'method' => '$I->fillField("." . $className , "Cactiville");',
        'type' => 'input',
        'data' => 'Cactiville'
    ),
    'Country' => array(
        'listcountry' => false,
        'method' => '$I->selectOption("." . $className, "United States");',
        'type' => 'select',
        'data' => 'United States'
    ),
    'Date' => array(
        'date' => false,
        'method' => '$I->fillField( ".pikaday__display", "02/02/2025"  );',
        'type' => 'input',
        'data' => '02/02/2025'
    ),
    'Email' => array(
        'email' => false,
        'method' => '$I->fillField("." . $className , "test@test.com");',
        'type' => 'input',
        'data' => 'test@test.com'
    ),
    'First Name' => array(
        'firstname' => false,
        'method' => '$I->fillField("." . $className , "Jerry");',
        'type' => 'input',
        'data' => 'Jerry'
    ),
    'Hidden' => array(
        'hidden' => true,
        'type' => 'input',
        'data' => 'secret'
    ),
    'Last Name' => array(
        'lastname' => false,
        'method' => '$I->fillField("." . $className , "Seinfeld");',
        'type' => 'input',
        'data' => 'Seinfeld'
    ),
    'Multi-Select' => array(
        'listmultiselect' => false,
        'method' => '$I->selectOption(".listmultiselect", array("One", "Two", "Three"));',
        'type' => 'select',
        'data' => array('One', 'Two', 'Three')
    ),
    'Number' => array(
        'number' => true,
        'method' => '$I->fillField("." . $className , "5");',
        'type' => 'input',
        'data' => '5'
    ),
    'Paragraph Text' => array(
        'textarea' => false,
        'method' => '$I->fillField("." . $className , "Hello, this is a sentence.");',
        'type' => 'textarea',
        'data' => 'Hello, this is a sentence.'
    ),
    'Phone' => array(
        'phone' => true,
        'method' => '$I->fillField("." . $className , "8675309");',
        'type' => 'input',
        'data' => '8675309'
    ),
    'Radio List' => array(
        'listradio' => false,
        'method' => '$I->click( "input[type=\'radio\'][value=\'three\']");',
        'type' => 'select',
        'data' => 'Three'
    ),
    'Select' => array(
        'listselect' => false,
        'method' => '$I->selectOption("." . $className, "Two");',
        'type' => 'select',
        'data' => 'Two'
    ),
    'Single Checkbox' => array(
        'checkbox' => false,
        'method' => '$I->checkOption(".$className");',
        'type' => 'input[type="checkbox"]',
        'data' => 'Checked'
    ),
    'Single Line Text' => array(
        'textbox' => false,
        'method' => '$I->fillField("." . $className , "Mungo Jerry Summertime");',
        'type' => 'input',
        'data' => 'Mungo Jerry Summertime'
    ),
    'Star Rating' => array(
        'starrating' => true,
        'method' => '$I->click(".star[title=\'5\']");',
        'type' => 'input',
        'data' => '5'
    ),
    'US States' => array(
        'liststate' => true,
        'type' => 'select',
        'data' => '- Select State -'
    ),
    'Zip' => array(
        'zip' => true,
        'method' => '$I->fillField("." . $className , "86753");',
        'type' => 'input',
        'data' => '86753'
    )
);

$I->amOnPage( '/wp-admin/admin.php?page=ninja-forms&form_id=new' );
$scroll = 100;

//Adds each field in the array to the form builder
foreach($FieldTypes as $key => $value){
    foreach($value as $key2 => $value2){
        if($key2 != 'data' && $key2 != 'method' && $key2 != 'type'){

            $I->addAField($key, $key2, $value2);

            $I->executeJS( "jQuery( '#nf-main' ).scrollTop( " . $scroll . " );" );
            $scroll += 200;

            if($key != 'US States'){

                $I->click('div.' . $key2 . ' span.nf-field-label');

                if($key == 'Hidden'){
                    $I->fillField('#default', 'secret');
                    $I->click('a[title="Done"]');
                    $I->wait(1);
                    $I->click('[data-drawerid="addField"]');  
                }else{
                    $I->executeJS('jQuery("h3.toggle:contains(\'Display\')").attr("id", "temporary");');
                    $I->click('#temporary');
                    $I->fillField('#element_class', $key2);
                    $I->click('a[title="Done"]');
                    $I->wait(1);
                    $I->click('[data-drawerid="addField"]');
                }

            }
        }
    }
}


//Navigate to the form to fill the fields
$I->click( '.nf-close-drawer' );

$I->waitForText('PUBLISH');
$I->click( 'PUBLISH', '.nf-app-buttons span' );
$I->waitForElement( '#title' );
$I->fillField( '#title', 'Stanky New Title' );
$I->click( '.publish', '#nf-drawer' );
$I->wait(5);

$I->click('.preview');

$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
    $handles=$webdriver->getWindowHandles();
    $last_window = end($handles);
    $webdriver->switchTo()->window($last_window);
});

$I->waitForElementVisible( '.nf-form-content', 30 );

//Fill each field based on the method you call
foreach($FieldTypes as $key => $value){
    $className = '';
    foreach($value as $key2 => $value2){
        if($key2 != 'method' && $key2 != 'data' &&  $key2 != 'type'){
            $className = $key2;
        }
        if($key2 == 'method'){
            
            eval( $value2 );
            if($key != 'Hidden' && $key != 'Single Checkbox' && $key != 'Checkbox List' && $key != 'Radio List' && $key != 'Star Rating' && $key != 'US States'){
                $I->scrollTo(['css' => '.' . $className], 200);
            }
        }
    }
}


//submit the form
$I->click('Submit');
$I->wait( 5 );

$I->amOnPage('/wp-admin/edit.php?post_status=all&post_type=nf_sub&form_id=2&paged=1');

$I->executeJS( 'jQuery( ".row-actions" ).removeClass( "row-actions" );' );
$I->click( 'span.edit > a' );
$I->wait(2);
$I->scrollTo(['css' => '.inside']);

// $I->wait(25);
$keyType = '';
$fieldNum = 5;

//Check for each field in the submissions
foreach($FieldTypes as $key => $value){  
    foreach($value as $key2 => $value2){   
          if($key2 == 'type'){
               $keyType = $value2;
          }
          if($key2 == 'data'){
               if($key == 'Checkbox List'){
                    for($j = 0; $j < sizeof($value2); $j++){
                         $I->seeCheckboxIsChecked( $keyType . '[name="fields[' . $fieldNum . '][]"][value="' . $value2[$j] . '"]');
                    }
               }elseif($key == 'Single Checkbox'){
                    $I->seeCheckboxIsChecked( $keyType . '[name="fields[' . $fieldNum . ']"]');
               }elseif($keyType == 'select'){
                    if(gettype($value2) == 'array'){
                         for($j = 0; $j < sizeof($value2); $j++){
                              
                              $I->seeOptionIsSelected( $keyType . '[name="fields[' . $fieldNum . '][]"]', $value2[$j]);
                         }
                    }else{
                         $I->seeOptionIsSelected( $keyType . '[name="fields[' . $fieldNum . ']"]', $value2);
                    }
               }else{
                    $I->seeInField($keyType . '[name="fields[' . $fieldNum . ']"]', $value2);
               }
          }
     }
     $fieldNum += 1;
}