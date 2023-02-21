<?php declare(strict_types = 1);
/**
 * Copyright (C) 2023 Adam Schubert <adam.schubert@sg1-game.net>.
 */

namespace Dravencms\AdminModule\Components\CallToAction\ButtonGrid;

/**
 * Interface ButtonGridFactory
 * @package Dravencms\AdminModule\Components\CallToAction\ButtonGrid
 */
interface ButtonGridFactory
{
    /**
     * @return ButtonGrid
     */
    public function create(): ButtonGrid;
}