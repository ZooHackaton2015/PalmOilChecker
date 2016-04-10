<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;


class FormFactory extends Nette\Object
{

	/**
	 * @return Form
	 */
	public function create()
	{
        $form = new Form;
        $renderer = $form->getRenderer();
        //dump($renderer->wrappers);exit;
        $renderer->wrappers['control']['.text'] = 'text form-control';
        //$renderer->wrappers['label'][''] = 'control-label';
		return $form;
	}

}
