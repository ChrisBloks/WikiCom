<?php
require_once "./src/factories/PageFactory.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";


/**
 * PageController class for handling non-AJAX requests.
 * @uses tErrorMessageCollector
 * @var bool $posted true if the page was requested through a POST request, otherwise false.
 * @var array $request Contains boolean 'posted' and string 'page'.
 * @var array $response Becomes equal to $request when method validateRequest() is reached.
 * @var BasePage $response_page page produced by the PageController.
 */
class PageController implements iController
{
    use tErrorMessageCollector;
    private bool $posted;
    private array $request;
    private ?array $response;
    private ?BasePage $response_page = NULL;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->posted = ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * Calls the get, validate and show functions in sequence.
     * @return void
     */
    public function handleRequest(): void
    {
        $this->getRequest();
        $this->validateRequest();
        $this->showResponse();
    }

    /**
     * Extract the specified page from the request.
     * @return void
     */
    public function getRequest(): void
    {
        $this->request = [
            'posted' => $this->posted,
            'page' => Utils::getRequestVar(
                key: 'page',
                frompost: $this->posted,
                default: ($this->posted ? '' : 'home'
                )
            ),
            'isLoggedIn' => isset($_SESSION['userID'])];
    }


    /**
     * Call the relevant validator based on the current request. Should set $this->response_page before terminating
     * @return void
     */
    protected function validateRequest(): void
    {
        // Get correct handler
        $requestHandler =  ($this->request['posted'] ? new PostRequestHandler(): new GetRequestHandler());
        
        // Validate requets and retrieve page object
        $this->response = $requestHandler->handleRequest($this->request);


        // Sanity check - Christian
        // Should be last line of validateRequest
    }
    /**
     * If page generation was succesful, call its show function.
     * @return void
     */
    public function showResponse(): void
    {
        $PageFactory = new PageFactory($this->response);
        $this->response_page = $PageFactory->show();
        // if response page is not null -> show page
        if (!is_null($this->response_page)) {
            $this->response_page->show();
        }
    }



}
