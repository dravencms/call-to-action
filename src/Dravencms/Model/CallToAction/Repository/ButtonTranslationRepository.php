<?php declare(strict_types = 1);

namespace Dravencms\Model\CallToAction\Repository;


use Dravencms\Model\Locale\Entities\ILocale;
use Dravencms\Database\EntityManager;
use Dravencms\Model\CallToAction\Entities\Button;
use Dravencms\Model\CallToAction\Entities\ButtonTranslation;

class ButtonTranslationRepository
{
    /** @var \Doctrine\Persistence\ObjectRepository|ButtonTranslation */
    private $buttonTranslationRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * CategoryRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->buttonTranslationRepository = $entityManager->getRepository(ButtonTranslation::class);
    }

    /**
     * @param Button $button
     * @param ILocale $locale
     * @return null|ButtonTranslation
     */
    public function getTranslation(Button $button, ILocale $locale): ?ButtonTranslation
    {
        return $this->buttonTranslationRepository->findOneBy(['button' => $button, 'locale' => $locale]);
    }
}
