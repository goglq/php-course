<?php
require_once('user_input_functions.php');

$pdo = new PDO("mysql:host=localhost;dbname=php_test_db", 'root', 'qwerty', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

create_posts_table_if_not_exist($pdo);

while(true) {
    echo 'Actions: find | find many | create | edit | remove | exit', "\n";
    $action = readline("Choose Action: ");
    switch($action) {
        case 'f':
        case 'find':
            $post = find_post($pdo);
            echo "\n";
            print_post($post);
            echo "\n";
            break;
        case 'fm':
        case 'find many':
            $posts = find_many_posts($pdo);
            echo "\n";
            print_posts($posts);
            echo "\n";
            break;
        case 'c':
        case 'create':
            create_post($pdo);
            break;
        case 'e':
        case 'edit':
            edit_post($pdo);
            break;
        case 'r':
        case 'remove':
            remove_post($pdo);
            break;
        case 'q':
        case 'quit':
        case 'exit':
            exit("bye bye");
            break;
        default:
            echo "Action [$action] is unknown\n";
    }
}