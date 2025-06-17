<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nombres, apellidos, email, usuario, rol FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<div class="header">
    <h1 class="page-title">Perfil</h1>
</div>

<div class="profile-box">
    <h2><?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?></h2>
    <p><strong>Usuario:</strong> <?= htmlspecialchars($user['usuario']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
</div>
