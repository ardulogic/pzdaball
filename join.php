<?php
require_once 'bootstrap.php';

function form_success($safe_input, $form) {
    $team_idx = $safe_input['team'];
    $player = [
        'nick_name' => $safe_input['nick'],
        'score' => 0
    ];

    if (file_exists(STORAGE_FILE)) {
        $teams_array = file_to_array(STORAGE_FILE);
        $teams_array[$team_idx]['players'][] = $player;
        return array_to_file($teams_array, STORAGE_FILE);
    }
}

function validate_nick($field_input, &$field, $form_input) {
    $team_idx = $form_input['team'];
    if (file_exists(STORAGE_FILE)) {
        $teams_array = file_to_array(STORAGE_FILE);
        $player_team = $teams_array[$team_idx];

        foreach ($player_team['players'] as $player) {
            if ($player['nick_name'] == $field_input) {
                $field['error_msg'] = strtr('Pz2aball žaidėjas '
                        . 'komandoje "@team_name" '
                        . 'su nick`u "@nick" jau egzistuoja!', [
                    '@team_name' => $player_team['team_name'],
                    '@nick' => $player['nick_name']
                ]);
                return false;
            }
        }
    }

    return true;
}

function get_team_names() {
    if (file_exists(STORAGE_FILE)) {

        $teams_array = file_to_array(STORAGE_FILE);
        return array_column($teams_array, 'team_name');
    }
    return [];
}

$form = [
    'fields' => [
        'team' => [
            'label' => 'Choose team',
            'type' => 'select',
            'validate' => [
                'validate_not_empty',
            ],
            'options' => get_team_names()
        ],
        'nick' => [
            'label' => 'nick team',
            'type' => 'text',
            'placeholder' => 'Nickname',
            'validate' => [
                'validate_not_empty',
                'validate_nick'
            ],
        ]
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Create'
        ]
    ],
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
        <title>PZ2ABALL | Join Team</title>
    </head>
    <body>
        <!-- Navigation -->    
        <?php require 'objects/navigation.php'; ?>        

        <!-- Content -->       
        <h1>Join a PZ2ABALL team!</h1>
        <?php if ($show_form): ?>
            <!-- Form -->        
            <?php require 'objects/form.php'; ?>
        <?php else: ?>
            <h2>Zašibys!</h2>
            <h3><?php print $success_msg; ?></h3>
        <?php endif; ?>
    </body>
</html>