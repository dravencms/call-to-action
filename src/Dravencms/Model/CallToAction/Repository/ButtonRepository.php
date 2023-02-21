<?php declare(strict_types = 1);

namespace Dravencms\Model\CallToAction\Repository;


use Dravencms\Database\EntityManager;
use Dravencms\Model\CallToAction\Entities\Button;

class ButtonRepository
{
    /** @var \Doctrine\Persistence\ObjectRepository|Button */
    private $buttonRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * CategoryRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->buttonRepository = $entityManager->getRepository(Button::class);
    }

    /**
     * @param $id
     * @return null|Button
     */
    public function getOneById(int $id): ?Button
    {
        return $this->buttonRepository->find($id);
    }

    /**
     * @param $id
     * @return Button[]
     */
    public function getById($id)
    {
        return $this->buttonRepository->findBy(['id' => $id]);
    }

    /**
     * @return QueryBuilder
     */
    public function getButtonItemsQueryBuilder()
    {
        $qb = $this->buttonRepository->createQueryBuilder('b')
            ->select('b');
        return $qb;
    }

    /**
     * @return Button[]
     */
    public function getAll()
    {
        return $this->buttonRepository->findAll();
    }

    /**
     * @param array $parameters
     * @return null|Button
     */
    public function getOneByParameters(array $parameters): ?Button
    {
        return $this->buttonRepository->findOneBy($parameters);
    }

    /**
     * @param $identifier
     * @param Button|null $buttonIgnore
     * @return boolean
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isIdentifierFree(string $identifier, Button $buttonIgnore = null): bool
    {
        $qb = $this->buttonRepository->createQueryBuilder('b')
            ->select('b')
            ->where('b.identifier = :identifier')
        ->setParameter('identifier', $identifier);

        if ($buttonIgnore)
        {
            $qb->andWhere('b != :buttonIgnore')
            ->setParameter('buttonIgnore', $buttonIgnore);
        }

        return is_null($qb->getQuery()->getOneOrNullResult());
    }
}