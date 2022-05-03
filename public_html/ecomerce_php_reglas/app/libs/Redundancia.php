<?php

class Redundancia {
    function __construct() {}

    static function validacionDatosCliente(){
        $datas = array();
        $errores = array();

        $nombre = Valida::cadena(isset($_POST["nombre"])?$_POST["nombre"]:"");
        $apellidoPaterno = Valida::cadena(isset($_POST["apellidoPaterno"])?$_POST["apellidoPaterno"]:"");
        $apellidoMaterno = Valida::cadena(isset($_POST["apellidoMaterno"])?$_POST["apellidoMaterno"]:"");
        $email = Valida::cadena(isset($_POST["email"])?$_POST["email"]:"");
        $direccion = Valida::cadena(isset($_POST["direccion"])?$_POST["direccion"]:"");
        $telefono = Valida::cadena(isset($_POST["telefono"])?$_POST["telefono"]:"");

        if($nombre == ""){
            array_push($errores, "El nombre es requerido");
        }
        if($apellidoPaterno == ""){
            array_push($errores, "El apellido paterno es requerido");
        }
        if($email == ""){
            array_push($errores, "El email es requerido");
        }
        if($direccion == ""){
            array_push($errores, "La direccion es requerida");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errores, "El correo electronico no es valido");
        }
        if($telefono == ""){
            array_push($errores, "El telefono es requerido");
        }else if(Valida::validaCodigoCelular($telefono)){
            if(strlen($telefono)==12){
                $numeroCelular = substr($telefono, 3, 9);
                if(!is_numeric($numeroCelular)){
                    array_push($errores, "despues del prefijo +56 debe ingresar su nunmero de celular");
                }
            }else{
                array_push($errores, "Numero de telefono invalido");
            }
        }else{
            if(strlen($telefono)==9){
                if(!is_numeric($telefono)){
                    array_push($errores, "el telefono debe ser un numero");
                }else{
                    $telefono = "+56".$telefono;
                }
            }else{
                array_push($errores, "Numero de telefono invalido");
            }
        }

        $datas = [
            "nombre" => $nombre,
            "apellidoPaterno" => $apellidoPaterno,
            "apellidoMaterno" => $apellidoMaterno,
            "email" => $email,
            "direccion" => $direccion,
            "telefono" => $telefono
        ];

        $data = [
            "datas" => $datas,
            "errores" => $errores
        ];

        return $data;
    }

    static function validacionFiltros(){
        $datas = array();
        $errores = array();

        $fechaInicio = isset($_GET["fechaInicio"])?$_GET["fechaInicio"]:"";
        $fechaMaxima = isset($_GET["fechaMaxima"])?$_GET["fechaMaxima"]:"";
        $productos = isset($_GET["productos"])?$_GET["productos"]:"";
        $productos = ($productos=="")?"0":"1";
        $categoria = isset($_GET["categoria"])?$_GET["categoria"]:"";
        $categoria = ($categoria=="")?"0":"1";
        if(!empty($fechaInicio)){
            if(Valida::fecha($fechaInicio)){
                array_push($errores,"La fecha no es valida (AAAA-MM-DD)");
            }else if(Valida::fechaDia($fechaInicio)){
                array_push($errores,"La fecha de inicio no puede ser mayor a la del dia de hoy");
            }
            if(!empty($fechaMaxima)){
                if(Valida::fecha($fechaInicio)){
                    array_push($errores,"La fecha de inicio no es valida (AAAA-MM-DD)");
                }else if(Valida::fechaDia($fechaInicio)){
                    array_push($errores,"La fecha de inicio no puede ser mayor a la del dia de hoy");
                }
            }
        }
        if(empty($errores)){
            if($fechaInicio>$fechaMaxima){
                array_push($errores,"Las fechas no son validas");
            }
        }
        if($productos==1&&$categoria==1){
            array_push($errores,"no puede filtrar por productos y categorias a la vez");
        }

        $datas = [
            "fechaInicio" =>$fechaInicio,
            "fechaMaxima" =>$fechaMaxima,
            "productos" =>$productos,
            "categoria" =>$categoria
        ];

        $data = [
            "datas" =>$datas,
            "errores" =>$errores
        ];

        return $data;
    }
    
    static function queryCarroTemporal($datas){
        $sql = "'".$datas["nombre"]."', ";
        $sql.= "'".$datas["apellidoPaterno"]."', ";
        $sql.= "'".$datas["apellidoMaterno"]."', ";
        $sql.= "'".$datas["email"]."', ";
        $sql.= "'".$datas["direccion"]."', ";
        $sql.= "'".$datas["telefono"]."', ";
        return $sql;
    }
}
?>