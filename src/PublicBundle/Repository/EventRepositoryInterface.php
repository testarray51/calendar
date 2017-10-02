<?php

namespace PublicBundle\Repository;


interface EventRepositoryInterface
{
    public function getById($id);
    public function findList($limit, $offset);
    public function save($entity);
    public function remove($entity);
    public function getOneById($id);

}
