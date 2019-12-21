<?php

namespace App\Controllers;

use App\Freight\Freight;
use App\Models\ContactRecord;
use App\Models\Quotes;
use App\Models\Kpis;
use App\Models\Users;
use App\Models\DailySales;
use App\Models\Budgets;
use App\Models\QuoteVisits;
use App\Models\StickyNotes;
use App\Models\Orders;

class DashboardController extends ControllerBase
{
	public function initialize()
	{
        if ($this->session->has('auth-identity')) {
            $this->view->setTemplateBefore('private');
        }
        $this->tag->prependTitle('Dashboard');
        parent::initialize();
	}

    public function indexAction()
    {
        $config = include __DIR__ . "/../config/config.php";

        $tasks = new ContactRecord;
        $this->view->noHeader = true;

        $user = $this->session->get('auth-identity')['id'];

        $this->view->notes              = StickyNotes::current();
        $this->view->budget             = Budgets::current();
        $this->view->kpis               = Kpis::thisMonth();
        $this->view->users              = Users::listUsers();
        $this->view->monthsSales        = DailySales::sumMonth();
        $this->view->daySales           = DailySales::sumDay(date("Y-m-d"));
        $this->view->agentSales         = DailySales::getMonthByRep(date("Y-m-d"));
        $this->view->daySalesByAgent    = DailySales::getDayByRep(date("Y-m-d"));
        $this->view->quoteCount         = DailySales::countQuotesMonth(date("Y-m-d"));
        $this->view->quoteSum           = DailySales::sumQuotesMonth(date("Y-m-d"));
        $this->view->sales              = DailySales::dailySalesBetween(date("Y-m-01"), date("Y-m-t"));
        $this->view->tasks              = $tasks->getOverdue();

        $this->view->parser = new \cebe\markdown\Markdown();

        $this->assets->collection('header')
            ->addCss('//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css');

        $this->assets->collection('footer')
            ->addJs('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.2/Chart.bundle.min.js')
            ->addJs('js/dashboard/charts.js');

        $this->view->quotes = new Quotes();
    }

    public function createstickyAction()
    {
        if(!$this->request->isPost())
        {
            $this->_redirectBack();
        }

        $note = new StickyNotes();
        $note->title = $this->request->getPost('title');
        $note->note = $this->request->getPost('note');
        $note->expires = date("Y-m-d", strtotime($this->request->getPost('expires')));
        $note->type = $this->request->getPost('type');
        $success = $note->save();
        if ($success) {
			$this->response->redirect('dashboard');
			$this->view->disable;
		} else {
			$this->flash->error("Sorry, the note could not be saved");
			foreach ($note->getMessages() as $message) {
				$this->flash->error($message->getMessage());
			}
		}
    }

    public function deletenoteAction($id)
    {
        if(!$id)
        {
            $this->_redirectBack();
        }

        $note = StickyNotes::findFirstById($id);
        $success = $note->delete();
        if ($success) {
			$this->response->redirect('dashboard');
			$this->view->disable;
		} else {
			$this->flash->error("Sorry, the note could not be deleted");
			foreach ($note->getMessages() as $message) {
				$this->flash->error($message->getMessage());
			}
		}
    }

		public function despatchAction()
		{
			$config = include __DIR__ . "/../config/config.php";
			$this->view->noHeader = true;

	        $tasks = new ContactRecord;

			$this->view->orderLocations = Orders::countLocations();

	        $user = $this->session->get('auth-identity')['id'];
	        $this->view->notes              = StickyNotes::current();
	        $this->view->parser = new \cebe\markdown\Markdown();

		}
}
