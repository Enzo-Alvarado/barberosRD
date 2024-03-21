<?php

// Base de datos
require "config/database.php";
$db = conectarDB();

$query = "SELECT * FROM servicios";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita</title>
</head>
<body>
    <main>
        <section>
        <div>
            <?php foreach ($errores as $error) : ?>
                <div class="alerta error">
                    <?php echo $error; ?>
                </div>
            <?php endforeach; ?>
            </div>
            <div>
                <form action="reservacion.php" method="post">
                    <label for="nombre">Nombre</label>    
                    <input type="text" name="nombre" placeholder="Escribe tú nombre">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" placeholder="Escribe tu apellido">
                    <label for="telefono">Telefono</label>
                    <input type="number" name="telefono" placeholder="Escribe tu número de telefono">
                    <select name="servicio" id="servicio">
                        <option selected value="">-- Seleccione --</option>
                        <?php while ($servicio = mysqli_fetch_assoc($resultado)) : ?>
                        <option value="<?php echo $servicio['servicio']; ?>"><?php echo $servicio['servicio']; ?></option>
                        <?php endwhile ?>
                    </select>
                    <label for="fecha">Fecha del Turno</label>
                    <input type="date" name="fecha">
                    <label for="hora">Horario del turno</label>
                    <input type="time" name="hora">
                    <input type="submit" value="Reservar cita">
                </form>
            </div>
        </section>
    </main>
</body>
</html>