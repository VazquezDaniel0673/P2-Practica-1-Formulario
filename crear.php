<?php
session_start();
require_once 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $pass = $_POST['pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($pass !== $confirm_pass) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $pass_hashed = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $email, $pass_hashed);

        if ($stmt->execute()) {
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "El usuario o correo ya está registrado.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Carhartt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto Mono', monospace; padding: 40px 20px; max-width: 700px; margin: 0 auto; text-align: center; }
        header h1 { font-size: 2rem; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 20px; }
        nav ul { display: flex; justify-content: center; gap: 30px; list-style: none; margin-bottom: 40px; }
        nav a { color: #666; text-decoration: none; font-size: 0.85rem; text-transform: uppercase; }
        form { max-width: 400px; margin: 0 auto; display: flex; flex-direction: column; gap: 10px; text-align: left; }
        input { padding: 10px; border: 1px solid #111; font-family: 'Roboto Mono', monospace; }
        button { padding: 12px; font-family: 'Roboto Mono', monospace; background: #111; color: #fff; border: none; cursor: pointer; text-transform: uppercase; font-weight: bold; }
        .error { color: red; margin-bottom: 15px; }
        footer { margin-top: 60px; font-size: 0.7rem; color: #888; }
    </style>
</head>
<body>

    <header>
        <h1>Carhartt</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="mis_compras.php">Mis Compras</a></li>
            <li><a href="cuenta.php">Cuenta</a></li>
        </ul>
    </nav>

    <main>
        <h2>Crear Cuenta</h2>
        <?php if (!empty($mensaje)): ?>
            <p class="error"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" required>

            <label>Usuario:</label>
            <input type="text" name="usuario" required>

            <label>Contraseña:</label>
            <input type="password" id="pass" name="pass" required>

            <label>Confirmar Contraseña:</label>
            <input type="password" id="confirm_pass" name="confirm_pass" required>

            <button type="submit">Registrarse</button>
        </form>
        <br>
        <p><a href="cuenta.php">Regresar a Iniciar Sesión</a></p>
    </main>

    <footer>
        <p>Daniel Vazquez Montañez | 5ºI | 24308051280673</p>
    </footer>

</body>
</html>