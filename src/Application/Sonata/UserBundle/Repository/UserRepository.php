<?php

namespace Application\Sonata\UserBundle\Repository;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function add($user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function remove($id)
    {
        $em = $this->getEntityManager();
        $user = $this->find($id);
        $em->remove($user);
        $em->flush();
    }
}