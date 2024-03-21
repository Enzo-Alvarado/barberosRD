<?php

session_start();
$auth = $_SESSION["login"] = true;

if (!$auth) {
    header("Location: /login.php");
    exit();
}

$email_usuario = $_SESSION['email'];

// Base de datos
require "config/database.php";
$db = conectarDB();

$query = "SELECT * FROM carrito WHERE email_usuario = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $email_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

//Mostrar cantidad de productos y precio total
$query = "SELECT SUM(precio_producto) as precio_total FROM carrito WHERE email_usuario = ?";
$stmt_total = $db->prepare($query);
$stmt_total->bind_param('s', $email_usuario);
$stmt_total->execute();
$total = $stmt_total->get_result();

// Eliminar producto del carrito
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {
        $query_delete = "DELETE FROM carrito WHERE id = ?";
        $stmt_delete = $db->prepare($query_delete);
        $stmt_delete->bind_param('i', $id);
        $stmt_delete->execute();

        header("Location: /carrito.php");
        exit();
    }
}

// Insertar todos los productos del carrito en la tabla detalle_carrito
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['finalizar_compra'])) {
    $query_insert = "INSERT INTO detalle_carrito (nombre_producto, precio_producto, cantidad, email_usuario) VALUES (?, ?, ?, ?)";
    $stmt_insert = $db->prepare($query_insert);

    while ($carrito = $resultado->fetch_assoc()) {
        $stmt_insert->bind_param('sdis', $carrito['nombre_producto'], $carrito['precio_producto'], $carrito['cantidad'], $email_usuario);
        $stmt_insert->execute();
    }

    // Limpiar el carrito después de finalizar la compra
    $query_clear_cart = "DELETE FROM carrito WHERE email_usuario = ?";
    $stmt_clear_cart = $db->prepare($query_clear_cart);
    $stmt_clear_cart->bind_param('s', $email_usuario);
    $stmt_clear_cart->execute();

    header("Location: /envio.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="carrito.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
    
    <main>
        <section>
            <div class="carritoDeCompras">
                <h1>CARRITO DE COMPRAS</h1>
                <?php while($carrito = $resultado->fetch_assoc()) : ?>
                    <div class="cardCarrito">
                        <div class="cardCarrito__Img">
                            <img src="images/<?php echo $carrito['imagen']; ?>.png" alt="" />
                        </div>
                        <div class="cardCarrito__datos">
                            <h3><?php echo $carrito['nombre_producto']; ?></h3>
                            <p><?php echo $carrito['cantidad']; ?></p>
                            <p>€ <?php echo $carrito['precio_producto']; ?></p>
                        </div>
                        <form action="carrito.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $carrito["id"]; ?>">
                            <div class="cardCarrito__eliminar">
                                <button type="submit"><i class="bx bx-trash"></i></button>
                            </div>
                        </form>
                    </div>
                <?php endwhile; ?>
                <form method="post">
                    <input type="submit" class="finalizarCompra" name="finalizar_compra" value="Finalizar Compra"/>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
