<?php

namespace App\Custom;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class Query
{
    private EntityManagerInterface $em;

    function __construct (EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @throws Exception
     */
    function getLastUserEmail (): String
    {
        $conn = $this->em->getConnection();
        $query = $conn->prepare('SELECT email FROM user ORDER BY id desc LIMIT 1');
        return $query->executeQuery()->fetchOne();
    }
}