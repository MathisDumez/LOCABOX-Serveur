<?php
$user_input_password = 'test1234';
$hash = password_hash($user_input_password, PASSWORD_ARGON2I);
echo "Hash Argon2i pour test1234 : " . $hash;

$stored_hash = '$argon2i$v=19$m=65536,t=4,p=1$akJVdnJVb1hHcDBIcFl5Rg$WK4nU2k4gBKVNps0SrYGOs0xU5zoppOIyxO/0dPUk4s'; // Mot de passe haché stocké dans la base de données

if (password_verify($user_input_password, $stored_hash)) {
    echo 'Mot de passe valide!';
} else {
    echo 'Mot de passe invalide!';
}
?>