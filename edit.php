<?php
$jsonAlcance = 'testAlcance.json';
$dataAlcance = json_decode(file_get_contents($jsonAlcance), true) ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $id = (int)$_POST['id'];
    foreach ($dataAlcance as &$item) {
        if ($item['id'] == $id) {
            $item['nombre'] = $_POST['nombre'];
            $item['edad'] = $_POST['edad'];
            $item['correo'] = $_POST['correo'];
            $item['telefono'] = $_POST['telefono'];
            $item['direccion'] = $_POST['direccion'];
            $item['ciudad'] = $_POST['ciudad'];
            $item['pais'] = $_POST['pais'];
            $item['ocupacion'] = $_POST['ocupacion'];
            $item['estado_civil'] = $_POST['estado_civil'];
            break;
        }
    }
    file_put_contents($jsonAlcance, json_encode($dataAlcance, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

$user = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    foreach ($dataAlcance as $item) {
        if ($item['id'] == $id) {
            $user = $item;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Registro</h1>
        <div class="position-relative">
            <form method="post" class="text-center">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" placeholder="Nombre" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput edad" name="edad" value="<?php echo htmlspecialchars($user['edad']); ?>" placeholder="Edad" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" placeholder="Correo" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>" placeholder="Teléfono" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput direccion" name="direccion" value="<?php echo htmlspecialchars($user['direccion']); ?>" placeholder="Dirección" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput ciudad" name="ciudad" value="<?php echo htmlspecialchars($user['ciudad']); ?>" placeholder="Ciudad" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput pais" name="pais" value="<?php echo htmlspecialchars($user['pais']); ?>" placeholder="País" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput ocupacion" name="ocupacion" value="<?php echo htmlspecialchars($user['ocupacion']); ?>" placeholder="Ocupación" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput estado_civil" name="estado_civil" value="<?php echo htmlspecialchars($user['estado_civil']); ?>" placeholder="Estado Civil" required>
                </div>
                <div class="button-contaier">
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="index.php" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas regresar al inicio?')">Regresar</a>
                </div>
                <br>
            </form>
        </div>
    </div>
</body>
</html>
