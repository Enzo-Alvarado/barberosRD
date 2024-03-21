<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


// Base de datos
require "config/database.php";
$db = conectarDB();

$query = "SELECT * FROM productos";
$result = mysqli_query($db, $query);

// $query = "INSERT INTO carrito (nombre_producto, precio_producto, id_producto, cantidad, imagen, email_usuario) VALUES (?, ?, ?, ?, ?, ?)";
// $stmt = $db->prepare($query);
// $stmt->bind_param('sdiiss', $_POST['nombre_producto'], $_POST['precio_producto'], $_POST['id'], $_POST['cantidad'], $_POST['imagen'], $_POST['email_usuario']);
// $stmt->execute();

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="productos.css" />
    <!-- ICONOS LINK -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- HEADER -->

    <header>
      <button class="abrir-menu" id="abrir">
        <i class="bx bx-menu"></i>
      </button>
      <h1>RD NERVION</h1>
      <button class="linksHeader-carrito" href="">
        <i class="bx bx-cart carrito"></i>
      </button>

      <nav class="nav" id="nav">
        <button class="cerrar-menu" id="cerrar">
          <i class="bx bx-x carrito"></i>
        </button>
        <li class="nav-list">
          <a class="linksHeader" href="/">Inicio</a>
          <div>
            <a href=""><i class="bx bxl-whatsapp"></i></a>
            <a href=""><i class="bx bxl-instagram"></i></a>
            <a href="carrito.php"><i class="bx bx-cart carrito"></i></a>
          </div>
        </li>
      </nav>
    </header>

    <h1>PRODUCTOS</h1>
    <section class="productosNewGrid">
    <?php while ($producto = mysqli_fetch_assoc($result)) : ?>
      <a href="producto.php?id=<?php echo $producto['id'] ?>">
      <div class="card">
          <div class="card-img">
          <img src="images/<?php echo $producto['imagen']; ?>.png" alt="" />
        </div>
        <div class="card-title"><?php echo $producto['producto']; ?></div>
        <hr class="card-divider" />
        <div class="card-footer">
          <div class="card-price"><span>€</span> <?php echo $producto['precio']; ?></div>
        </div>
      </div>
      </a>
      <?php endwhile; ?>
    </section>
    <!-- SECCION FOOTER-->

    <footer class="contenedorFooter">
      <div>
        <h3>VISÍTANOS</h3>
        <p>C. Juan de Zoyas 14, 41018 Sevilla</p>
      </div>

      <div>
        <h3>CONTÁCTANOS</h3>
        <p>+34 642312827</p>
      </div>

      <div>
        <h3>HORARIOS</h3>
        <p>Lunes a Viernes: 10:00am - 15:00pm / 17:00pm a 21:00pm</p>
        <p>Sabados: 10:00am a 16:00pm</p>
      </div>
    </footer>

    <!-- SECCION FONDO FINAL-->
    <div class="fondoFinal"></div>

    <script src="script.js"></script>
  </body>
</html>