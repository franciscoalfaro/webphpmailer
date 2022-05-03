<?php

class Buscar extends Base{
    private $model;

    function __construct(){
        $this->model = $this->model("BuscarModel");
    }

    public function producto(){
        $sesion = new Sesion();
        $datas = array();
        $buscar = Valida::cadena(isset($_GET["buscar"])?$_GET["buscar"]:"");
        $totalBuscar = 0;
        if(!empty($buscar)){
            $totalBuscar = $this->model->totalBuscar($buscar);
            if($totalBuscar<=1){
                $total = 1;
            }else{
                $total = $totalBuscar/4;
                $total = ceil($total);
            }
            if($_GET['numeroBuscar'] <= 1){
                $_GET['numeroBuscar'] = 1;
                $limiteInicial = 0;
            }else if($_GET['numeroBuscar'] > $total){
                $_GET['numeroBuscar'] = 1;
                $limiteInicial = 0;
            }else{
                $limiteInicial = abs(($_GET['numeroBuscar'] - 1) * 4);            
            }
            $datas = $this->model->getProductosBuscar($buscar, $limiteInicial, 4);
            if(!empty($datas)){
                $data = Caratula::caratula("Productos Buscados", true, false, false, "Tienda Virtual", $datas);
                $dataDos = Caratula::caratulaBuscar($data,$totalBuscar,$buscar);
                $this->view("productosBuscar", $dataDos);
            }else{
                $data = Caratula::caratula("Productos Buscados", true, false, false, "Tienda Virtual", $datas);
                $dataDos = Caratula::caratulaBuscar($data,$totalBuscar,$buscar);
                $this->view("productosBuscar", $dataDos);
            }
        }else{
            $data = Caratula::caratula("Productos Buscados", true, false, false, "Tienda Virtual", $datas);
            $dataDos = Caratula::caratulaBuscar($data,$totalBuscar,$buscar);
            $this->view("productosBuscar", $dataDos);
        }
    }
}
?>