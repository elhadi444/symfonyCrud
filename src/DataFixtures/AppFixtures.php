<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $product = new Product;
        $product->setName('product one');
        $product->setDescription('description for product one');
        $product->setSize(100);

        $manager->persist($product);


        $product = new Product;
        $product->setName('product two');
        $product->setDescription('description for product two');
        $product->setSize(200);

        $manager->persist($product);

        $manager->flush();
    }
}
