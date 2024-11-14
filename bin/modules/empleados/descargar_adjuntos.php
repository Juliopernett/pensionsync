<?php
// Incluir los archivos de configuración de la base de datos
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");

// Verificar si el ID del documento es válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_documento = $_GET['id']; // ID del documento a descargar
    
    // Consulta para obtener los detalles del archivo (nombre y ruta)
    $query = mysqli_query($con, "SELECT * FROM gt_documentos WHERE id_documento = '$id_documento'");
    
    if ($query) {
        $row = mysqli_fetch_assoc($query);
        
        if ($row) {
            $nombre_archivo = $row['nombre_archivo'];   // Nombre del archivo
            $ruta_archivo = $row['ruta'];       // Ruta del archivo en el servidor
            $tamano = $row['tamanio'];                   // Tamaño del archivo en bytes
            
            // Verificar que $ruta_archivo no tenga el prefijo 'adjuntos'
            if (strpos($ruta_archivo, 'adjuntos\\') === 0) {
                // Eliminar el prefijo 'adjuntos\' si lo tiene
                $ruta_archivo = substr($ruta_archivo, strlen('adjuntos\\'));
            }

            // Ahora construimos la ruta completa correctamente
            $ruta_completa = __DIR__ . '\\adjuntos\\' . $nombre_archivo ;  // Ruta completa del archivo en el servidor
            
            // Verificar el tamaño real del archivo en el servidor
            $tamano_real = filesize($ruta_completa); // Tamaño real en el servidor

            if ($tamano_real != $tamano) {
                echo "El tamaño del archivo en el servidor no coincide con el tamaño almacenado en la base de datos. ";
                echo "Tamaño real: " . $tamano_real . " bytes. Tamaño en la base de datos: " . $tamano . " bytes.";
                exit;
            }

            // Verificar si el archivo existe en la ruta especificada
            if (file_exists($ruta_completa)) {
               // Establecer las cabeceras para la descarga del archivo
               header('Content-Description: File Transfer');
               header('Content-Type: application/octet-stream');
               header('Content-Disposition: attachment; filename="' . basename($nombre_archivo) . '"');
               header('Content-Length: ' . $tamano);
               header('Cache-Control: no-cache, no-store, must-revalidate');
               header('Pragma: no-cache');
               header('Expires: 0');
               
               // Evitar cualquier tipo de salida antes de la descarga
               ob_clean(); // Limpiar cualquier salida previa
               flush();    // Vaciar el buffer de salida

               // Leer y enviar el archivo al navegador en bloques de 8K
               readfile($ruta_completa);
               exit;
            } else {
                echo $ruta_completa;

                echo "El archivo no existe en el servidor.";
            }
        } else {
            echo "No se encontró el documento en la base de datos.";
        }
    } else {
        echo "Error en la consulta a la base de datos.";
    }
} else {
    echo "ID de documento no válido.";
}
?>
