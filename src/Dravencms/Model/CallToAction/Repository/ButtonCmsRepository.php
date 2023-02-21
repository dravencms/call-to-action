<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\Model\CallToAction\Repository;

use Dravencms\Model\FileDownload\Entities\Download;
use Dravencms\Model\FileDownload\Repository\DownloadRepository;
use Nette;
use Dravencms\Structure\CmsActionOption;
use Dravencms\Structure\ICmsActionOption;
use Dravencms\Structure\ICmsComponentRepository;

class ButtonCmsRepository implements ICmsComponentRepository
{
    /** @var ButtonRepository */
    private $buttonRepository;

    /**
     * ButtonCmsRepository constructor.
     * @param ButtonRepository $buttonRepository
     */
    public function __construct(ButtonRepository $buttonRepository)
    {
        $this->buttonRepository = $buttonRepository;
    }

    /**
     * @param string $componentAction
     * @return ICmsActionOption[]
     */
    public function getActionOptions(string $componentAction)
    {
        switch ($componentAction)
        {
            case 'Bar':
                $return = [];
                /** @var Download $download */
                foreach ($this->buttonRepository->getAll() AS $download) {
                    $return[] = new CmsActionOption($download->getIdentifier(), ['id' => $download->getId()]);
                }
                break;

            default:
                return false;
                break;
        }

        return $return;
    }

    /**
     * @param string $componentAction
     * @param array $parameters
     * @return null|CmsActionOption
     */
    public function getActionOption(string $componentAction, array $parameters): ?CmsActionOption
    {
        $found = $this->buttonRepository->getOneByParameters($parameters);

        if ($found)
        {
            return new CmsActionOption($found->getIdentifier(), $parameters);
        }

        return null;
    }
}