<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CestaCompra {

    //Atributos de la clase
    protected $carrito = [];
    protected $requestStack;
    protected $session;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->session = $requestStack->getCurrentRequest()->getSession();
        $this->cargarCesta();
    }

    //Cargamos la cesta de la sesión si hubiera, sino se crea
    public function cargarCesta() {
        if ($this->session->has('cesta')) {
            //Si existe en la sesión, lo guardamos
            $this->carrito = $this->session->get('cesta');
        } else {
            //Si no existe en la sesión, lo creamos
            $this->carrito = [];
        }
    }

    //Guardamos la cesta en la sesión
    public function guardaCesta() {
        $this->session->set('cesta', $this->carrito);
    }

    //Añadimos el articulo a la cesta
    public function anadirArticulo($producto, $unidades) {

        //Sacamos el código del producto
        $codigoProducto = $producto->getCod();

        if (array_key_exists($codigoProducto, $this->carrito)) {
            //Si este producto existe en el carrito, sumamos las unidades
            $this->carrito[$codigoProducto]['unidades'] += $unidades;
        } else {
            //Si no existe, lo añadimos
            $this->carrito[$codigoProducto]['producto'] = $producto;
            $this->carrito[$codigoProducto]['unidades'] = $unidades;
        }

        return $this->carrito;
    }

    //Eliminar las unidades de un producto
    public function eliminarUnidades($producto, $unidades) {
        //Sacamos el código del producto pasado por parametro
        $codigoProducto = $producto->getCod();

        //Comprobamos si existe el producto en la cesta
        if (array_key_exists($codigoProducto, $this->carrito)) {

            $unidades_anteriores = $this->carrito[$codigoProducto]['unidades'];
            //Compruebo que las unidades que se quieren restar no sean mayores que las existentes
            if ($unidades_anteriores > $unidades) {

                //Cambio las unidades
                $this->carrito[$codigoProducto]['unidades'] -= $unidades;
            } else {
                unset($this->carrito[$codigoProducto]);
            }
        }
    }

    //Obtener todos los productos que están en la cesta
    public function getProductos() {
        return $this->carrito;
    }

    //Devuelve el coste de los productos que figuran en la cesta
    public function precioTotal() {
        $coste_total = 0;
        //Recorremos el objeto, sumando las unidades
        foreach ($this->carrito as $producto) {
            $coste_total += $producto['unidades'] * $producto['producto']->getPrice();
        }

        return $coste_total;
    }

    //Función para vaciar la cesta
    public function vacia_cesta() {
        $this->carrito = [];
    }

}
