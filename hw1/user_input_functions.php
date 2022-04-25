<?php

require_once('query_functions.php');

function print_posts($posts) {
    foreach ($posts as $post) {
        print_post($post);
    }
}

function print_post($post) {
    echo $post["id"].' '.$post["title"].' '.$post["content"], "\n";
}

function input_post_data() {
    $title = readline("Post title: ");
    $content = readline("Post content: ");

    return ['title' => $title, 'content' => $content];
}

function create_post($pdo) {
    $input = input_post_data();

    insert_post($pdo, $input['title'], $input['content']);
}

function edit_post($pdo) {
    $id = readline("Post Id: ");

    $post = get_post($pdo, $id);

    print_post($post);

    $input = input_post_data();

    $title = empty($input['title']) ? $post['title'] : $input['title'];
    $content = empty($input['content']) ? $post['content'] : $input['content'];

    update_post($pdo, $id, $title, $content);
}

function remove_post($pdo) {
    $id = readline("Post Id: ");

    delete_post($pdo, $id);
}

function find_post($pdo) {
    $id = readline("Post Id: ");

    $post = get_post($pdo, $id);

    return $post;
}

function find_many_posts($pdo) {
    $offset = readline("Number of posts to skip: ");
    $limit = readline("Number of posts to fetch: ");
    $posts = get_posts($pdo, $offset, $limit);
    return $posts;
}