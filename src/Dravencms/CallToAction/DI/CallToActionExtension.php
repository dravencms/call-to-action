<?php declare(strict_types = 1);

namespace Dravencms\CallToAction\DI;

use Contributte\Translation\DI\TranslationProviderInterface;
use Nette\DI\CompilerExtension;
use Dravencms\Structure\DI\StructureExtension;
use Dravencms\CallToAction\CallToAction;

/**
 * Class CallToActionExtension
 * @package Dravencms\CallToAction\DI
 */
class CallToActionExtension extends CompilerExtension implements TranslationProviderInterface
{
    public function getTranslationResources(): array
    {
        return [__DIR__.'/../lang'];
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();


        $builder->addDefinition($this->prefix('callToAction'))
            ->setFactory(CallToAction::class);

        $this->loadComponents();
        if (class_exists(StructureExtension::class)) {
            $this->loadCmsComponents();
            $this->loadCmsModels();
        }
        $this->loadModels();
        $this->loadConsole();
    }
    
    protected function loadComponents(): void
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/components.neon') as $i => $command) {
            $cli = $builder->addFactoryDefinition($this->prefix('components.' . $i));
            if (is_string($command)) {
                $cli->setImplement($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }

    protected function loadModels(): void
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/models.neon') as $i => $command) {
            $cli = $builder->addDefinition($this->prefix('models.' . $i));
            if (is_string($command)) {
                $cli->setFactory($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }

    protected function loadConsole(): void
    {
        $builder = $this->getContainerBuilder();

        foreach ($this->loadFromFile(__DIR__ . '/console.neon') as $i => $command) {
            $cli = $builder->addDefinition($this->prefix('cli.' . $i))
                ->setAutowired(false);

            if (is_string($command)) {
                $cli->setFactory($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }

    protected function loadCmsComponents(): void
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/cmsComponents.neon') as $i => $command) {
            $cli = $builder->addFactoryDefinition($this->prefix('cmsComponent.' . $i))
                ->addTag(StructureExtension::TAG_COMPONENT);
            if (is_string($command)) {
                $cli->setImplement($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }

    protected function loadCmsModels(): void
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/cmsModels.neon') as $i => $command) {
            $cli = $builder->addDefinition($this->prefix('cmsModels.' . $i));
            if (is_string($command)) {
                $cli->setFactory($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }
}
