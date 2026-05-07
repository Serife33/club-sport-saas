<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\EventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventType>
 */
class EventTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventType::class);
    }

    public function findByClub(Club $club): array
    {
        return $this->createQueryBuilder('e')  // On requête sur Event avec l'alias 'e'
            ->join('e.team', 't')              // On joint Team via la relation team, alias 't'
            ->where('t.club = :club')          // On filtre : seulement les teams de ce club
            ->setParameter('club', $club)      // On donne la valeur au paramètre :club
            ->orderBy('e.seasonStartDate', 'DESC') // On trie par date de début de saison
            ->getQuery()                       // On transforme en requête SQL
            ->getResult();                     // On exécute et retourne un tableau d'objets Event
    }
}
