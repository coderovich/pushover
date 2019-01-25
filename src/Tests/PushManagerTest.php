<?php

namespace coderovich\pushover\Tests;

use coderovich\pushover\PushManager;
use coderovich\pushover\Model\Push;
use Nyholm\Psr7\Response;

/**
 * PushManager test class.
 *
 * @uses   PHPUnit_Framework_TestCase
 */
class PushManagerTest extends \PHPUnit_Framework_TestCase {
	protected $push;
	protected $pushWithMessage;
	protected $pushManager;
	protected $pusherResponse;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->push = new Push();
		$this->pushWithMessage = new Push();
		$this->pushManager = new PushManager('XXX', 'YYY');
		$this->pusherResponse = $this->getMock('Buzz\Message\Response');

		$this->pushWithMessage->setMessage('Test');
	}


	public function testData() {
		$pushManagerApiUrl = PushManager::API_URL;

		$this->assertTrue(isset($pushManagerApiUrl) && true === is_string($pushManagerApiUrl), 'There is no PushManager API URL constant');
	}

	/**
	 * @expectedException coderovich\pushover\Exception\InvalidMessageException
	 */
	public function testPushWithoutMessage() {
		$pushResult = $this->pushManager->push($this->push);
	}

	/**
	 * @expectedException coderovich\pushover\Exception\AuthenticationException
	 */
	public function testWebServiceCommunication() {
		$pushResult = $this->pushManager->push($this->pushWithMessage);
	}

	/**
	 * @covers PushManager::_getResponseObj
	 * @expectedException coderovich\pushover\Exception\AuthenticationException
	 */
	public function testResponseWithInvalidUser() {
		$this->pusherResponse->expects($this->once())
			->method('getContent')
			->will($this->returnValue('{"user":"invalid","status":0}'));

		$reflectionOfPushManager = new \ReflectionClass('coderovich\pushover\PushManager');
		$method = $reflectionOfPushManager->getMethod('_getResponseObj');
		$method->setAccessible(true);

		$pushManagerResponse = $method->invokeArgs($this->pushManager, array($this->pusherResponse));
	}

	/**
	 * @covers PushManager::_getResponseObj
	 * @expectedException coderovich\pushover\Exception\AuthenticationException
	 */
	public function testResponseWithInvalidToken() {
		$this->pusherResponse->expects($this->once())
			->method('getContent')
			->will($this->returnValue('{"token":"invalid","status":0}'));

		$reflectionOfPushManager = new \ReflectionClass('coderovich\pushover\PushManager');
		$method = $reflectionOfPushManager->getMethod('_getResponseObj');
		$method->setAccessible(true);

		$pushManagerResponse = $method->invokeArgs($this->pushManager, array($this->pusherResponse));
	}
}