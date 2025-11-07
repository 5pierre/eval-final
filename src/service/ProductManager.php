<?php

// src/Service/ProductManager.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function createProduct($typeOfDemand) : bool
    {
        // Logic to create a product based on the type of demand
        return true;    

    }

    public function getHappyMessage(): string
    {
        

        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}