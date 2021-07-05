<?php

namespace App\Controller;

use App\Entity\Tree;
use App\Repository\TreeRepository;
use App\Services\FakeTree;
use Faker\Provider\Lorem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TreeController extends AbstractController
{
    private $treeRepository;

    public function __construct(TreeRepository $treeRepository)
    {
        $this->treeRepository = $treeRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render("tree/tree.html.twig");
    }

    /**
     * @Route("/api/tree", methods={"GET"})
     */
    public function tree(): JsonResponse
    {
        $tree = $this->treeRepository->buildTree();

        return $this->response($tree);
    }

    /**
     * @Route("/api/node/{id}", methods={"GET"})
     */
    public function getNode(int $id): JsonResponse
    {

        $node = $this->findNode($id);

        return $this->response($node->asArray());
    }

    /**
     * @Route("/api/node/{id}", methods={"POST"})
     */
    public function updateNodeText(int $id, Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);

        $this->requestCheckExist($request, ['text']);

        $node = $this->findNode($id);

        $node->setText($request->get('text'));

        $this->getDoctrine()->getManager()->flush();

        return $this->response($node->asArray(), 200, [
            "Access-Control-Allow-Methods" => "OPTIONS, PUT",
            "Allow" => " OPTIONS, PUT"
        ]);
    }

    /**
     * @Route("/api/node", methods={"POST"})
     */
    public function createNode(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);

        $this->requestCheckExist($request, ['parentId']);

        $parentNode = $this->findNode($request->get('parentId'));
        $lastChildNode = $this->treeRepository->findLastChildNode($parentNode->getId());

        $node = new Tree();
        $node->setText(Lorem::words(rand(3, 10), true));
        $node->setParentId($parentNode->getId());
        $node->setPosition($lastChildNode ? $lastChildNode->getPosition() + 1 : 1);
        $this->getDoctrine()->getManager()->persist($node);
        $this->getDoctrine()->getManager()->flush();

        return $this->response($node->asArray());
    }

    /**
     * @Route("/api/tree-position", methods={"POST"})
     */
    public function updateNodePosition(Request $request): JsonResponse
    {

        $request = $this->transformJsonBody($request);

        $this->requestCheckExist($request, ['targetNodeId', 'destinationNodeId', 'status']);

        $targetNode = $this->findNode($request->get('targetNodeId'));
        $destinationNode = $this->findNode($request->get('destinationNodeId'));

        $status = $request->get('status');

        if ($status == 'drag-above') {

            $position = $destinationNode->getPosition();
            $parentId = $destinationNode->getParentId();

        } elseif ($status == 'drag-below') {

            $position = $destinationNode->getPosition() + 1;
            $parentId = $destinationNode->getParentId();

        } elseif ($status == 'drag-on') {

            $lastChildNode = $this->treeRepository->findLastChildNode($destinationNode->getId());
            $position = $lastChildNode ? $lastChildNode->getPosition() + 1 : 1;
            $parentId = $destinationNode->getId();

        } else {
            throw new \Exception();
        }
        $this->treeRepository->increasePosition($parentId, $position);

        $targetNode->setPosition($position);
        $targetNode->setParentId($parentId);

        $this->getDoctrine()->getManager()->flush();

        return $this->response($targetNode->asArray());
    }

    /**
     * @Route("/api/node/{id}", methods={"DELETE"})
     */
    public function removeNode(int $id): JsonResponse
    {
        $node = $this->findNode($id);
        $this->getDoctrine()->getManager()->remove($node);
        $this->getDoctrine()->getManager()->flush();
        return $this->response(['status' => 'success']);
    }

    protected function findNode(int $id): Tree
    {
        $node = $this->treeRepository->find($id);
        if (!$node) {
            throw $this->createNotFoundException(
                'No node found for id ' . $id
            );
        }
        return $node;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function response(array $data, int $status = 200, array $headers = []): JsonResponse
    {
        $headers["Access-Control-Allow-Origin"] = "*";
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    protected function requestCheckExist($request, $items)
    {

        foreach ($items as $item) {
            if (!$request || !$request->get($item)) {
                throw new \Exception();
            }
        }
    }
}
