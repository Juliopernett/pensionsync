<?php
include '../../../core.php';
require_once("../../../login/Login.php");

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("../../../lib/password_compatibility_library.php");
}

$errors = [];
$messages = [];

if (empty($_POST['firstname'])) {
    $errors[] = "Nombres o razón social vacío";
} elseif (empty($_POST['user_name'])) {
    $errors[] = "Nombre de usuario vacío";
} elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
    $errors[] = "Contraseña vacía";
} elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
    $errors[] = "La contraseña y la repetición de la contraseña no son lo mismo";
} elseif (strlen($_POST['user_password_new']) < 6) {
    $errors[] = "La contraseña debe tener como mínimo 6 caracteres";
} elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
    $errors[] = "Nombre de usuario no puede ser inferior a 2 o más de 64 caracteres";
} elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
    $errors[] = "Nombre de usuario no encaja en el esquema de nombre: Sólo a-z y los números están permitidos, de 2 a 64 caracteres";
} elseif (empty($_POST['user_email'])) {
    $errors[] = "El correo electrónico no puede estar vacío";
} elseif (strlen($_POST['user_email']) > 64) {
    $errors[] = "El correo electrónico no puede ser superior a 64 caracteres";
} elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Su dirección de correo electrónico no está en un formato de correo electrónico válida";
} elseif (empty($_POST['perfil'])) {
    $errors[] = "El perfil no puede estar vacío";
} elseif (!empty($_POST['user_name']) && !empty($_POST['firstname']) && strlen($_POST['user_name']) <= 64 && strlen($_POST['user_name']) >= 2 && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name']) && !empty($_POST['user_email']) && strlen($_POST['user_email']) <= 64 && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['user_password_new']) && !empty($_POST['user_password_repeat']) && ($_POST['user_password_new'] === $_POST['user_password_repeat'])) {

    require_once("../../../config/db.php");
    require_once("../../../config/conexion.php");

    // Escapando datos
    $firstname = mysqli_real_escape_string($con, strip_tags($_POST["firstname"], ENT_QUOTES));
    $lastname = mysqli_real_escape_string($con, strip_tags($_POST["lastname"], ENT_QUOTES));
    $user_name = mysqli_real_escape_string($con, strip_tags($_POST["user_name"], ENT_QUOTES));
    $user_email = mysqli_real_escape_string($con, strip_tags($_POST["user_email"], ENT_QUOTES));
    $user_password = $_POST['user_password_new'];
    $date_added = date("Y-m-d H:i:s");
    $perfil = mysqli_real_escape_string($con, strip_tags($_POST["perfil"], ENT_QUOTES));

    $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE user_name = '$user_name' OR user_email = '$user_email'";
    $query_check_user_name = mysqli_query($con, $sql);

    if (mysqli_num_rows($query_check_user_name) > 0) {
        $errors[] = "Lo sentimos, el nombre de usuario ó la dirección de correo electrónico ya está en uso.";
    } else {
        $sql = "INSERT INTO users (firstname, lastname, user_name, user_password_hash, user_email, date_added, perfil)
                VALUES('$firstname', '$lastname', '$user_name', '$user_password_hash', '$user_email', '$date_added', '$perfil')";
        $query_new_user_insert = mysqli_query($con, $sql);

        if ($query_new_user_insert) {
            $messages[] = "La cuenta ha sido creada con éxito.";
        } else {
            $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
        }
    }
}

if (!empty($errors)) {
    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>';
    foreach ($errors as $error) {
        echo $error;
    }
    echo '</div>';
}

if (!empty($messages)) {
    echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>¡Bien hecho!</strong>';
    foreach ($messages as $message) {
        echo $message;
    }
    echo '</div>';
}
?>
