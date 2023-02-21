<?php declare(strict_types = 1);

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\AdminModule\Components\CallToAction\ButtonForm;



use Dravencms\Model\CallToAction\Entities\Button;

interface ButtonFormFactory
{
    /**
     * @param Button|null $button
     * @return ButtonForm
     */
    public function create(Button $button = null): ButtonForm;
}