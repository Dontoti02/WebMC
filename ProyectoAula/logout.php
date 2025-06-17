<?php
session_start();
session_destroy();
http_response_code(200); // Indica que el cierre de sesión fue exitoso
exit;
?>