  <?php require_once('../Connections/basededatos.php'); ?>
  <?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../paginas/Sesion.php";// cambiar aca dependiendo donde nos ubicamos
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
  <?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../paginas/fail.html";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
  <?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$maxRows_Departamento = 10;
$pageNum_Departamento = 0;
if (isset($_GET['pageNum_Departamento'])) {
  $pageNum_Departamento = $_GET['pageNum_Departamento'];
}
$startRow_Departamento = $pageNum_Departamento * $maxRows_Departamento;

mysql_select_db($database_basededatos, $basededatos);
$query_Departamento = "SELECT * FROM Departamento";
$query_limit_Departamento = sprintf("%s LIMIT %d, %d", $query_Departamento, $startRow_Departamento, $maxRows_Departamento);
$Departamento = mysql_query($query_limit_Departamento, $basededatos) or die(mysql_error());
$row_Departamento = mysql_fetch_assoc($Departamento);

if (isset($_GET['totalRows_Departamento'])) {
  $totalRows_Departamento = $_GET['totalRows_Departamento'];
} else {
  $all_Departamento = mysql_query($query_Departamento);
  $totalRows_Departamento = mysql_num_rows($all_Departamento);
}
$totalPages_Departamento = ceil($totalRows_Departamento/$maxRows_Departamento)-1;

mysql_select_db($database_basededatos, $basededatos);
$query_Edificio = "SELECT * FROM edificios";
$Edificio = mysql_query($query_Edificio, $basededatos) or die(mysql_error());
$row_Edificio = mysql_fetch_assoc($Edificio);
$totalRows_Edificio = mysql_num_rows($Edificio);

mysql_select_db($database_basededatos, $basededatos);
$query_Aulas = "SELECT * FROM aulas";
$Aulas = mysql_query($query_Aulas, $basededatos) or die(mysql_error());
$row_Aulas = mysql_fetch_assoc($Aulas);
$totalRows_Aulas = mysql_num_rows($Aulas);

mysql_select_db($database_basededatos, $basededatos);
$query_Recordset1 = "SELECT * FROM turno";
$Recordset1 = mysql_query($query_Recordset1, $basededatos) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accesos a Menues Agregar</title>
<link rel="stylesheet" href="../Css/style.css">
<link rel="stylesheet" href="../Css/Styles.css" type="text/css">
<link href="../Css/bootstrap.min.css" rel="stylesheet">
<link href="../menu.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimum-scale=1.0"/>
<link rel="stylesheet" href="../Css/fonts.css">
<script src="http://code.jquery.com/jquery-lastest.js"></script>
<script src="../header.js"></script>
<script src="../js/main.js"></script>
</head>

<body>
<!--Esta es la cabecera de todas las paginas-->
<div class="container">
<header>

<div class="logo">
<center><img src="../Imagenes/sca-administrator_solo_por_illustracion.jpg" alt="" width="175" height="90"/></center>
</div ><div id="headerr" class="table" >
 <ul class="nav">
    <li><a href="../paginas/Home.php">Inicio </a></li>
    <li><a href="../alumnos/Buscar.php ">Alumnos</a>  				  <ul> 
                  <li><a href="../alumnos/Registro.php">Nuevo Registro</a></li>
                  <li><a href="../alumnos/Inscripcion.php">Inscripciones</a></li>
<li><a href="../alumnos/Mostrar">Ver Registros</a></li></ul></li>
                       
    <li><a href="../profesores/Buscar.php">Profesores</a>
      <ul> 
         <li><a href="../profesores/Registro.php">Nuevo Registro</a></li>
         <li><a href="../profesores/Asignacion.php">Crear Asignaciones</a></li>
          <li><a href="../profesores/Mostrar.php">Ver Registros</a></li>
            </ul></li>
    <li><a href="../Clases/Buscar.php">Mostrar Clases </a> <ul> 
       <li><a href="../Clases/ClaseRegistro.php">Nuevo Registro</a></li>
       <li><a href="../Clases/Claseincomplet.php">Clases Incompletas</a></li>  
          <li><a href="../Clases/Mostrar.php">Ver Clases Registradas</a></li> 
                  </ul></li>
     <li><a href="#">Varios </a> <ul> 
       <li><a href="../Edificios_Utensileos/MenudeAgregar.php">Menu de Acceso</a></li>
       <li><a href="../Edificios_Utensileos/TurnoRegistro.php">Registro de Turnos</a></li> 
         <li><a href="../Edificios_Utensileos/AulaRegistro.php">Registro de Aulas</a></li> 
         <li><a          <li><a href="../Edificios_Utensileos/DepartamentoRegistro.php">Registro de Departamento</a></li> 
