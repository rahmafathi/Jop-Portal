<?php

$host_name = "localhost";
$user_name = "root";
$password = "";
$db = "job_portal";

$conn = mysqli_connect($host_name, $user_name, $password, $db);

// ===================== INSERT =====================
function insert($conn, $post, $table)
{
    $columns = [];
    $values = [];

    foreach ($post as $key => $value) {
        $columns[] = $key;
        $values[] = "'" . $value . "'";
    }

    $columns_string = implode(",", $columns);
    $values_string = implode(",", $values);

    if (mysqli_query($conn, "INSERT INTO $table ($columns_string) VALUES ($values_string)")) {
        return true;
    }

    return false;
}

// ===================== SELECT ALL =====================
function select($conn, $table)
{
    $rows = mysqli_query($conn, "SELECT * FROM $table");

    if ($rows && mysqli_num_rows($rows) > 0) {
        return mysqli_fetch_all($rows, MYSQLI_ASSOC);
    }

    return [];
}

// ===================== SELECT ONE =====================
function selectOne($conn, $table, $id)
{
    $row = mysqli_query($conn, "SELECT * FROM $table WHERE id = $id");

    if ($row && mysqli_num_rows($row) > 0) {
        return mysqli_fetch_assoc($row);
    }

    return [];
}

// ===================== LOGIN =====================
// function login($conn, $email, $password)
// {
//     $row = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$password'");

//     if ($row && mysqli_num_rows($row) > 0) {
//         return mysqli_fetch_assoc($row);
//     }

//     return [];
// }
function login($conn, $email, $password)
{
    $email = mysqli_real_escape_string($conn, $email);
<<<<<<< HEAD

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            return $user;
        }

=======

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if ($result && mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            return $user;
        }
>>>>>>> 5cd5b3393caac3fbee9071936658e148114c5bf8
    }

    return [];
}
// ===================== UPDATE =====================
function update($conn, $post, $table, $id)
{
    $fieldValue = [];

    foreach ($post as $key => $value) {
        $fieldValue[] = "$key = '$value'";
    }

    $fieldValueString = implode(",", $fieldValue);

    if (mysqli_query($conn, "UPDATE $table SET $fieldValueString WHERE id = $id")) {
        return true;
    }

    return false;
}

// ===================== DELETE =====================
function delete($conn, $table, $id)
{
    if (mysqli_query($conn, "DELETE FROM $table WHERE id = $id")) {
        return true;
    }

    return false;
}

// ===================== SELECT WHERE =====================
function selectWhere($conn, $table, $column, $value)
{
    $rows = mysqli_query($conn, "SELECT * FROM $table WHERE $column = '$value'");

    if ($rows && mysqli_num_rows($rows) > 0) {
        return mysqli_fetch_all($rows, MYSQLI_ASSOC);
    }

    return [];
}

?>