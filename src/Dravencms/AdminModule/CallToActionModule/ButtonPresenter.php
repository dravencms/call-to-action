<?php declare(strict_types = 1);


namespace Dravencms\AdminModule\CallToActionModule;

use Dravencms\Flash;
use Dravencms\AdminModule\Components\CallToAction\ButtonForm\ButtonFormFactory;
use Dravencms\AdminModule\Components\CallToAction\ButtonForm\ButtonForm;
use Dravencms\AdminModule\Components\CallToAction\ButtonGrid\ButtonGridFactory;
use Dravencms\AdminModule\Components\CallToAction\ButtonGrid\ButtonGrid;
use Dravencms\AdminModule\SecuredPresenter;
use Dravencms\Model\CallToAction\Entities\Button;
use Dravencms\Model\CallToAction\Repository\ButtonRepository;


/**
 * Description of FeaturePresenter
 *
 * @author Adam Schubert
 */
class ButtonPresenter extends SecuredPresenter
{

    /** @var ButtonRepository @inject */
    public $buttonRepository;

    /** @var ButtonGridFactory @inject */
    public $buttonGridFactory;

    /** @var ButtonFormFactory @inject */
    public $buttonFormFactory;

    /** @var Button|null */
    private $button = null;

    /**
     * @isAllowed(callToAction,edit)
     */
    public function actionDefault(): void
    {
        $this->template->h1 = 'Call to action buttons';
    }

    /**
     * @isAllowed(callToAction,edit)
     * @param $id
     * @throws \Nette\Application\BadRequestException
     */
    public function actionEdit(int $id = null): void
    {
        if ($id) {
            $button = $this->buttonRepository->getOneById($id);

            if (!$button) {
                $this->error();
            }

            $this->button = $button;

            $this->template->h1 = sprintf('Edit button „%s“', $button->getIdentifier());
        } else {
            $this->template->h1 = 'New call to action button';
        }
    }

    /**
     * @return ButtonForm
     */
    protected function createComponentButtonForm(): ButtonForm
    {
        $control = $this->buttonFormFactory->create($this->button);
        $control->onSuccess[] = function(){
            $this->flashMessage('Button has been successfully saved', Flash::SUCCESS);
            $this->redirect('Button:');
        };
        return $control;
    }

    /**
     * @return ButtonGrid
     */
    public function createComponentButtonGrid(): ButtonGrid
    {
        $control = $this->buttonGridFactory->create();
        $control->onDelete[] = function()
        {
            $this->flashMessage('Button has been successfully deleted', Flash::SUCCESS);
            $this->redirect('Button:');
        };
        return $control;
    }
}