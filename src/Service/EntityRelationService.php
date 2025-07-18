<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class EntityRelationService
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    /**
     * HowTose
     */
     /* 
     $shortUrlIds = $em->createQueryBuilder()->select('s.id')
                        ->from(ShortUrl::class, 's')
                        ->getQuery()
                        ->getSingleColumnResult();

        $relations = $entityRelationService->entityRelationsQueryBuilder($conn, ['ShortUrl', 'Tag'], $shortUrlIds)
                            ->fetchAllAssociative();
     */

    /**
     * Returns relations between entities.
     *
     * @param string[] $entities List of entities, the first is the main one
     * @param int[]|null $mainEntityIds Optional filter by main entity IDs
     * @return array
     */
    function entityRelationsQueryBuilder(Connection $conn, array $entities, array $primaryEntityIds = []): \Doctrine\DBAL\Query\QueryBuilder
    {
        if (count($entities) < 2) {
            throw new \InvalidArgumentException('Should be at least two entities.');
        }

        $primaryEntity = $entities[0];
        $otherEntities = array_slice($entities, 1);

        $qb = $conn->createQueryBuilder()
            ->select('owner_entity', 'owner_entity_id', 'related_entity', 'related_entity_id')
            ->from('entity_relation');

        $orX = $qb->expr()->orX();

        foreach ($otherEntities as $relatedEntity) {
            // Primary > Related
            $cond1 = $qb->expr()->andX(
                $qb->expr()->eq('owner_entity', ':oe1_' . $relatedEntity),
                $qb->expr()->eq('related_entity', ':re1_' . $relatedEntity)
            );
            $qb->setParameter('oe1_' . $relatedEntity, $primaryEntity);
            $qb->setParameter('re1_' . $relatedEntity, $relatedEntity);

            if (!empty($primaryEntityIds)) {
                $cond1->add($qb->expr()->in('owner_entity_id', ':oe1_ids_' . $relatedEntity));
                $qb->setParameter('oe1_ids_' . $relatedEntity, $primaryEntityIds, Connection::PARAM_INT_ARRAY);
            }

            $orX->add($cond1);

            // Related > Primary
            $cond2 = $qb->expr()->andX(
                $qb->expr()->eq('owner_entity', ':oe2_' . $relatedEntity),
                $qb->expr()->eq('related_entity', ':re2_' . $relatedEntity)
            );
            $qb->setParameter('oe2_' . $relatedEntity, $relatedEntity);
            $qb->setParameter('re2_' . $relatedEntity, $primaryEntity);

            if (!empty($primaryEntityIds)) {
                $cond2->add($qb->expr()->in('related_entity_id', ':re2_ids_' . $relatedEntity));
                $qb->setParameter('re2_ids_' . $relatedEntity, $primaryEntityIds, Connection::PARAM_INT_ARRAY);
            }

            $orX->add($cond2);
        }

        $qb->where($orX);

        return $qb;
    }
}
