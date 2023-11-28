<?php

namespace App\Controller;

//use Symfony\Component\Validator\Constraints\DateTime;

use App\Entity\Familia;
use App\Entity\Pedido;
use App\Entity\PedidosProductos;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Service\CestaCompra;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TheSeer\Tokenizer\Exception;

/**
 * IsGranted("ROLE_USER")
 */
class PedidosBaseController extends AbstractController {

    //Para el correo electrónico de confirmación
    private $mailer;

    public function __construct(Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    //suplementación
    /**
     * @Route("/familia", name="familia")
     */
    public function obtenerFamilia(ManagerRegistry $doctrine): Response {
        $familias = $doctrine->getRepository(Familia::class)->findAll();
        $argumentos = ['familias' => $familias];
        return $this->render('listado_familias.html.twig', $argumentos);
    }
    
  
    
 
    /**     Lo de aqui abajo es el path que ponemos en los action y href
     * @Route("/productos/{familia}", name="productos")
     */
    public function obtenerProductos(ManagerRegistry $doctrine, $familia): Response {
        $productos = $doctrine->getRepository(Familia::class)->find($familia)->getProductos();
        $argumentos = ['productos' => $productos];
        return $this->render('listado_productos.html.twig', $argumentos);
    }

 
 /**
     * @Route("/mostrar-imagen-producto/{id}", name="mostrar_imagen_producto")
     */
    public function mostrarImagenProducto($id)
    {
        $producto = $this->getDoctrine()->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException('El producto no fue encontrado');
        }

        $imagenData = $producto->getImg();

        // Devuelve una respuesta de tipo imagen
        return new Response($imagenData, 200, [
            'Content-Type' => 'image/jpeg', // Ajusta según el tipo de imagen que estés utilizando
            'Content-Disposition' => 'inline; filename="imagen.jpg"',
        ]);
    }
    
    
    
    /**
     * @Route("/anadir/{producto_id}", name="anadir")
     */
    public function anadir(Request $request, ManagerRegistry $doctrine, $producto_id, CestaCompra $cesta): Response {
        //Obtenemos el producto por el id               
        $producto = $doctrine->getRepository(Producto::class)->find($producto_id);

        //Recogemos las unidades
        $unidades = $request->request->get('unidades');

        //Aquí tendremos que llamar a la función de la cesta anadirArticulo
        $cesta->anadirArticulo($producto, $unidades);

        //Guardamos la cesta
        $cesta->guardaCesta();

        //Redirigimos a cesta
        return $this->redirectToRoute('cesta');
    }

    /**
     * @Route("/cesta", name="cesta")
     */
    public function mostrarCesta(CestaCompra $cesta): Response {
        $cesta->cargarCesta();

        //Calculamos el precio total
        $precioTotal = $cesta->precioTotal();
        $argumentos = ['cesta' => $cesta->getProductos(), 'total' => $precioTotal, 'error' => false];
        return $this->render('cesta.html.twig', $argumentos);
    }

    /**
     * @Route("/eliminar/{producto_id}", name="eliminar")
     */
    public function eliminar(Request $request, ManagerRegistry $doctrine, $producto_id, CestaCompra $cesta): Response {
        //Obtenemos el producto por el id                
        $producto = $doctrine->getRepository(Producto::class)->find($producto_id);

        //Recogemos las unidades
        $unidades = $request->request->get('unidades');

        //Aquí tendremos que llamar a la función de la cesta anadirArticulo
        $cesta->eliminarUnidades($producto, $unidades);

        //Guardamos la cesta
        $cesta->guardaCesta();

        //Redirigimos a cesta
        $argumentos = ['cesta' => $cesta->getProductos(), 'total' => $cesta->precioTotal()];
        return $this->render('cesta.html.twig', $argumentos);
    }

    /**
     * @Route("/comprar", name="comprar")
     */
    public function comprar(ManagerRegistry $doctrine, CestaCompra $cesta): Response {
        //Recoger el nombre del usuario
        $id_user = $this->getUser();
        $usuario = $doctrine->getRepository(Usuario::class)->find($id_user);

        //Recogemos el coste total
        $coste = $cesta->precioTotal();

        //Crear variable fecha
        $date = date('d-m-Y h:i:s');
        $fecha = DateTime::createFromFormat('d-m-Y h:i:s', $date);

        //Creamos el objeto producto:
        $pedido = new Pedido();

        //Introducimos los datos en el pedido
        $pedido->setFecha($fecha);
        $pedido->setCoste($coste);
        $pedido->setUsuario($usuario);

        //Insertamos el pedido
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($pedido);

        //Insertar en la base de datos el pedido en foreach
        foreach ($cesta->getProductos() as $producto) {

            $pedidosProducto = new PedidosProductos;
            $pedidosProducto->setUnidades($producto['unidades']);
            $prod = $doctrine->getRepository(Producto::class)->find($producto['producto']->getId());
            $pedidosProducto->setProducto($prod);
            $pedidosProducto->setPedido($pedido);

            $doctrine->persist($pedidosProducto);
        }

        //Hacemos flush a la bbdd dentro de un try-catch
        try {
            $doctrine->flush();

            //Enviamos el correo
            $mensaje = (new \Swift_Message('Confirmación de pedido'))
                    ->setFrom('danieljimplaysix@gmail.com')
                    ->setTo($usuario->getEmail())
                    ->setBody($this->renderView('confirmacion_pedido.html.twig',
                            ['pedido' => $pedido, 'cesta' => $cesta->getProductos()]), 'text/html');
            $this->mailer->send($mensaje);

            //Antes de eliminar la cesta, guardamos los productos de la cesta 
            //para mostrarlos en la confirmación de la compra
            $productosCesta = $cesta->getProductos();

            //Vaciar la cesta
            $cesta->vacia_cesta();
            //Guardar la cesta
            $cesta->guardaCesta();

            //Le pasamos el pedido y los productos de la cesta
            $argumentos = ['pedido' => $pedido, 'productosCesta' => $productosCesta];
            //Lo pasamos a la plantilla
            return $this->render('confirmacion_compra.html.twig', $argumentos);
        } catch (Exception $ex) {

            $argumentos = ['cesta' => $cesta->getProductos(), 'total' => $cesta->precioTotal(), 'error' => true];
            return $this->render('cesta.html.twig', $argumentos);
        }
    }

    /**
     * @Route("/historial", name="historial")
     */
    public function historial(ManagerRegistry $doctrine): Response {

        //Pedimos el pedido a la bbdd
        $pedidos = $doctrine->getRepository(Pedido::class)->findBy(['usuario' => $this->getUser()]);

        $argumentos = ['pedidos' => $pedidos];
        //Lo pasamos a la plantilla
        return $this->render('historial.html.twig', $argumentos);
    }

}
