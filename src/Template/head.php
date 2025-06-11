<?php
// Definir BASE_URL solo si no está definida
if (!defined('BASE_URL')) {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    //$carpeta = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');// fija la ruta hasta la carpeta en donde esta el archivo que estoy usando o abriendo
    //$carpeta = '/Mis_Proyectos/IFTS12-LaCanchitaDeLosPibes';// XAMPP
     $carpeta = ''; // SIN subcarpeta// POR PHP - s LOCALHOST:8000
    define('BASE_URL', $protocolo . $host . $carpeta);
}
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!--<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 <!-- <link rel="stylesheet" href="<?php //echo '/src/Css/styles.css'; ?>">--> <!-- PARA USAR EN CASA-->
  <!--<link rel="stylesheet" href="<?php //echo '/Mis%20proyectos/IFTS12-LaCanchitaDeLosPibes/src/Css/styles.css'; ?>">--> <!--PARA USAR EN EL TRABAJO-->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/Css/styles.css">

  
  <title>La canchita de los pibes</title>

</head>


