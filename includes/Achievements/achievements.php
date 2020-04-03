<?php

return [

    [
        'metric' => 'formCount',
        'threshold' => 2, // Accounting for the default contact form, a form count of 2 means the user created a form.
        'message' => 'Love using Ninja Forms? Your support helps us grow and continue adding new features. Please consider leaving us a 5 Star Review on WordPress.org!',
    ],

    [
        'metric' => 'formCount',
        'threshold' => 2,
        'message' => 'How\'s it going? If you\'re loving using Ninja Forms, please let us know by leaving a 5 Star Review on WordPress.org. Thanks for your support!',
    ],

    [
        'metric' => 'submissionCount',
        'threshold' => 1,
        'message' => 'Congratulations on your first form submission! Loving Ninja Forms? If you have an extra 60 seconds, showing your support with a 5 star review helps us grow!',  
    ],

    [
        'metric' => 'formDisplayCount',
        'threshold' => 1,
        'message' => 'Lots of people looking at your forms!',
    ],
    
];
