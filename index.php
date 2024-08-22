<?php

$jsonAlcance = 'testAlcance.json';
$dataAlcance = json_decode(file_get_contents($jsonAlcance), true) ?? [];

// Obtener el ID máximo actual y asignar el siguiente ID
$maxId = 0;
foreach ($dataAlcance as $item) {
    if ($item['id'] > $maxId) {
        $maxId = $item['id'];
    }
}
$nextId = $maxId + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    // Definir las variables con los valores del formulario
    $nombre = trim($_POST['nombre']);
    $edad = (int)$_POST['edad'];
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $pais = trim($_POST['pais']);
    $ocupacion = trim($_POST['ocupacion']);
    $estado_civil = trim($_POST['estado_civil']);

    // Inicializar el array de errores
    $errors = [];

    // Validar correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Correo electrónico no válido.';
    }

    // Validar teléfono (debe ser un número de 10 dígitos)
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $errors[] = 'Número de teléfono no válido. Debe tener 10 dígitos.';
    }

    if (empty($errors)) {
        $nuevoReg = [
            'id' => $nextId,
            'nombre' => $nombre,
            'edad' => $edad,
            'correo' => $correo,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'pais' => $pais,
            'ocupacion' => $ocupacion,
            'estado_civil' => $estado_civil,
            'eliminado' => false
        ];

        $dataAlcance[] = $nuevoReg;
        file_put_contents($jsonAlcance, json_encode($dataAlcance, JSON_PRETTY_PRINT));
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['delete'])) {
    $ID_Delete = $_GET['delete'];
    foreach ($dataAlcance as &$item) {
        if ($item['id'] == $ID_Delete) {
            $item['eliminado'] = true;
            break;
        }
    }
    file_put_contents($jsonAlcance, json_encode($dataAlcance, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete_fisico'])) {
    $ID_Delete = $_GET['delete_fisico'];
    $dataAlcance = array_filter($dataAlcance, function($item) use ($ID_Delete) {
        return $item['id'] != $ID_Delete;
    });
    file_put_contents($jsonAlcance, json_encode(array_values($dataAlcance), JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

if (isset($_GET['restore'])) {
    $ID_Restore = $_GET['restore'];
    foreach ($dataAlcance as &$item) {
        if ($item['id'] == $ID_Restore) {
            $item['eliminado'] = false;
            break;
        }
    }
    file_put_contents($jsonAlcance, json_encode($dataAlcance, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit;
}

$User_read = array_filter($dataAlcance, function($item) {
    return !$item['eliminado'];
});

$User_eliminado = array_filter($dataAlcance, function($item) {
    return $item['eliminado'];
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C - R - U - D</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">C - R - U - D</h1>
        <div class="position-relative">
            <form method="post" class="text-center">
                <h2 class="text-center">Agregar un nuevo registro</h2>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput nombre" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="floatingInput edad" name="edad" placeholder="Edad" value="<?php echo htmlspecialchars($_POST['edad'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput correo" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="tel" class="form-control" id="floatingInput telefono" name="telefono" placeholder="Teléfono" pattern="[0-9]{10}" maxlength="10" title="El número de teléfono debe tener 10 dígitos" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput direccion" name="direccion" placeholder="Dirección" value="<?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput ciudad" name="ciudad" placeholder="Ciudad" value="<?php echo htmlspecialchars($_POST['ciudad'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput pais" name="pais" placeholder="País" value="<?php echo htmlspecialchars($_POST['pais'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput ocupacion" name="ocupacion" placeholder="Ocupación" value="<?php echo htmlspecialchars($_POST['ocupacion'] ?? ''); ?>" required>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput estado_civil" name="estado_civil" placeholder="Estado Civil" value="<?php echo htmlspecialchars($_POST['estado_civil'] ?? ''); ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Agregar</button>
            </form>
        </div>

        <div class="container mt-5">
            <h2 class="text-center">Registros</h2>
            <br>
            <ul class="record-list">
                <?php foreach($User_read as $user): ?>
                    <li>
                        <?php echo htmlspecialchars($user['nombre']); ?> - <?php echo htmlspecialchars($user['edad']); ?> años
                        <div class="button-contaier">
                            <a class="btn btn-warning" href="edit.php?id=<?php echo urlencode($user['id']); ?>">Editar</a>
                            <a class="btn btn-secondary" href="index.php?delete=<?php echo urlencode($user['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?')">Eliminar</a>
                            <a class="btn btn-danger" href="index.php?delete_fisico=<?php echo urlencode($user['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro permanentemente?')">Eliminar Permanentemente</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="container mt-5">
            <h2 class="text-center">Registros Eliminados</h2>
            <br>
            <ul class="record-list">
                <?php foreach($User_eliminado as $user_e): ?>
                    <li>
                        <?php echo htmlspecialchars($user_e['nombre']); ?> - <?php echo htmlspecialchars($user_e['edad']); ?> años
                        <div class="button-contaier">
                            <a class="btn btn-primary" href="index.php?restore=<?php echo urlencode($user_e['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas restaurar este usuario?')">Restaurar</a>
                            <a class="btn btn-danger" href="index.php?delete_fisico=<?php echo urlencode($user_e['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro permanentemente?')">Eliminar Permanentemente</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>