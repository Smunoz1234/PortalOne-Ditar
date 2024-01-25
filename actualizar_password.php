<?php 
require_once("includes/conexion.php");

// Consulta para obtener los datos actuales de la tabla
$sql = "SELECT [Usuario], [Password] FROM [dbo].[tbl_Usuarios_test]";
$result = sqlsrv_query($conexion, $sql);

// Verificar si la consulta tuvo éxito
if ($result) {
    // Recorrer los resultados y actualizar la columna 'Password'
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $usuario = $row['Usuario'];
        $password = md5($usuario); // Calcular el MD5 del nombre de usuario

        // Actualizar la columna 'Password' en la tabla
        $updateSql = "UPDATE [dbo].[tbl_Usuarios_test] SET [Password] = '$password' WHERE [Usuario] = '$usuario'";
        sqlsrv_query($conexion, $updateSql);
    }

    // Liberar los recursos
    sqlsrv_free_stmt($result);

    // Mensaje de confirmación
    echo "Consulta éxitosa.";
} else {
    echo "Error en la consulta: " . print_r(sqlsrv_errors(), true);
}

// Cerrar la conexión
sqlsrv_close($conexion);