<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tax::class);
    }

    public function findByCode(string $code): ?Tax
    {
        /** @var Tax|null $result */
        $result =  $this->findOneBy(['code' => $code]);
        return $result;
    }
}
