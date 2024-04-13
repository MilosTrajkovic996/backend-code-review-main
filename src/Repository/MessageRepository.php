<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * CODE REVIEW
     * 
     * Previous version: function is attempting to retrieve messages based on the status parameter provided in the request query string.
     * It directly constructs a query using string interpolation, which is prone to SQL injection attacks and not recommended.
     * The function lacks proper parameter binding, making it vulnerable to SQL injection.
     * Additionally, it returns an array of message entities directly without any type hinting or validation.
     * There is also a potential issue with calling `$this->findAll()` if the repository class does not have such a method defined.
     * Overall, this function is insecure, lacks proper error handling, and may not behave as expected due to the usage of potentially undefined methods.
     */


    /**
     * CODE REVIEW AND REFACTOR
     * 
     * This function provides a safer and more maintainable way to retrieve messages based on the status parameter.
     * It uses Doctrine's query builder to construct the query, which is safer than directly interpolating strings.
     * The status parameter is properly bound to the query to prevent SQL injection attacks.
     * The function accepts a nullable string parameter for status, providing flexibility and clarity in usage.
     * It returns an array of Message objects, providing proper type hinting and making the return value clear to the caller.
     * Overall, this function is more secure, maintainable, and robust compared to the previous implementation.
    */
    
    /**
     * Retrieves messages based on status.
     *
     * @param string|null $status The status to filter messages by
     *
     * @return Message[] Returns an array of Message objects
     */
    public function byStatus(?string $status): array
    {
        $qb = $this->createQueryBuilder('m');

        if (!empty($status)) {
            $qb->andWhere('m.status = :status')
                ->setParameter('status', $status);
        }

        $result = $qb->getQuery()->getResult();

        return is_array($result) ? $result : [];

    }
}
