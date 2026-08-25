<?php
require_once "./src/controllers/factories/PageFactory.php";
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
            )
        ];
    }


    /**
     * Call the relevant validator based on the current request.
     * @return void
     */
    protected function validateRequest()
    {
        // validateRequest
        // new ValidateRequestFactory();
        $this->response = $this->request;
        $validation = new ValidateRequest($this->response);
        $this->response = $validation ->show();

        ($this->request['posted']) ? $this->handlePostRequest() : $this->handleGetRequest();
    }

    /**
     * To Be Removed
     *
     * @return void
     */
    protected function handlePostRequest()
    {
        $this->checkLogin($this->response);
        $this->updateResponse();
        $PageFactory = new PageFactory($this->response);
        $this->response_page = $PageFactory->show();
    }

    /**
     * To Be Removed
     *
     * @return void
     */
    protected function handleGetRequest()
    {
        $this->updateResponse();
        $PageFactory = new PageFactory($this->response);
        $this->response_page = $PageFactory->show();
    }


    /**
     * If page generation was succesful, call its show function.
     * @return void
     */
    public function showResponse()
    {
        // if response page is not null -> show page
        if (!is_null($this->response_page)) {
            $this->response_page->show();
        }
    }

    protected function updateResponse()
    {
        $this->response['isLoggedIn'] = Utils::getSesVar('isLoggedIn', false); // from session variable
    }

    protected function checkLogin(&$response)
    {
        $response['email'] = Utils::getRequestVar('email', $response['posted']);
        $response['password'] = Utils::getRequestVar('password', $response['posted']);
        $userinfo = ModelSelector::getUserInfoModel()->fetchUserInfoByEmail($response['email']);
        if (!empty($userinfo['password']) and ($response['password'] === $userinfo['password'])) {
            $response['page'] = 'home';
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['name'] = $userinfo['name'];
            $_SESSION['userID'] = $userinfo['id'];
        }
    }
}
