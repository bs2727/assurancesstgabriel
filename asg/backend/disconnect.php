<?php
session_start();
session_destroy(); // Destruction de la session
echo "<script type='text/javascript'>
window.location.href = '../pages/index.php';
</script>";
?>