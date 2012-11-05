<?php

class Tx_MeExtsearch_Controller_CommandController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * commandRepository
	 *
	 * @var Tx_MeExtsearch_Domain_Repository_CommandRepository
	 */
	protected $commandRepository;

	/**
	 * injectCommandRepository
	 *
	 * @param Tx_MeExtsearch_Domain_Repository_CommandRepository $commandRepository
	 * @return void
	 */
	public function injectCommandRepository(Tx_MeExtsearch_Domain_Repository_CommandRepository $commandRepository) {
		$this->commandRepository = $commandRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function deleteAction() {
            
            // Aufruf von scheduler
            // query ausführen -> rückgabe
            
            
		$categories = $this->commandRepository->findAll();
		$this->view->assign('categories', $categories);
	}
}
?>