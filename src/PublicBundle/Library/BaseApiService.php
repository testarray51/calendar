<?php

namespace PublicBundle\Library;

use PublicBundle\Services\ValidationService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseApiService {
	/** @var  string */
	protected $appRepository;
	/** @var \Doctrine\ORM\EntityRepository  */
	protected $repository;

	/** @var string  */
	protected $getByIdMethod = 'getById';
	protected $getListMethod = 'findList';

	/** @var EntityManager  */
	protected $em;
	/** @var Container  */
	protected $container;
    /** @var ValidationService */
    protected $validator;

	/**
	 * BaseApiService constructor.
	 * @param EntityManager $entityManager
	 * @param Container $container
     * @param ValidationService $validator
	 */
	public function __construct(EntityManager $entityManager, Container $container, ValidationService $validator) {

		if ($this->appRepository == null)
			throw new Exception('Service app repository not defined in child class', Response::HTTP_INTERNAL_SERVER_ERROR);

		$this->em = $entityManager;
		$this->container = $container;
		$this->repository = $this->em->getRepository($this->appRepository);
		$this->validator = $validator;
	}

	public function getByPrimary($primary) {
		return $this->repository->{$this->getByIdMethod}($primary);
	}

	public function getList($limit, $offset) {
		return $this->repository->{$this->getListMethod}($limit, $offset);
	}




}