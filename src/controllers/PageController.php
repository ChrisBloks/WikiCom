<?php
require_once "./src/controllers/PageFactory.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";
// ToDo:
// getRequest
class PageController
{

        use tErrorMessageCollector;
        private array $request;
        private array $response;


        
        private function getRequestVar(string $key, bool $frompost, $default = '', bool $asnumber = false)
        {
                if ($asnumber) {
                        $result = filter_input(
                                $frompost ? INPUT_POST : INPUT_GET,
                                $key,
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                        );
                        return ($result === false || $result === null) ? $default : $result;
                }

                $raw = filter_input($frompost ? INPUT_POST : INPUT_GET, $key, FILTER_UNSAFE_RAW);

                if ($raw === false || $raw === null) {
                        return $default;
                }
                return htmlspecialchars(strip_tags(trim($raw)), ENT_QUOTES, 'UTF-8');
        }




        // validateRequest
        // new ValidateRequestFactory();



        // temporary page navigator
        public function showResponse()
        {
                $posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
                $this->response = [
                        'posted' => $posted,
                        'page' => $this->getRequestVar('page', $posted, $posted ? '' : 'home')

                ];
                // set page to be displayed to NULL
                $response_page = NULL;


                $PageFactory = new PageFactory($this->response['page'], true);
                $response_page = $PageFactory->show();


                // if response page is not null -> show page
                if (!is_null($response_page)) {
                        $response_page->show();
                }
        }
}
