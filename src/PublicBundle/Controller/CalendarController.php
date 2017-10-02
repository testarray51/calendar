<?php

namespace PublicBundle\Controller;

use PublicBundle\Library\REST\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations;
use JMS\Serializer as Serializer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ActionController
 * @package ApiBundle\Controller
 *
 * @RouteResource("Event", pluralize=false)
 */
class CalendarController extends RestController
{
    protected $apiServiceName = 'calendar';

    /**
     * create a new event in a calendar
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned the event created",
     *     400 = "Bad request: Invalid params"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="description", requirements="string", nullable=false, description="The description of the event")
     * @Annotations\QueryParam(name="from", requirements="string", nullable=false, description="Start date")
     * @Annotations\QueryParam(name="to", requirements="string", nullable=false, description="End date")
     * @Annotations\QueryParam(name="location", requirements="string", nullable=false, description="The location of the event")
     * @Annotations\QueryParam(name="comment", requirements="string", nullable=false, description="put a comment to an event")
     * @param Request $request the request object
     *
     */
    public function postAction(Request $request) {
        $description = $request->request->get('description', null);
        $from = $request->request->get('from', null);
        $to = $request->request->get('to', null);
        $location = $request->request->get('location', null);
        $comment = $request->request->get('comment', null);
        $model = $this->getService()->createEvent($description, $from, $to, $location, $comment);
        $this->setModel($model);
        $this->sendResponse();
    }

    /**
     * edit an event in a calendar
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned the edited event",
     *     400 = "Bad request: Invalid params"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="description", requirements="string", nullable=true, description="The description of the event")
     * @Annotations\QueryParam(name="from", requirements="string", nullable=true, description="Start date")
     * @Annotations\QueryParam(name="to", requirements="string", nullable=true, description="End date")
     * @Annotations\QueryParam(name="location", requirements="string", nullable=true, description="The location of the event")
     * @Annotations\QueryParam(name="comment", requirements="string", nullable=true, description="put a comment to an event")
     * @param $id
     * @param Request $request the request object
     *
     */
    public function putAction(Request $request, $id) {
        $description = $request->request->get('description', null);
        $from = $request->request->get('from', null);
        $to = $request->request->get('to', null);
        $location = $request->request->get('location', null);
        $comment = $request->request->get('comment', null);
        $model = $this->getService()->editEvent($id, $description, $from, $to, $location, $comment);
        $this->setModel($model);
        $this->sendResponse();
    }

    /**
     * Display events in a calendar chronologically or not
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Bad request Invalid params"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing patients.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="20", description="How many patients to return.")
     * @Annotations\QueryParam(name="order", requirements="string", description="Order ASC or DESC, default: null")

     * @param Request $request
     *
     */
    public function listAction(Request $request) {

        $offset = $request->query->get('offset', 0);
        $limit = $request->query->get('limit', 100);
        $order = $request->query->get('order', null);
        $collection = $this->getService()->getEventList($limit, $offset, $order);
        $this->setCollection($collection);
        $this->sendResponse();

    }

    /**
     * remove an event from a calendar
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned if the event was deleted",
     *     400 = "Bad request Invalid params",
     *     404 = "Event not found"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     * @param int $id the event id
     * @return JsonResponse
     *
     */
    public function deleteAction(Request $request, $id) {
        $this->getService()->deleteEvent($id);

        return (new JsonResponse(["message" => "Event was deleted"]))->setStatusCode(Response::HTTP_OK);
    }

}