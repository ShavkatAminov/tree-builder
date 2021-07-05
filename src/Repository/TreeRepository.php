<?php

namespace App\Repository;

use App\Entity\Tree;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Provider\Lorem;

/**
 * @method Tree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tree[]    findAll()
 * @method Tree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tree::class);
    }

    private function getChildren($id) {

        $children = $this->findByParentId($id);
        if (!$children) return null;
        $arr = [];
        foreach ($children as $child) {
            $arr[] = [
                'id' => $child -> getId(),
                'text' => $child -> getText(),
                'children' => $this->getChildren($child -> getId())
            ];
        }
        return $arr;
    }

    public function buildTree() {
        $root = $this->findRootElement();
        return [
            'id' => $root->getId(),
            'text' => $root->getText(),
            'children' => $this->getChildren($root->getId())
        ];
    }

    /**
     * @param int $parentId
     * @return Tree[]|null
     */

    public function findByParentId(int $parentId ) {
        return $this->createQueryBuilder('tree')
            ->andWhere('tree.parent_id = :parentId')
            ->setParameter('parentId', $parentId)
            ->orderBy('tree.position')
            ->getQuery()
            ->getResult();
    }

    public function findRootElement() {
        return $this->createQueryBuilder('tree')
            ->where('tree.parent_id is NULL')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $parentId
     * @return Tree[]|null
     */
    public function findLastChildNode(int $parentId) {
        return $this->createQueryBuilder('tree')
            ->where('tree.parent_id = :parentId')
            ->setParameter('parentId', $parentId)
            ->orderBy('tree.position', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function increasePosition($parentId, $position) {
        $this->createQueryBuilder('tree')
            ->update()
            ->set('tree.position', 'tree.position + 1')
            ->where('tree.parent_id = :parentId')
            ->andWhere('tree.position >= :position')
            ->setParameter('parentId', $parentId)
            ->setParameter('position', $position)
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return Tree[] Returns an array of Tree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tree
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
