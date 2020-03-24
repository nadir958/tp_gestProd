<?php


namespace App\Controller;


use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Repository\OrderDetailRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    private $session;

    /**
     * ProduitController constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/produits",name="produits.index")
     * @return Response
     */
    public function index(ProductRepository $productRepo):Response
    {
        return $this->render('produits/index.html.twig',[
            'current_menu' => 'properties','products' =>  $productRepo->findAll()
        ]);
    }
    /**
     * @Route("/add",name="produits.add")
     * @return Response
     */
    public function add():Response
    {
        return $this->render('produits/add.html.twig');
    }
    /**
     * @Route("/save",name="produits.save")
     * @return Response
     */

    public function save(Request $request):Response
    {
        $Product = new Product();
        $Product->setName($request->get('Name'))
            ->setPrice($request->get('Price'))
            ->setQuantity($request->get('Quantity'))
            ->setDescription($request->get('Description'))
            ->setImageURL('image.png');
        $em = $this->getDoctrine()->getManager();
        $em->persist($Product);
        $em->flush();
        return $this->redirectToRoute('produits.index');
    }

    /**
     * @Route("/lastthree",name="produits.lastthree")
     * @return Response
     */
    public function find(ProductRepository $productRepo):Response
    {
        return $this->render('produits/lastThree.html.twig',[
        'current_menu' => 'properties','products' =>  $productRepo->findBy(array(),array('id'=>'DESC'),3)
    ]);
    }

    /**
     * @Route("/panier",name="commande.panier")
     * @return Response
     */
    public function basket(ProductRepository $productRepo):Response
    {
        return $this->render('commande/panier.html.twig',[
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/addpanier/{idprod}",name="commande.ajoutpanier")
     * @return Response
     */
    public function addbasket(ProductRepository $productRepo,$idprod,Request $request):Response
    {
        $product =  $productRepo->find($idprod);
        if ($this->session->has('basket')) {
            $basket = $this->session->get('basket');
            $basket[]['product'] = $product;
            $basket[array_key_last($basket)]['quantity'] = $request->get('commandQuantity');
            $this->session->set('basket', $basket);
        } else {
            $basket = [];
            $basket[]['product'] = $product;
            $basket[array_key_last($basket)]['quantity'] = $request->get('commandQuantity');
            $this->session->set('basket', $basket);
        }
        //$this->flash->add('success', 'The product is added to basket !');
        //dd($this->session->get('basket'));
        return $this->redirectToRoute('commande.panier');
    }

    /**
     * @Route("/commande",name="commande.ajoutcommande")
     * @return Response
     */

    public function commande(ProductRepository $productRepo)
    {
        $em = $this->getDoctrine()->getManager();
        $order = new Order();
        $em->persist($order);
        $basket = $this->session->get('basket');
        if ($this->session->has('basket')) {
            foreach ($basket as $key=> $product){
                $productToOrder =  $productRepo->find($product['product']);
                $productToOrder->setQuantity((int)$productToOrder->getQuantity() - (int)$product['quantity']);
                $orderDetail = new OrderDetail();
                $orderDetail->setProductOrder($order);
                $orderDetail->setProduct($productToOrder);
                $orderDetail->setQuantity($product['quantity']);
                $em->persist($orderDetail);
            }
            $em->flush();
            $this->session->clear();

        }
        return $this->render('commande/panier.html.twig');
    }

    /**
     * @Route("/commande/afficher/{idprod}", name="commande.commande")
     **/
    public function show($idprod, OrderDetailRepository $orderDRepo, ProductRepository $productRepo)
    {
        $product =  $productRepo->find($idprod);
        $orderDetails = $orderDRepo->findBy(array('product'=> $product));
        return new Response($this->render('commande/commande.html.twig', ['product'=> $product, 'orderDetails'=> $orderDetails]));
    }
}

