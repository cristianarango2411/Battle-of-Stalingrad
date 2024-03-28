<?php declare( strict_types = 1 );

require ('vendor/autoload.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla CRUD</title>
</head>
<body>
    <h1>Crear, Modificar y Eliminar Tablas</h1>

    <h2>Crear Tabla</h2>
    <form action="createTable.php" method="post">
        <label for="table_name">Nombre de la tabla:</label>
        <input type="text" id="table_name" name="table_name" required>
        <button type="submit">Crear Tabla</button>
    </form>

    <h2>Eliminar Tabla</h2>
    <form action="deleteTable.php" method="post">
        <label for="table_name_delete">Nombre de la tabla a eliminar:</label>
        <input type="text" id="table_name_delete" name="table_name_delete" required>
        <button type="submit">Eliminar Tabla</button>
    </form>
    
    <h2>obtener Tabla</h2>
    <form action="getTable.php" method="post">
        <label for="table_name_delete">tabla a consultar:</label>
        <input type="text" id="table_name_get" name="table_name_get" required>
        <button type="submit">Eliminar Tabla</button>
    </form>

</body>
</html>
