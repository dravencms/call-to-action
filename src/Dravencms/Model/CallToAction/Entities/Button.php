<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Model\CallToAction\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Dravencms\Model\File\Entities\StructureFile;
use Dravencms\Model\Structure\Entities\Menu;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Dravencms\Database\Attributes\Identifier;
use Nette;

/**
 * Class Button
 * @ORM\Entity
 * @ORM\Table(name="callToActionButton")
 */
class Button
{
    use Nette\SmartObject;
    use Identifier;
    use TimestampableEntity;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false,unique=true)
     */
    private $identifier;

    /**
     * @var StructureFile
     * @ORM\ManyToOne(targetEntity="\Dravencms\Model\File\Entities\StructureFile")
     * @ORM\JoinColumn(name="structure_file_id", referencedColumnName="id")
     */
    private $picture;

    /**
     * @var ArrayCollection|ButtonTranslation[]
     * @ORM\OneToMany(targetEntity="ButtonTranslation", mappedBy="button",cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * @var Menu
     * @ORM\ManyToOne(targetEntity="Dravencms\Model\Structure\Entities\Menu")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=true)
     */
    private $menu;


    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isOpenInNewTab;

    /**
     * @var string
     * @ORM\Column(type="text",nullable=true)
     */
    private $url;

    /**
     * Button constructor.
     * @param $identifier
     * @param Menu|null $menu
     * @param bool $isOpenInNewTab
     * @param null $url
     * @param StructureFile|null $picture
     */
    public function __construct(string $identifier, Menu $menu = null, bool $isOpenInNewTab = false, string $url = null, StructureFile $picture = null)
    {
        $this->identifier = $identifier;
        $this->menu = $menu;
        $this->picture = $picture;
        $this->isOpenInNewTab = $isOpenInNewTab;
        $this->url = $url;
        $this->translations = new ArrayCollection();
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @param StructureFile $picture
     */
    public function setPicture(StructureFile $picture = null): void
    {
        $this->picture = $picture;
    }

    /**
     * @param Menu $menu
     */
    public function setMenu(Menu $menu = null): void
    {
        $this->menu = $menu;
    }

    /**
     * @param bool $isOpenInNewTab
     */
    public function setIsOpenInNewTab(bool $isOpenInNewTab): void
    {
        $this->isOpenInNewTab = $isOpenInNewTab;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(string $url = null): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return Menu
     */
    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    /**
     * @return StructureFile
     */
    public function getPicture(): ?StructureFile
    {
        return $this->picture;
    }

    /**
     * @return ArrayCollection|ButtonTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return bool
     */
    public function isOpenInNewTab(): bool
    {
        return $this->isOpenInNewTab;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}