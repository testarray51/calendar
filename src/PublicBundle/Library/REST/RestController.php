<?php
namespace PublicBundle\Library\REST;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcher;

use JMS\Serializer as Serializer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

abstract class RestController extends FOSRestController {

	const TYPE_COLLECTION = 'collection';
	const TYPE_MODEL = 'model';

	private $collection = null;
	private $model = null;

	private $type;
	private $response;

	/** @var Serializer\Serializer */
	private $serializer;

	protected $apiServiceName;
	private $apiService;

	public function __construct() {
		$this->response = new JsonResponse();
		$this->serializer = Serializer\SerializerBuilder::create()->build();

		if ($this->apiServiceName == null)
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Api Service not defined!');
	}

	private function send($data, $httpCode = Response::HTTP_OK, $headers = []) {

		$request = $this->container->get('request_stack')->getCurrentRequest();
		$requestFormat = $request->attributes->get('_format', 'json');

		$response = $this->serializer->serialize($data, $requestFormat);

		$this->response->setContent($response);
		$this->response->setStatusCode($httpCode);

		if ($headers) $this->setHeaders($headers);

		$this->response->send();
		exit;
	}

	/**
	 * Accept an action, but the response has no content
	 */
	private function accept() {
		$this->send(null, Response::HTTP_NO_CONTENT);
	}

	protected function sendResponse() {
		if (is_null($this->model) && is_null($this->collection))
			$this->accept();

		if ($this->type == self::TYPE_MODEL)
			$this->send($this->model);

		if ($this->type == self::TYPE_COLLECTION)
			$this->send($this->collection);

		$this->sendError('Bad Request');
	}

	protected function setHeaders($headers) {
		if (!$headers) return;

		foreach ($headers as $key => $header) {
			$this->response->headers->set($key, $header);
		}
	}

	protected function sendError($err, $httpCode = 400, $errorCode = null, $headers = []) {
		$errorCode = $errorCode ? $errorCode : $httpCode;

		$output = array(
			'message' => $err,
		);

		$this->send($output, $httpCode, $headers);
	}

	protected function sendValidationError($err) {
        $this->send($err, 400);
    }


	protected function getService() {
		if (!$this->apiService)
			$this->apiService = $this->get($this->apiServiceName);

		return $this->apiService;
	}

	protected function getModelById($id) {
		return $this->getService()->getByPrimary($id);
	}

	public function getList($limit, $offset) {
		return $this->getService()->getList($limit, $offset);
	}


	/**
	 * @return mixed
	 */
	public function getCollection() {
		return $this->model;
	}

	/**
	 * @param mixed $collection
	 */
	public function setCollection($collection) {
		$this->type = self::TYPE_COLLECTION;
		$this->collection = $collection;
	}

	/**
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel($model) {
		$this->type = self::TYPE_MODEL;
		$this->model = $model;
	}

	/**
	 * The index action handles index/list requests; it should respond with a
	 * list of the requested resources.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing patients.")
	 * @Annotations\QueryParam(name="limit", requirements="\d+", default="20", description="How many patients to return.")
	 *
	 * @param Request $request
     *
	 */
	public function listAction(Request $request) {

		$offset = $request->query->get('offset') ?: 0;
		$limit =  $request->query->get('limit');

		$collection = $this->getList($limit, $offset);

		$this->setCollection($collection);
		$this->sendResponse();

	}

	/**
	 * The get action handles GET requests and receives an 'id' parameter; it
	 * should respond with the server resource state of the resource identified
	 * by the 'id' value.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful",
     *     404 = "Model not found"
	 *   }
	 * )
	 *
	 * @param int $id the patient id
	 *
	 */
	public function getAction($id) {
		$model = $this->getModelById($id);

		if (!$model)
			$this->sendError('Model Not Found!', Response::HTTP_NOT_FOUND);

		$this->setModel($model);
		$this->sendResponse();
	}

	/**
	 * The post action handles POST requests; it should accept and digest a
	 * POSTed resource representation and persist the resource state.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 *
	 * @param Request $request the request object
	 *
	 */
	public function postAction(Request $request) {
		$this->sendError('Method Not Allowed3.', Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * The put action handles PUT requests and receives an 'id' parameter; it
	 * should update the server resource state of the resource identified by
	 * the 'id' value.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 *
	 * @param Request $request the request object
	 * @param int $id the patient id
	 *
	 */
	public function putAction(Request $request, $id) {
		$this->sendError('Method Not Allowed4.', Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * The delete action handles DELETE requests and receives an 'id'
	 * parameter; it should update the server resource state of the resource
	 * identified by the 'id' value.
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     204 = "Returned when successful"
	 *   }
	 * )
	 *
	 *
	 * @param Request $request the request object
	 * @param int $id the patient id
	 *
	 */
	public function deleteAction(Request $request, $id) {
		$this->sendError('Method Not Allowed5.', Response::HTTP_METHOD_NOT_ALLOWED);
	}


}