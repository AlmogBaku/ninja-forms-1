<?php

return [

    // [
    //     'uid' => 'firstFormReviewOrSupport',
    //     'metric' => 'formCount',
    //     'threshold' => 2, // Accounting for the default contact form, a form count of 2 means the user created a form.
    //     'title' => 'Love using Ninja Forms?',
    //     'message' => 'Your support helps us grow and continue adding new features. Please consider leaving us a 5 Star Review on WordPress.org!',
    //     'links' => '<li><a href="#">Yes, Ninja Forms is pretty awesome!</a></li>' .
    //                '<li><a href="#">Actually, I could use a hand!</a></li>'
    // ],

    // [
    //     'metric' => 'formCount',
    //     'threshold' => 2,
    //     'message' => 'How\'s it going? If you\'re loving using Ninja Forms, please let us know by leaving a 5 Star Review on WordPress.org. Thanks for your support!',
    // ],

    [
        'uid' => 'firstSubmissionsReview',
        'metric' => 'submissionCount',
        'threshold' => 1,
        'title' => 'How\' it going?',
        'message' => 'Loving Ninja Forms? If you have an extra 60 seconds, showing your support with a 5 star review helps us grow!',  
        'links' => '
            <li><a href="#">Checkout our documentation</a></li>
            <li><a href="#">Get help</a></li>
            <li><a href="#">Dismiss</a></li>
        ',
    ],

    // [
    //     'metric' => 'formDisplayCount',
    //     'threshold' => 1,
    //     'message' => 'Lots of people looking at your forms!',
    // ],
    
];
