<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: cuenta.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto'])) {
    $producto = $_POST['producto'];
    $usuario_nombre = $_SESSION['usuario'];

    $stmt_user = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt_user->bind_param("s", $usuario_nombre);
    $stmt_user->execute();
    $res = $stmt_user->get_result();

    if ($row = $res->fetch_assoc()) {
        $usuario_id = $row['id'];

        $stmt_compra = $conn->prepare("INSERT INTO compras (usuario_id, producto) VALUES (?, ?)");
        $stmt_compra->bind_param("is", $usuario_id, $producto);

        if ($stmt_compra->execute()) {
            $mensaje = "¡Compra realizada con éxito!";
        } else {
            $mensaje = "Error al procesar la compra.";
        }
        $stmt_compra->close();
    }
    $stmt_user->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Carhartt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto Mono', monospace; color: #111; padding: 40px 20px; max-width: 700px; margin: 0 auto; text-align: center; }
        header h1 { font-size: 2rem; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 20px; }
        nav ul { display: flex; justify-content: center; gap: 20px; list-style: none; margin-bottom: 40px; }
        nav a { color: #666; text-decoration: none; font-size: 0.85rem; text-transform: uppercase; }
        h2 { font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 20px; }
        .galeria-imagenes { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin-bottom: 30px; }
        .galeria-imagenes img { width: 120px; height: auto; border: 1px solid #eee; }
        form { max-width: 400px; margin: 0 auto; }
        select { font-family: 'Roboto Mono', monospace; width: 100%; padding: 12px; border: 1px solid #111; margin-bottom: 15px; background: #fff; }
        .btn-group { display: flex; gap: 10px; }
        .btn { font-family: 'Roboto Mono', monospace; flex: 1; padding: 12px; text-transform: uppercase; font-weight: 700; border: 1px solid #111; cursor: pointer; background: #fff; }
        .btn.submit { background: #111; color: #fff; }
        .mensaje { font-weight: bold; margin-bottom: 15px; color: green; }
        footer { margin-top: 60px; font-size: 0.7rem; color: #888; letter-spacing: 0.1em; }
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
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <main>
        <h2>Productos</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <div class="galeria-imagenes">
            <img src="car1.png" alt="Detroit Carhartt Jacket">
            <img src="car2.png" alt="T-Shirt Carhartt Negra">
            <img src="car3.jpg" alt="Cosmetiquera Carhartt Negra">
            <img src="car4.png" alt="Converse x Carhartt">
        </div>

        <form action="index.php" method="POST">
            <select name="producto">
                <option value="Detroit Carhartt Jacket">Detroit Carhartt Jacket</option>
                <option value="T-Shirt Carhartt Negra">T-Shirt Carhartt Negra</option>
                <option value="Cosmetiquera Carhartt Negra">Cosmetiquera Carhartt Negra</option>
                <option value="Converse x Carhartt">Converse x Carhartt</option>
            </select>
            
            <div class="btn-group">
                <input type="submit" value="Comprar" class="btn submit">
                <input type="reset" value="Cancelar" class="btn reset">
            </div>
        </form>
    </main>

    <footer>
        <p>Daniel Vazquez Montañez | 5ºI | 24308051280673</p>
    </footer>

</body>
</html>