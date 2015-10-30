<?php

namespace App\Controllers\Admin;

class IndexController extends ControllerBase
{

	protected function initialize()
	{
        $this->tag->setTitle('Dashboard');
        parent::initialize();
	}

    public function indexAction()
    {
    	echo '[' . __METHOD__ . ']';
    }
}