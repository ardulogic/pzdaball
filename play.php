<?php
require_once 'bootstrap.php';

$form = [
    'fields' => [],
    'buttons' => [],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];

$show_form = true;

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);

    if ($form_success) {
        $success_msg = 'Sėkmingai pasijungei į komandą!';
        $show_form = false;
    }
}
?>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <title>PZ2ABALL | Play</title>
    </head>
    <body>
        <!-- Navigation -->    
        <?php require 'objects/navigation.php'; ?>        

        <!-- Content -->       
        <h1>Išbėk į aikštelę</h1>
        <?php if ($show_form): ?>
            <!-- Form -->        
            <?php require 'objects/form.php'; ?>
        <?php else: ?>
            <h2>Zašibys!</h2>
            <h3><?php print $success_msg; ?></h3>
        <?php endif; ?>
    </body>
</html>