<?php           
    //Agregamos resultados de la consulta por campos a la tabla
    while ($fila2 = $query2->fetch()) {
        echo '<tr>';
        echo '<td>' . $fila2['id'] . '</td>';
        echo '<td>' . $fila2['nick'] . '</td>';
        echo '<td>' . $fila2['nombre'] . '</td>';
        echo '<td>' . $fila2['apellidos'] . '</td>';
        echo '<td>' . $fila2['email'] . '</td>';
        echo '<td>' . $fila2['password'] . '</td>';
        if ($fila2['imagen_avatar'] != null) {
            echo '<td><img src="fotos/' . htmlspecialchars($fila2['imagen_avatar']) . '"with="40" alt="imagen_avatar"/></td>';
        } else {
            echo '<td>' . "---" . '</td>';
        }

        //Añadimos a la columna Operaciones
        echo '<td>' . '<a id="editar" href="actuser.php?id=' . $fila2['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z" />
        <path d="M16 5l3 3" />
        <path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6" />

        </svg></a>' . '<a class="m-2" id="editar" href="eliminarUser.php?id=' . $fila2['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M4 7l16 0" />
        <path d="M10 11l0 6" />
        <path d="M14 11l0 6" />
        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        </svg></a>' . '<a class="m-2" id="editar" href="detalleUser.php?id=' . $fila2['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
        <path d="M15 8l2 0" />
        <path d="M15 12l2 0" />
        <path d="M7 16l10 0" />
        </svg></a>' . '</td>';
        echo '</tr>';
    }
?>