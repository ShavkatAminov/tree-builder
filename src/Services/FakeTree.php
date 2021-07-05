<?php


namespace App\Services;


use App\Entity\Tree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Faker\Provider\Lorem;

class FakeTree
{
    private $entityManager;
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param $parentId
     * @param $position
     * @return Tree
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNode($parentId, $position) {
        $node = new Tree();
        $node->setText(Lorem::words(rand(3,10), true));
        $node->setParentId($parentId);
        $node->setPosition($position);
        $this->entityManager->persist($node);
        $this->entityManager->flush();
        return $node;
    }
    public function create(int $countElements = 500, $treeId = null)
    {
        if (is_null($treeId)){
            $node = $this->createNode(null, 0);
            $treeId = $node->getId();
        }
        $position = [];
        $treeQueue = [$treeId];
        $queueNumber = 0;

        while ($queueNumber < sizeof($treeQueue)) {

            $childrenCount = min(rand(10, 20), $countElements);
            $countElements -= $childrenCount;
            $treeId = $treeQueue[$queueNumber];
            $queueNumber ++;
            $position[$treeId] = $position[$treeId] ?? 0;

            while ($childrenCount -- ) {
                $child = $this->createNode($treeId, ++ $position[$treeId]);
                array_push($treeQueue , $child->getId());
            }
        }
    }
}
