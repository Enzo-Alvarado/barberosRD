<?php

session_start();
$auth = $_SESSION["login"] = true;

if (!$auth) {
    header("Location: /login.php");
    exit;
}

$email_usuario = $_SESSION['email'];

// Base de datos
require "config/database.php";
$db = conectarDB();



$query = "SELECT SUM(precio_producto) AS pagar FROM detalle_carrito WHERE email_usuario = '${email_usuario}'";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_execute($stmt);
$total = mysqli_stmt_get_result($stmt);
// while($row = mysqli_fetch_assoc($total)) :
$row = mysqli_fetch_assoc($total);
$suma_pagar = $row['pagar'];
// $nombre_producto = $row['nombre_producto'];
// $cantidad = $row['cantidad'];
// endwhile;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['email']) && !empty($_POST['telefono']) && !empty($_POST['dni']) && !empty($_POST['provincia']) && !empty($_POST['ciudad']) && !empty($_POST['calle']) && !empty($_POST['altura']) && !empty($_POST['email_usuario'])) {
        $query = "INSERT INTO envio (nombre, apellido, email, telefono, dni, provincia, ciudad, calle, altura, email_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);

        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $dni = $_POST['dni'];
        $provincia = $_POST['provincia'];
        $ciudad = $_POST['ciudad'];
        $calle = $_POST['calle'];
        $altura = $_POST['altura'];

        // Corrected typo in the field name: $_POST['teelefono'] => $_POST['telefono']
        $stmt->bind_param('sssiisssis', $nombre, $apellido, $email, $telefono, $dni, $provincia, $ciudad, $calle, $altura, $email_usuario);
        $stmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio</title>
    <link rel="stylesheet" href="envioYpago.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AWVMGX3f_YkhPjgI8Kd5ltBlAkYqFkwcKXnQL3J_taK4aUrkGXEBA_N5vxVml2AOarUZTcWwyr_IfjoN&currency=EUR"></script>
</head>
<body>
    <main>
        <section>
        <form class="form" method="post" action="envio.php">
          <p class="title">Envio y pago</p>

          <div class="flex">
            <label>
              <input
                required=""
                placeholder=""
                type="text"
                class="input"
                name="nombre"
              />
              <span>Nombre</span>
            </label>

            <label>
              <input
                required=""
                placeholder=""
                type="text"
                class="input"
                name="apellido"
              />
              <span>Apellido</span>
            </label>
          </div>

          <label>
            <input
              required=""
              placeholder=""
              type="email"
              class="input"
              name="email"
            />
            <span>Mail</span>
          </label>

          <label>
            <input
              required=""
              placeholder=""
              type="tel"
              class="input"
              name="telefono"
            />
            <span>Telefono</span>
          </label>
          <label>
            <input
              required=""
              placeholder=""
              type="text"
              class="input"
              name="dni"
            />
            <span>DNI</span>
          </label>
          <label>
            <input
              required=""
              placeholder=""
              type="text"
              class="input"
              name="provincia"
            />
            <span>Provincia</span>
          </label>
          <label>
            <input
              required=""
              placeholder=""
              type="text"
              class="input"
              name="ciudad"
            />
            <span>Ciudad</span>
          </label>
          <label>
            <input
              required=""
              placeholder=""
              type="text"
              class="input"
              name="direccion"
            />
            <span>Direccion</span>
          </label>
          <label>
            <input
              required=""
              class="input"
              type="text"
              name="altura"
              id=""
              placeholder="altura"
            />
          </label>
          <input
            type="hidden"
            name="email_usuario"
            value="<?php echo $email_usuario; ?>"
          />
          <input type="submit" value="Enviar" class="btn-form" />
        </form>
        </section>

        <section>
            <div id="paypal-button-container" class="metodo-pago">
            <script>
                paypal.Buttons({
                    style:{
                        color: 'blue',
                        label: 'pay'
                    },
                    createOrder: function(data, actions){
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: <?php echo isset($suma_pagar) ? $suma_pagar : 0; ?>
                                }
                            }]
                        });
                    },

                    onApprove: function(data, actions){
                        actions.order.capture().then(function(detalles){
                            <?php
                                $query = "INSERT INTO pago (compra, email_usuario) VALUES ('PAGADO', $email_usuario)";
                            ?>
                        });
                    },

                    onCancel: function(data){
                        alert("Pago no realizado")
                    }
                }).render('#paypal-button-container')
            </script>
            </div>
        </section>
    </main>
</body>
</html>