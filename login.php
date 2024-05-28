<?php
session_start(); // Iniciar la sesión

// Configuración del servidor LDAP
$ldap_host = 'ldap://192.168.7.1'; // Cambiar por la dirección del servidor LDAP
$ldap_port = 389; // Cambiar por el puerto del servidor LDAP
$ldap_base_dn = 'dc=adrianldaprb,dc=daw'; // Cambiar por el DN base de LDAP
$ldap_user_suffix = '@adrianldaprb.daw'; // Cambiar por el sufijo de usuario de LDAP
$ldap_group_dn = 'cn=users,dc=adrianldaprb,dc=daw'; // Cambiar por el DN del grupo LDAP si es necesario
$endlinestr = '<br>'; // Línea final HTML

// Mostrar la configuración del servidor LDAP
echo $ldap_host . $endlinestr;
echo $ldap_port . $endlinestr;
echo $ldap_base_dn . $endlinestr;

// DN para el enlace de administrador LDAP y contraseña
$ldap_bind_dn = 'cn=admin,' . $ldap_base_dn; // DN para el administrador LDAP
$ldap_bind_password = 'ldaprb'; // Contraseña para el enlace DN

// ID de usuario
$userId = 'user_id'; // ID de usuario

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener entradas del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    $md5Hash = md5($password); // Hash MD5 para la contraseña

    // Conexión LDAP
    $ldap_conn = ldap_connect($ldap_host, $ldap_port);

    // Comprobar si la conexión LDAP se estableció correctamente
    if ($ldap_conn) {
        // Establecer opciones LDAP
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ldap_conn, LDAP_OPT_DEBUG_LEVEL, 7);

        // Enlazar utilizando el DN y la contraseña del administrador
        $ldap_bind = ldap_bind($ldap_conn, $ldap_bind_dn, $ldap_bind_password);

        // Comprobar si el enlace LDAP fue exitoso
        if ($ldap_bind) {
            // Buscar al usuario por su ID
            $userId = $username;
            $filter = "(uid=$userId)";
            $result = ldap_search($ldap_conn, $ldap_base_dn, $filter);
            $entries = ldap_get_entries($ldap_conn, $result);

            // Verificar si se encontraron resultados
            if ($entries['count'] > 0) {
                // Utilizar el DN de la primera entrada encontrada
                $user_dn = $entries[0]['dn'];

                // Enlazar utilizando el DN y la contraseña del usuario
                $ldap_user_bind = ldap_bind($ldap_conn, $user_dn, $password);

                // Comprobar si el enlace LDAP del usuario fue exitoso
                if ($ldap_user_bind) {
                    // Guardar el nombre de usuario en una variable de sesión
                    $_SESSION['username'] = $username;

                    // Operaciones LDAP
                    if (preg_match("/admin/i", $user_dn) == 1) {
                        // Si el usuario es un administrador, redirigir a admin_page.php
                        header('Location: admin_page.php');
                        exit();
                    } else if (preg_match("/users/i", $user_dn) == 1) {
                        // Si el usuario es un usuario normal, redirigir a usuario_page.php
                        header('Location: user_page.php');
                        exit();
                    }
                } else {
                    echo "El enlace LDAP falló para el usuario $userId: " . ldap_error($ldap_conn) . $endlinestr;
                }
            } else {
                echo "Usuario $userId no encontrado" . $endlinestr;
            }
        } else {
            // Fallo de conexión LDAP
            echo "Error al enlazar como administrador al servidor LDAP." . $endlinestr;
            echo "Error de enlace LDAP: " . ldap_error($ldap_conn) . $endlinestr;
        }
    } else {
        // Fallo de conexión LDAP
        echo "Error al conectar al servidor LDAP." . $endlinestr;
        echo "Error de enlace LDAP: " . ldap_error($ldap_conn) . $endlinestr;
    }
    
    // Cerrar la conexión LDAP
    ldap_close($ldap_conn);
}
?>
