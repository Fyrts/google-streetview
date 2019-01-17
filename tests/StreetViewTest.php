<?php

namespace Defro\Google\StreetView\Tests;

use Defro\Google\StreetView\Exception\UnexpectedStatusException;
use Defro\Google\StreetView\Exception\UnexpectedValueException;
use GuzzleHttp\Client;
use Defro\Google\StreetView\Api;

class StreetViewTest extends TestCase
{
    /** @var \Defro\Google\StreetView\Api */
    protected $streetView;

    public function setUp()
    {
        parent::setUp();

        $client = new Client();

        $this->streetView = new Api($client);

        $apiKey = getenv('GOOGLE_API_KEY');

        if (!$apiKey) {
            $this->markTestSkipped('No Google API key was provided.');

            return;
        }

        $this->streetView->setApiKey($apiKey);
    }

    public function testGetMetadata()
    {
        $result = $this->streetView->getMetadata('Statue of Liberty National Monument');

        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('copyright', $result);
        $this->assertArrayHasKey('panoramaId', $result);
    }

    public function testGetMetadataStatusException()
    {
        $this->expectException(UnexpectedStatusException::class);
        $this->streetView->getMetadata('A place where I will got an error');
    }

    public function testGetImageUrlByLocation()
    {
        $result = $this->streetView->getImageUrlByLocation('Statue of Liberty National Monument');
        $this->assertStringStartsWith('https://', $result);
    }

    public function testGetImageUrlByLatitudeAndLongitude()
    {
        $result = $this->streetView->getImageUrlByLatitudeAndLongitude(40.70584913094, -74.035342633881);
        $this->assertStringStartsWith('https://', $result);
    }

    public function testGetImageUrlByPanoramaId()
    {
        $result = $this->streetView->getImageUrlByPanoramaId('Bc-tdEJFUCt21hqBjhY_NQ');
        $this->assertStringStartsWith('https://', $result);
    }

    public function testSetImageWidth()
    {
        $result = $this->streetView->setImageWidth(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetImageWidthException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setImageWidth(0);
    }

    public function testSetImageHeight()
    {
        $result = $this->streetView->setImageHeight(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetImageHeightException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setImageHeight(0);
    }

    public function testSetSource()
    {
        $result = $this->streetView->setSource(Api::SOURCE_DEFAULT);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetSourceException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setSource('unknownSource');
    }

    public function testSetHeading()
    {
        $result = $this->streetView->setHeading(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetHeadingFirstException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setHeading(-42);
    }

    public function testSetHeadingSecondException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setHeading(420);
    }

    public function testSetRadius()
    {
        $result = $this->streetView->setRadius(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetRadiusException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setRadius(-42);
    }

    public function testSetCameraPitch()
    {
        $result = $this->streetView->setCameraPitch(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetCameraPitchFirstException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setCameraPitch(-100);
    }

    public function testSetCameraPitchSecondException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setCameraPitch(100);
    }

    public function testSetCameraFov()
    {
        $result = $this->streetView->setCameraFov(42);
        $this->assertSame(get_class($result), Api::class);
    }

    public function testSetCameraFovException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->streetView->setCameraFov(150);
    }
}
