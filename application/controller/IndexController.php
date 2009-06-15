<?php

	class IndexController extends AbstractController {
		
		public function index() {
			$view = $this->getView('index');
			//ist der Nutzer bereits eingeloggt?
			$authenticated = $this->twitter_service->isAuthenticated();
			//ansonsten Link zum Authorisieren erstellen
			if (!$authenticated) {
				$view->authURL = $this->twitter_service->getAuthorizeURL();
			}
			$view->authenticated = $authenticated;
			$view->show();
		}
		
	}

?>