<a href="../Edificios_Utensileos/EdificioRegistro.php">Registro de Edificios</a></li> 
        
                  </ul></li>
           <li><a href="../Notas/Menu.ph">Notas de Alumnos </a> <ul> 
         <li><a href="../Notas/AsignacionNotas.php ">Asignacion de Notas</a></li>  
         <li><a href="../Notas/CreaciondeNotas.php ">Creacion de Notas</a></li> 
              </ul></li>
         
          <li><a href="#">Otras opciones </a> <ul> 
         <li><a href="../paginas/Acerca_de.php ">Acerca de</a></li>  
         <li><a href="../paginas/Ayuda.php ">Ayuda</a></li>
              <li> <a href="<?php echo $logoutAction ?>">Cerrar Sesi&oacute;n</a></li>
              </ul></li>
    </ul>
    
    </div> 
 
</header>
<div class="container">
<div class="table-responsive">
<p>Departamento</p>
<p><a href="/Edificios_Utensileos/DepartamentoRegistro.php">Registrar Departamento</a></p>
<table border="1" class="table">
  <tr>
    <td>IDDepartamento</td>
    <td>Nombre</td>
    <td>Descripcion</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Departamento['IDDepartamento']; ?></td>
      <td><?php echo $row_Departamento['Nombre']; ?></td>
      <td><?php echo $row_Departamento['Descripcion']; ?></td>
    </tr>
    <?php } while ($row_Departamento = mysql_fetch_assoc($Departamento)); ?>
</table>
<p>&nbsp;</p>
<p>Edificios</p>
<p><a href="/Edificios_Utensileos/EdificioRegistro.php">Registrar Edificios</a></p>
<table border="1" class="table">
  <tr>
    <td>idEdificios</td>
    <td>Codigo_edificio</td>
    <td>descripcion</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Edificio['idEdificios']; ?></td>
      <td><?php echo $row_Edificio['Codigo_edificio']; ?></td>
      <td><?php echo $row_Edificio['descripcion']; ?></td>
    </tr>
    <?php } while ($row_Edificio = mysql_fetch_assoc($Edificio)); ?>
</table>
<p>&nbsp;</p>
<p>Turno
</p>
<p><a href="/Edificios_Utensileos/TurnoRegistro.php">Registrar Turno</a></p>
<table border="1" class="table">
  <tr>
    <td>idTurno</td>
    <td>turno</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_Recordset1['idTurno']; ?></td>
    <td><?php echo $row_Recordset1['turno']; ?></td>
  </tr>
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
<p>&nbsp;</p>
<p>Aulas</p>
<blockquote>
       <blockquote>
         <blockquote>
           <blockquote>
             <blockquote>
               <p><a href="/Edificios_Utensileos/AulaRegistro.php">Registrar Aulas</a></p>
             </blockquote>
           </blockquote>
         </blockquote>
  </blockquote>
</blockquote>
<table border="1" class="table">
  <tr>
    <td>idAulas</td>
    <td>Codigo_aula</td>
    <td>Descripcion</td>
    <td>id_edificio</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Aulas['idAulas']; ?></td>
      <td><?php echo $row_Aulas['Codigo_aula']; ?></td>
      <td><?php echo $row_Aulas['Descripcion']; ?></td>
      <td><?php echo $row_Aulas['id_edificio']; ?></td>
    </tr>
    <?php } while ($row_Aulas = mysql_fetch_assoc($Aulas)); ?>
</table>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>

<div id="footer">
<p> Copyright Softeasy unt&copy; - 2015</p>
</div>

</div>

</body>
</html>
<?php
mysql_free_result($Departamento);

mysql_free_result($Edificio);

mysql_free_result($Aulas);

mysql_free_result($Recordset1);
?>