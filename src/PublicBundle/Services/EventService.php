<?php

namespace PublicBundle\Services;
use PublicBundle\Entity\Event;
use PublicBundle\Entity\EventId;
use PublicBundle\Exceptions\EventException;
use PublicBundle\Factory\EventFactory;
use PublicBundle\Library\BaseApiService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventService extends BaseApiService
{
    protected $appRepository = 'PublicBundle:Event';

    public function createEvent($description, $from, $to, $location, $comment) {
        if(!$this->validator->validateStrings([$description, $from, $to, $location, $comment]))
            throw new BadRequestHttpException(EventException::RESPONSE_BAD_REQUEST);

        if(!$this->validator->validateDate($from) || !$this->validator->validateDate($to))
            throw new BadRequestHttpException(EventException::RESPONSE_INVALID_DATE_FORMAT);

        $event = EventFactory::create($description, new \DateTime($from), new \DateTime($to), $location, $comment);

        return $this->repository->save($event);
    }

    public function editEvent($id, $description, $from, $to, $location, $comment) {
        $event = $this->getEvent($id);

        if(!$this->validator->validateNullOrStrings([$description, $from, $to, $location, $comment]))
            throw new BadRequestHttpException(EventException::RESPONSE_BAD_REQUEST);
        if(!$this->validator->validateDate($from) || !$this->validator->validateDate($to))
            throw new BadRequestHttpException(EventException::RESPONSE_INVALID_DATE_FORMAT);

        $from = ($from) ?  new \DateTime($from) : null;
        $to = ($to) ? new \DateTime($to) : null;

        return $this->repository->save(Event::updateObject($event, $description, $from, $to, $location, $comment));
    }

    public function getEventList($limit, $offset, $order) {
        if($order) {
            if(!in_array($order, [Event::ORDER_ASC, Event::ORDER_DESC])) {
                throw new BadRequestHttpException(EventException::RESPONSE_INVALID_ORDER);
            }

            return $this->repository->findBy([], ['fromDate' => $order], ($offset + 1) * $limit, $offset * $limit);
        }
        else {
            return $this->repository->findBy([], ['id' => Event::ORDER_ASC], ($offset + 1) * $limit, $offset * $limit);
        }

    }

    public function deleteEvent($id) {
        $event = $this->getEvent($id);
        $this->repository->remove($event);
    }

    private function getEvent($id) {
        $eventId = (new EventId($id))->getValue();

        if(!$this->validator->validatePositiveNumbers([$eventId]))
            throw new BadRequestHttpException(EventException::RESPONSE_BAD_REQUEST);

        $event = $this->repository->getById($eventId);

        if(!$event)
            throw new NotFoundHttpException(EventException::RESPONSE_NOT_FOUND);

        return $event;

    }
}