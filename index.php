<?php
// Base de datos
require "config/database.php";
$db = conectarDB();

$query = "SELECT * FROM productos LIMIT 4";
$result = mysqli_query($db, $query);

$query = "SELECT * FROM servicios";
$res = mysqli_query($db, $query);


$query = "SELECT servicio FROM servicios";
$resultado = mysqli_query($db, $query);

$errores = [];

if(!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['telefono']) && !empty($_POST['servicio']) && !empty($_POST['fecha']) && !empty($_POST['hora'])) {
    //Verivicar los dias que trabaja
    $dia = $_POST['fecha'];
    $fecha = date("N", strtotime($dia));

    $query = "SELECT * FROM reservacion WHERE fecha = ? AND hora = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $_POST['fecha'], $_POST['hora']);
    $stmt->execute();
    $result = $stmt->get_result();
    $reser = $result->fetch_assoc();

    if($reser) {
        $errores[] = 'Fecha u horario ya reservado, seleccione otro';
    }

    if($fecha == 7) {
        $errores[] = 'Seleccione un dia laboral (Lunes a Sabados)';
    }

    if(empty($errores)) {
        $query = "INSERT INTO reservacion (nombre, apellido, telefono, servicio, fecha, hora) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);

        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $servicio = $_POST['servicio'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];

        $stmt->bind_param('ssisss', $nombre, $apellido, $telefono, $servicio, $fecha, $hora);
        $stmt->execute();


    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- ICONOS LINK -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <!-- JS LINK -->
    <script src="script.js"></script>

    <!-- CSS LINK -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- TITULO DE LA PAGINA -->
    <title>RD NERVION</title>
  </head>
  <body>
    <!-- HEADER -->

    <main>
      <header>
        <button class="abrir-menu" id="abrir">
          <i class="bx bx-menu"></i>
        </button>
        <h1>RD NERVION</h1>
        <button class="linksHeader-carrito" href="">
          <i class="bx bx-cart carrito1"></i>
        </button>

        <nav class="nav" id="nav">
          <button class="cerrar-menu" id="cerrar">
            <i class="bx bx-x btn-cerrar"></i>
          </button>
          <li class="nav-list">
            <a class="linksHeader" href="#contenedorNosotros">Nosotros</a>
            <a class="linksHeader" href="#servicios">Servicios</a>
            <a class="linksHeader" href="#productos">Productos</a>
            <a class="linksHeader" href="#reservacion">Reservas</a>
            <div class="redesSociales">
              <a href="https://api.whatsapp.com/send?phone=34642312827"><i class="bx bxl-whatsapp"></i></a>
              <a href="https://www.instagram.com/barberos_rd_nervion?igsh=MWZucXBwNGtjdm8xMw%3D%3D&img_index=1"><i class="bx bxl-instagram"></i></a>
              <a href="carrito.php"><i class="bx bx-cart carrito"></i></a>
            </div>
          </li>
        </nav>
      </header>
      <!-- SECCION INICIO -->

      <section class="contenedorInicio">
        <div class="contenedorInicio__fondo">
          <img
            class="contenedorInicio__fondo--img"
            src="images/barbershop-logo-template-design-27ff9e72b794956f590e64e36f06fd2a_screen-removebg-preview.png"
            alt="imagen del logo"
          />
          <h1 class="contenedorInicio__fondo--h1">BARBERIA</h1>
          <h2 class="contenedorInicio__fondo--h2">- BARBEROS RD NERVION -</h2>
          <button class="contenedorInicio__fondo--btn"><a href="#reservacion">RESERVAR</a></button>
        </div>
      </section>
    </main>
    <!-- SECCION NOSOTROS -->

    <section class="contendorNosotros" id="contenedorNosotros">
      <div class="contendorNosotros__imagen">
        <img
          class="contendorNosotros__descripcion--img"
          src="images/cepillo-removebg-preview.png"
        />
      </div>

      <div class="contendorNosotros__descripcion">
        <h3 class="contendorNosotros__descripcion--h3">NOSOTROS</h3>
        <h2 class="contendorNosotros__descripcion--h2">BARBERIA Y TIENDA</h2>
        <p class="contendorNosotros__descripcion--p">
          En Barberia Rachid sabemos que tu cuidado personal se relaciona con la
          imagen que te representa, por esa razon te ofrecemos los mejores
          profesionales, brindando una atencion personalizada en cada servicio.
          Nos destacamos por ofrecer una atencion a niños y adultos, incluyendo
          una cortesia bienvenida y una experiencia grata y relajante. Te
          esperamos!
        </p>
        <button class="contendorNosotros__descripcion--boton"><a href="#reservacion">RESERVAR</a></button>
      </div>

      <div class="contendorNosotros__card--horarios">
        <div class="1">
          <i class="bx bx-cut contenedorNosotros__card--icono"></i>
          <h2 class="contendorNosotros__card--h2">HORARIOS</h2>
          <p>Lunes a Viernes: 10:00am - 15:00pm / 17:00pm a 21:00pm</p>
          <p class="contenedorNosotros__card--p">Sabados: 10:00am a 16:00pm</p>
        </div>
        <div class="2">
          <h2 class="contendorNosotros__card--h2">DIRECCION</h2>
          <p>C. Juan de Zoyas 14, 41018 Sevilla</p>
        </div>
      </div>
    </section>

    <!-- SECCION SERVICIOS -->
    <section>
      <div class="contenedorServicos">
        <div class="contenedorServicios__titulos">
        <h4 class="contendorProductos__titulos--h4">SERVICIOS QUE OFRECEMOS</h4>
        <h2 class="contendorProductos__titulos--h2" id="servicios">Servicios</h2>
        </div>
        <div class="catalog">
        <?php while ($ser = mysqli_fetch_assoc($res)) : ?>
          <div class="item">
            <p class="text-title"><?php echo $ser['servicio']; ?></p><p class="text-titlePrecio">€ <?php echo $ser['precio']; ?></p>
          </div>
        <?php endwhile; ?>
        </div>
      </div>
    </section>

    <!-- SECCION PRODUCTOS -->
    <section class="productos">
      <div class="contenedorProductos__titulos">
        <h4 class="contendorProductos__titulos--h4">TE TENEMOS CUBIERTO</h4>
        <h2 class="contendorProductos__titulos--h2" id="productos">Productos</h2>
      </div>
      <div class="productosGrid">
        <?php while($producto = mysqli_fetch_assoc($result)) : ?>
            <div class="card">
          <div class="card-img">
            <img src="images/<?php echo $producto['imagen']; ?>.png" alt="" />
          </div>
          <div class="card-info">
            <p class="text-title"><?php echo $producto['producto']; ?></p>
          </div>
          <div class="card-footer">
            <span class="text-titlePrecio">€ <?php echo $producto['precio']; ?></span>
            <div class="card-button"></div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div class="containerBoton">
        <button class="botonProductos"><a href="productos.php">CONOCE NUESTROS PRODUCTOS</a></button>
      </div>
    </section>
    <!-- SECCION RESERVAS -->

    <section class="contendorReservas">
      <div class="contendorReservas__grid">
        <img
          class="contendorReservas__grid--img1"
          src="images/imagenReservas1.jpg.png"
          alt=""
        />
        <div class="contendorReservas__grid--texto">
          <i class="bx bx-cut contendorReservas__grid--icono"></i>
          <h2 class="contendorReservas__grid--h2">AGENDA CON NOSTOROS</h2>
          <p>
            Asegura tu cita con tiempo y vive la <br />
            experiencia de nuestra barberia clasica
          </p>
          <button class="contendorReservas__grid--btn"><a href="#reservacion">RESERVAR</a></button>
        </div>
        <img class="eliminar390" src="images/imagenReservas2.jpg" alt="" />
        <img class="eliminar390" src="images/imagenReservas3.jpeg" alt="" />
        <img
          class="eliminar768"
          src="images/imagenReservas4.jpeg"
          alt=""
        />
        <img class="eliminar768" src="images/imagenReservas4.webp" alt="" />
      </div>
    </section>

    <!-- SECCION FONDO IMAGEN -->

    <section class="contenedorFondoImg">
      <img
        class="contenedorFondoImg__img"
        src="images/fondoImagen.png"
        alt=""
      />
    </section>

    <!-- SECCION CONTACTO -->

    <section class="contact" id="reservacion">
    <div class="centrar contactoTexto">
        <i class="bx bx-cut contact__icono"></i>
        <h3 class="contact__h3">RESERVACIÓN</h3>
        <h2 class="contact__h2">ENVIANOS TU RESERVA</h2>
        <button><a href="https://api.whatsapp.com/send?phone=34642312827">WHATSAPP</a></button>
      </div>
            <div>
                <form action="index.php" method="post">    
                  <div class="input-box">
                    <input type="text" name="nombre" placeholder="Escribe tú nombre">
                    <input type="text" name="apellido" placeholder="Escribe tu apellido">
                  </div>
                  <div class="input-box">
                    <input type="number" name="telefono" placeholder="Escribe tu número de telefono">
                    <select name="servicio" id="servicio">
                        <option selected value="">-- Seleccione Servicio --</option>
                        <?php while ($servicio = mysqli_fetch_assoc($resultado)) : ?>
                        <option value="<?php echo $servicio['servicio']; ?>"><?php echo $servicio['servicio']; ?></option>
                        <?php endwhile ?>
                    </select>
                  </div>
                  <div class="input-box">
                    <input type="date" name="fecha">
                    <input type="time" name="hora">
                  </div>
                  <div>
            <?php foreach ($errores as $error) : ?>
                <div class="alerta error">
                    <?php echo $error; ?>
                </div>
            <?php endforeach; ?>
            </div>
                    <input class="input-btn btn" type="submit" value="Reservar cita">
                </form>
            </div>
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