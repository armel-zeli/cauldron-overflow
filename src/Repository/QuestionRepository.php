<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

     /**
      * @return Question[] Returns an array of Question objects
      */
    public function findAllAskedOrderByNewest() : array
    {
        return $this->addIsAskedQueryBuilder()
            ->orderBy('q.askedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    private function addIsAskedQueryBuilder(QueryBuilder $qb = null) : QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('q.askedAt IS NOT NULL');

    }

    /**
     * @param QueryBuilder|null $qb
     *
     * @return QueryBuilder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null) : QueryBuilder
    {
        return $qb ?: $this->createQueryBuilder('q');
    }


}
