<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invitation>
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function findByClub(Club $club): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.invitedBy', 'u')
            ->where('u.club = :club')
            ->setParameter('club', $club)
            ->orderBy('i.sentAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
