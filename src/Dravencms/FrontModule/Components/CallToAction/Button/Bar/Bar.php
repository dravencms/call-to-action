<?php declare(strict_types = 1);

namespace Dravencms\FrontModule\Components\CallToAction\Button\Bar;

use Dravencms\Components\BaseControl\BaseControl;
use Dravencms\Locale\CurrentLocaleResolver;
use Dravencms\Model\Locale\Entities\ILocale;
use Dravencms\Model\CallToAction\Repository\ButtonRepository;
use Dravencms\Model\CallToAction\Repository\ButtonTranslationRepository;
use Dravencms\Structure\ICmsActionOption;

/**
 * Created by PhpStorm.
 * User: Adam Schubert
 * Date: 27.2.17
 * Time: 1:53
 */
class Bar extends BaseControl
{
    /** @var ICmsActionOption */
    private $cmsActionOption;

    /** @var ILocale */
    private $currentLocale;

    /** @var ButtonTranslationRepository */
    private $buttonTranslationRepository;

    /** @var ButtonRepository */
    private $buttonRepository;

    public function __construct(
        ICmsActionOption $cmsActionOption,
        ButtonRepository $buttonRepository,
        ButtonTranslationRepository $buttonTranslationRepository,
        CurrentLocaleResolver $currentLocaleResolver
    )
    {
        $this->cmsActionOption = $cmsActionOption;
        $this->buttonRepository = $buttonRepository;
        $this->buttonTranslationRepository = $buttonTranslationRepository;
        $this->currentLocale = $currentLocaleResolver->getCurrentLocale();
    }

    public function render(): void
    {
        $template = $this->template;
        $button = $this->buttonRepository->getOneById($this->cmsActionOption->getParameter('id'));
        $template->buttonTranslation = $this->buttonTranslationRepository->getTranslation($button, $this->currentLocale);
        $template->setFile(__DIR__ . '/Bar.latte');
        $template->render();
    }
}