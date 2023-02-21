<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Model\CallToAction\Entities;

use Doctrine\ORM\Mapping as ORM;
use Dravencms\Model\Locale\Entities\Locale;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Dravencms\Database\Attributes\Identifier;
use Nette;

/**
 * Class FeatureTranslation
 * @ORM\Entity
 * @ORM\Table(name="callToActionButtonTranslation")
 */
class ButtonTranslation
{
    use Nette\SmartObject;
    use Identifier;
    use TimestampableEntity;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $buttonTitle;

    /**
     * @var Button
     * @ORM\ManyToOne(targetEntity="Button", inversedBy="translations")
     * @ORM\JoinColumn(name="button_id", referencedColumnName="id")
     */
    private $button;

    /**
     * @var Locale
     * @ORM\ManyToOne(targetEntity="Dravencms\Model\Locale\Entities\Locale")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;

    /**
     * ButtonTranslation constructor.
     * @param Button $button
     * @param Locale $locale
     * @param $title
     * @param $buttonTitle
     */
    public function __construct(Button $button, Locale $locale, string $title, string $buttonTitle)
    {
        $this->title = $title;
        $this->buttonTitle = $buttonTitle;
        $this->button = $button;
        $this->locale = $locale;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $buttonTitle
     */
    public function setButtonTitle(string $buttonTitle): void
    {
        $this->buttonTitle = $buttonTitle;
    }

    /**
     * @param Button $button
     */
    public function setButton(Button $button): void
    {
        $this->button = $button;
    }

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getButtonTitle(): string
    {
        return $this->buttonTitle;
    }

    /**
     * @return Button
     */
    public function getButton(): Button
    {
        return $this->button;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }
}