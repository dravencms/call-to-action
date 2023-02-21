<?php declare(strict_types = 1);

/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\AdminModule\Components\CallToAction\ButtonForm;

use Dravencms\Components\BaseForm\BaseFormFactory;
use Dravencms\File\File;
use Dravencms\Model\File\Entities\Structure;
use Dravencms\Model\File\Repository\StructureFileRepository;
use Dravencms\Model\File\Repository\StructureRepository;
use Dravencms\Model\Locale\Repository\LocaleRepository;
use Dravencms\Model\Structure\Repository\MenuRepository;
use Dravencms\Database\EntityManager;
use Dravencms\Model\CallToAction\Entities\Button;
use Dravencms\Model\CallToAction\Entities\ButtonTranslation;
use Dravencms\Model\CallToAction\Repository\ButtonRepository;
use Dravencms\Model\CallToAction\Repository\ButtonTranslationRepository;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Salamek\Files\FileStorage;

class ButtonForm extends Control
{
    /** @var BaseFormFactory */
    private $baseFormFactory;

    /** @var ButtonRepository */
    private $buttonRepository;

    /** @var ButtonTranslationRepository */
    private $buttonTranslationRepository;

    /** @var StructureRepository */
    private $structureRepository;

    /** @var LocaleRepository */
    private $localeRepository;

    /** @var MenuRepository */
    private $menuRepository;

    /** @var StructureFileRepository */
    private $structureFileRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var File */
    private $file;

    /** @var FileStorage */
    private $fileStorage;

    /** @var null|Button */
    private $button = null;

    /** @var null|callable */
    public $onSuccess = null;

    /**
     * ButtonForm constructor.
     * @param BaseFormFactory $baseForm
     * @param ButtonRepository $buttonRepository
     * @param ButtonTranslationRepository $buttonTranslationRepository
     * @param StructureFileRepository $structureFileRepository
     * @param StructureRepository $structureRepository
     * @param MenuRepository $menuRepository
     * @param EntityManager $entityManager
     * @param LocaleRepository $localeRepository
     * @param File $file
     * @param FileStorage $fileStorage
     * @param Button|null $button
     */
    public function __construct(
        BaseFormFactory $baseForm,
        ButtonRepository $buttonRepository,
        ButtonTranslationRepository $buttonTranslationRepository,
        StructureFileRepository $structureFileRepository,
        StructureRepository $structureRepository,
        MenuRepository $menuRepository,
        EntityManager $entityManager,
        LocaleRepository $localeRepository,
        File $file,
        FileStorage $fileStorage,
        Button $button = null
    )
    {
        $this->baseFormFactory = $baseForm;
        $this->buttonRepository = $buttonRepository;
        $this->localeRepository = $localeRepository;
        $this->menuRepository = $menuRepository;
        $this->structureFileRepository = $structureFileRepository;
        $this->buttonTranslationRepository = $buttonTranslationRepository;
        $this->structureRepository = $structureRepository;
        $this->fileStorage = $fileStorage;
        $this->entityManager = $entityManager;
        $this->file = $file;
        $this->button = $button;

        $defaultValues = [];
        if ($this->button)
        {
            $defaultValues['identifier'] = $this->button->getIdentifier();
            $defaultValues['menu'] = ($this->button->getMenu() ? $this->button->getMenu()->getId() : null);
            $defaultValues['url'] = $this->button->getUrl();
            $defaultValues['isOpenInNewTab'] = $this->button->isOpenInNewTab();
            $defaultValues['picture'] = ($this->button->getPicture() ? $this->button->getPicture()->getId() : null);

            foreach ($this->button->getTranslations() AS $translation)
            {
                $defaultValues[$translation->getLocale()->getLanguageCode()]['buttonTitle'] = $translation->getButtonTitle();
                $defaultValues[$translation->getLocale()->getLanguageCode()]['title'] = $translation->getTitle();
            }
        }
        else{
            $defaultValues['isOpenInNewTab'] = false;
        }

        $this['form']->setDefaults($defaultValues);
    }

    /**
     * @return Form
     */
    protected function createComponentForm(): Form
    {
        $form = $this->baseFormFactory->create();

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            $container = $form->addContainer($activeLocale->getLanguageCode());

            $container->addText('title')
                ->setRequired('Please enter title.')
                ->addRule(Form::MAX_LENGTH, 'Title is too long.', 255);

            $container->addText('buttonTitle')
                ->setRequired('Please enter button Title.')
                ->addRule(Form::MAX_LENGTH, 'button Title is too long.', 2000);
        }

        $form->addText('picture');
        $form->addUpload('file');

        $menuItems = [null => '-- External URL --'];
        foreach ($this->menuRepository->getAll() AS $menu)
        {
            $menuItems[$menu->getId()] = $menu->getIdentifier();
        }

        $form->addSelect('menu', null, $menuItems);

        $form->addText('identifier');
        $form->addText('url');

        $form->addCheckBox('isOpenInNewTab');

        $form->addSubmit('send');

        $form->onValidate[] = [$this, 'editFormValidate'];
        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editFormValidate(Form $form): void
    {
        $values = $form->getValues();

        if (!$this->buttonRepository->isIdentifierFree($values->identifier, $this->button))
        {
            $form->addError('Identifier with this name already exists!');
        }
    }

    /**
     * @param Form $form
     * @throws \Exception
     */
    public function editFormSucceeded(Form $form): void
    {
        $values = $form->getValues();

        if ($values->picture) {
            $picture = $this->structureFileRepository->getOneById($values->picture);
        } else {
            $picture = null;
        }

        /** @var FileUpload $file */
        $file = $values->file;
        if ($file->isOk()) {
            $structureName = 'CallToActionButton';
            if (!$structure = $this->structureRepository->getOneByName($structureName)) {
                $structure = new Structure($structureName);
                $this->entityManager->persist($structure);
                $this->entityManager->flush();
            }
            $picture = $this->fileStorage->processFile($file, $structure);
        }

        $menu = ($values->menu ? $this->menuRepository->getOneById($values->menu) : null);
        $url = ($values->url ? $values->url : null);

        if ($this->button)
        {
            $button = $this->button;
            $button->setIdentifier($values->identifier);
            $button->setUrl($url);
            $button->setIsOpenInNewTab($values->isOpenInNewTab);
            $button->setMenu($menu);
            $button->setPicture($picture);
        }
        else
        {
            $button = new Button($values->identifier, $menu, $values->isOpenInNewTab, $url, $picture);
        }

        $this->entityManager->persist($button);

        $this->entityManager->flush();

        foreach ($this->localeRepository->getActive() AS $activeLocale) {
            if ($buttonTranslation = $this->buttonTranslationRepository->getTranslation($button, $activeLocale))
            {
                $buttonTranslation->setTitle($values->{$activeLocale->getLanguageCode()}->title);
                $buttonTranslation->setButtonTitle($values->{$activeLocale->getLanguageCode()}->buttonTitle);
            }
            else
            {
                $buttonTranslation = new ButtonTranslation(
                    $button,
                    $activeLocale,
                    $values->{$activeLocale->getLanguageCode()}->title,
                    $values->{$activeLocale->getLanguageCode()}->buttonTitle
                );
            }

            $this->entityManager->persist($buttonTranslation);
        }

        $this->entityManager->flush();

        $this->onSuccess($button);
    }

    public function render(): void
    {
        $template = $this->template;
        $template->activeLocales = $this->localeRepository->getActive();
        $template->fileSelectorPath = $this->file->getFileSelectorPath();
        $template->setFile(__DIR__ . '/ButtonForm.latte');
        $template->render();
    }
}