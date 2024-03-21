<?php

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Base de datos
require "config/database.php";
$db = conectarDB();

$errores = [];

$id = $_GET['id'];

if (!empty($_GET['id'])) {
    $id_producto = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id_producto === false) {
        $errores[] = 'ID de producto no válido';
    } else {
        $consulta = "SELECT * FROM productos WHERE id = ${id_producto}";
        $resultado = mysqli_query($db, $consulta);
        $producto = mysqli_fetch_assoc($resultado);

        if (!$producto) {
            $errores[] = 'Producto no encontrado';
        }
    }
} else {
    $errores[] = 'ID de producto no proporcionado';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nombre_producto']) && !empty($_POST['precio_producto']) && !empty($_POST['id']) && !empty($_POST['cantidad']) && !empty($_POST['email_usuario']) && !empty($_POST['imagen'])) {
        $query = "INSERT INTO carrito (nombre_producto, precio_producto, id_producto, cantidad, email_usuario, imagen) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        if ($stmt) {
            $precio_total = $_POST['precio_producto'] * $_POST['cantidad'];
            $stmt->bind_param('sdiiss', $_POST['nombre_producto'], $precio_total, $_POST['id'], $_POST['cantidad'], $_POST['email_usuario'], $_POST['imagen']);
            $stmt->execute();

            header("Location: productos.php");
            
            if ($stmt->affected_rows <= 0) {
                $errores[] = 'Error al agregar al carrito';
            }
            
            $stmt->close();
        } else {
            $errores[] = 'Error en la preparación de la consulta';
        }
    } else {
        $errores[] = 'Todos los campos son obligatorios';
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="producto.css">
    <title>Producto</title>
</head>
<body>
    <main>
        <section>
            <div class="product-container">
                <img src="images/<?php echo $producto['imagen']; ?>.png" alt="imagen producto">
                <div class="product-info">
                <p class="product-title"><?php echo $producto['producto'] ?></p>
                <p class="product-price">€ <?php echo $producto['precio'] ?></p>
                <p><?php echo $producto['descripcion'] ?></p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_producto; ?>">
                    <input type="hidden" name="nombre_producto" value="<?php echo $producto['producto']; ?>">
                    <input type="hidden" name="precio_producto" value="<?php echo $producto['precio']; ?>">
                    <input type="hidden" name="id" value="<?php echo $id_producto; ?>">
                    <input type="hidden" name="email_usuario" value="<?php echo $_SESSION['email']; ?>">
                    <input class="input-cantidad" type="number" name="cantidad" min="1" max="<?php echo $producto['stock']; ?>">
                    <input type="hidden" name="imagen" value="<?php echo $producto['imagen']; ?>">
                    <button class="card-btn" type="submit">Agregar
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                        d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z"
                        ></path>
                        <path
                        d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"
                        ></path>
                        <path
                        d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"
                        ></path>
                        <path
                        d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z"
                        ></path>
                    </svg>
                    </button>
                </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>