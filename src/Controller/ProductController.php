<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $repository): Response
    {   
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    // dumb way
    // #[Route('/product/{id<\d+>}')]
    // public function show($id, ProductRepository $repository): Response
    // {
    //     $product = $repository->findOneBy(['id' => $id]);

    //     if ($product == null)
    //         throw $this->createNotFoundException('Product not found');
    //     return $this->render('product/show.html.twig', [
    //         'product' => $product
    //     ]);
    // }


    #[Route('/product/{id<\d+>}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/product/new', name: 'app_product_new')]
    public function new(Request $req, EntityManagerInterface $manger): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){

            $manger->persist($product);
            
            $manger->flush();

            $this->addFlash('notice','product added successfully');

            //return $this->redirectToRoute('app_product');
            return $this->redirectToRoute('app_product_show',['id' => $product->getId()]);
        }
        return $this->render('product/new.html.twig',['form' => $form]);
    }


    #[Route('/product/{id<\d+>}/edit', name: 'app_product_edit')]
    public function edit(Product $product, Request $req, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->flush();

            $this->addFlash('notice','product updated successfully');

            return $this->redirectToRoute('app_product_show',['id' => $product->getId()]);
        }
        return $this->render('product/edit.html.twig',['form' => $form]);
    }


    #[Route('/product/{id<\d+>}/delete', name: 'app_product_delete')]
    public function delete(Product $product, Request $req, EntityManagerInterface $manager): Response
    {
        if ($req->isMethod('POST')){

            $manager->remove($product);
            
            $manager->flush();

            $this->addFlash('notice','product deleted successfully');

            return $this->redirectToRoute('app_product',['id' => $product->getId()]);
        }

        return $this->render('product/delete.html.twig', ['id' => $product->getId()]);
    }
}
