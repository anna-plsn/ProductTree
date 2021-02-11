<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(): Response {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setParentId(1);
        $product->setTitle('goods');

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product): Response {
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$product->getId()
            );
        }
        return new Response("Check out this great product: ".$product->getTitle().". His parent: ".$product->getParentId());
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function delete(Product $product): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$product->getId().' can not delete'
            );
        }
        return new Response("Remove this great product: ".$product->getTitle().". His parent was: ".$product->getParentId());
    }

    /**
     * @Route("/", name="product_showAll")
     */
    public function index(): Response {
        $entityManager = $this->getDoctrine();
        $products = $entityManager->getRepository(Product::class)->findAll();
        return new Response(print_r($products));
    }

    /**
     * @Route("/product/update/{id}/{title}")
     */
    public function update($id, $title) {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $product->setParentId(1);
        $product->setTitle($title);
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
    }
}
