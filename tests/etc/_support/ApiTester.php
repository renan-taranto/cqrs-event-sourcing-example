<?php
namespace Taranto\ListMaker\Tests;

use Behat\Gherkin\Node\PyStringNode;
use Codeception\Util\HttpCode;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * @Given I send a POST request to :url
     *
     * @param $url
     */
    public function iSendAPOSTRequestTo($url)
    {
        $this->sendPOST($url);
    }

    /**
     * @Given I send a POST request to :url with body: :body
     *
     * @param string $url
     * @param PyStringNode $body
     */
    public function iSendAPOSTRequestToWithBody(string $url, PyStringNode $body): void
    {
        $this->sendPOST($url, $body);
    }

    /**
     * @Given I send a GET request to :url
     *
     * @param $url
     */
    public function iSendAGETRequestTo($url)
    {
        $this->sendGET($url);
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        $this->assertEmpty($this->grabResponse());
    }

    /**
     * @Then the response status code should be :httpCode
     *
     * @param int $httpCode
     */
    public function theResponseStatusCodeShouldBe(int $httpCode): void
    {
        $this->seeResponseCodeIs($httpCode);
    }

    /**
     * @Then the response should be: :body
     *
     * @param PyStringNode $body
     */
    public function theResponseShouldBe(PyStringNode $body): void
    {
        $this->assertEquals(json_decode($body->getRaw()), json_decode($this->grabResponse()));
    }
}
