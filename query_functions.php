<?php

function create_posts_table_if_not_exist($pdo) {
    $req = "create table if not exists posts (".
        "id int primary key auto_increment, ".
        "title tinytext not null, ".
        "content text not null)";
    make_request($pdo, $req);
}

function get_post($pdo, $id) {
    $req = "select id, title, content from posts where id=$id";
    $sql = make_request($pdo, $req);
    return $sql->fetch();
}

function get_posts($pdo, $offset, $limit) {
    $req = "select id, title, content from posts limit $limit offset $offset";
    $sql = make_request($pdo, $req);
    return $sql->fetchAll();
}

function insert_post($pdo, $title, $content) {
    $req = "insert into posts values (null, '$title','$content')";
    make_request($pdo, $req);
}

function update_post($pdo, $id, $title, $content) {
    $req = "update posts set title='$title', content='$content' where id=$id";
    make_request($pdo, $req);
}

function delete_post($pdo, $id) {
    $req = "delete from posts where id=$id";
    make_request($pdo, $req);
}

function make_request($pdo, $req) {
    $sql = $pdo->prepare($req);
    $sql->execute();
    return $sql;
}