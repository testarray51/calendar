<?php

namespace PublicBundle\Repository;

use Doctrine\ORM\EntityRepository;

abstract class BaseRepository extends EntityRepository implements EventRepositoryInterface {

    public function getById($id) {
        return $this->find($id);
    }

    public function findList($limit, $offset) {
        return $this->findBy([], ['id' => 'ASC'], $limit, $offset);
    }

    public function save($entity) {
        if ($entity->getId())
            return $this->update($entity);
        else
            return $this->insert($entity);
    }

    protected function update($entity) {
        $this->_em->flush();

        return $entity;
    }

    protected function insert($entity) {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    public function remove($entity) {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    public function getOneById($id) {
        return $this->findOneBy(['id' => $id]);
    }

}