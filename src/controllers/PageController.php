<?php
require_once "./src/controllers/PageFactory.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";
// ToDo:
// getRequest
class PageController extends BaseController
{

        use tErrorMessageCollector;
        private array $request;
        private array $response;
        private ?BasePage $response_page = NULL;

        public function handleRequest() : void
        {
                $this->getRequest();
                $this->validateRequest();
                $this->showResponse();

        }

        protected function getRequest()
        {
                $posted = parent::$posted;
                $this->request = [
                        'posted' => $posted,
                        'page' => Utils::getRequestVar('page', $posted, $posted ? '' : 'home')

                ];
        }
        protected function validateRequest()
        {
                // validateRequest
                // new ValidateRequestFactory();
                $this->response = $this->request;
                ($this->request['posted']) ? $this->handlePostRequest() : $this->handleGetRequest();
        }

        protected function handlePostRequest()
        {
                $this -> updateResponse();
                $PageFactory = new PageFactory($this->response);
                $this->response_page = $PageFactory->show();
        }

        protected function handleGetRequest()
        {
                $this -> updateResponse();
                $PageFactory = new PageFactory($this->response);
                $this->response_page = $PageFactory->show();

        }

        public function showResponse()
        {
                // if response page is not null -> show page
                if (!is_null($this->response_page)) {
                        $this->response_page->show();
                }
        }

        protected function updateResponse(){
                $this->response['isLoggedIn'] = false; // from session variable
        }
}
