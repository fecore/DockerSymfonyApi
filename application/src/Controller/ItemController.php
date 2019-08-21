<?php

namespace App\Controller;

use App\Entity\Item;
use App\Message\ItemChangesNotification;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractFOSRestController
{

    private $bus;
    private $validator;

    public function __construct(MessageBusInterface $bus, ValidatorInterface $validator)
    {
        $this->bus = $bus;
        $this->validator = $validator;
    }
    
    /**
     * @Rest\Get("/", name="items")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $items = $repository->findall();

        return $this->handleView($this->view($items));
    }
    /**
     * @Rest\Post("/", name="item_create")
     */
    public function create(Request $request)
    {
        $item = new Item();

        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(),true);

        $item->setName($data['name'] ?? "");
        $item->setIsActive($data['isActive'] ?? 0);

        $errors = $this->validator->validate($item);

        if (count($errors) > 0) {
            return $this->handleView($this->view($errors, Response::HTTP_BAD_REQUEST));
        }

        $entityManager->persist($item);
        $entityManager->flush();

        // RabbitMQ
        $this->bus->dispatch(new ItemChangesNotification("update", $item->getId(), $item->getName(), $item->getUpdatedAt()->format("Y-m-d H:i:s")));


        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_CREATED,
                'reason_phrase' => 'Successfully created',
            ],
            Response::HTTP_CREATED)
        );
    }

    /**
     * @Rest\Put("/{item}", name="item_update")
     */
    public function update(Item $item, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        // If not filled just replace with an old value
        $item->setName($data['name'] ?? $item->getName());
        $item->setIsactive($data['isActive'] ?? $item->getIsActive());

        $errors = $this->validator->validate($item);

        if (count($errors) > 0) {
            return $this->handleView($this->view($errors, Response::HTTP_BAD_REQUEST));
        }

        $entityManager->flush();

        // RabbitMQ
        $this->bus->dispatch(new ItemChangesNotification("update", $item->getId(), $item->getName(), $item->getUpdatedAt()->format("Y-m-d H:i:s")));

        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_ACCEPTED,
                'reason_phrase' => 'Successfully updated',
            ],
            Response::HTTP_ACCEPTED)
        );
    }

    /**
     * @Rest\Delete("/{item}", name="item_delete")
     */
    public function delete(Item $item)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $id = $item->getId();

        $entityManager->remove($item);
        $entityManager->flush();

        // RabbitMQ
        $this->bus->dispatch(new ItemChangesNotification("delete", $id));

        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_ACCEPTED,
                'reason_phrase' => 'Successfully deleted',
            ],
            Response::HTTP_ACCEPTED)
        );
    }
}
