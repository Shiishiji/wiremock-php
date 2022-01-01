<?php

namespace WireMock\Client;

use WireMock\HamcrestTestCase;

class ResponseDefinitionBuilderTest extends HamcrestTestCase
{
    public function testDefault200StatusIsAvailableInArray()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('status', 200));
    }

    public function testSpecifiedStatusIsAvailableInArray()
    {
        // given
        $status = 403;
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->withStatus($status);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('status', $status));
    }

    public function testStatusMessageIsAvailableInArrayIfSet()
    {
        // given
        $statusMessage = "hello there";
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->withStatusMessage($statusMessage);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('statusMessage', $statusMessage));
    }

    public function testStatusMessageIsNotAvailableInArrayIfNotSet()
    {
        // given
        $statusMessage = "hello there";
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, not(hasKey('statusMessage')));
    }

    public function testBodyIsAvailableInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $body = '<h1>Some body!</h1>';
        $responseDefinitionBuilder->withBody($body);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('body', $body));
    }

    public function testBodyIsNotAvailableInArrayIfNotSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, not(hasKey('body')));
    }

    public function testBodyFileIsAvailableInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $bodyFile = 'someFile';
        $responseDefinitionBuilder->withBodyFile($bodyFile);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('bodyFileName', $bodyFile));
    }

    public function testBodyFileIsNotAvailableInArrayIfNotSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, not(hasKey('bodyFileName')));
    }

    public function testBase64BodyIsAvailableInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $bodyData = 'data';
        $responseDefinitionBuilder->withBodyData($bodyData);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        $base64 = base64_encode($bodyData);
        assertThat($responseDefArray, hasEntry('base64Body', $base64));
    }

    public function testHeaderIsAvailableInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->withHeader('foo1', 'bar1');
        $responseDefinitionBuilder->withHeader('foo2', 'bar2');

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('headers', array('foo1' => 'bar1', 'foo2' => 'bar2')));
    }

    public function testHeaderIsAvailableInArrayAsArrayIfSetMultipleTimes()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->withHeader('foo', 'bar1');
        $responseDefinitionBuilder->withHeader('foo', 'bar2');

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('headers', array('foo' => array('bar1', 'bar2'))));
    }

    public function testHeaderIsNotAvailableInArrayIfNotSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, not(hasKey('headers')));
    }

    public function testProxyBaseUrlIsAvailableIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->proxiedFrom('http://otherhost.com/approot');

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('proxyBaseUrl', 'http://otherhost.com/approot'));
    }

    public function testProxyAdditionalHeadersIsNotInArrayIfEmpty()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();

        // when
        $responseDefArray = $responseDefinitionBuilder->build()->toArray();

        // then
        assertThat($responseDefArray, not(hasKey('additionalProxyRequestHeaders')));
    }

    public function testProxiedBuilderRetainsMatchersAddedSoFar()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder = $responseDefinitionBuilder
            ->withStatus(404)
            ->withHeader('X-Header', 'four oh four')
            ->proxiedFrom('foo');

        // when
        $responseDefArray = $responseDefinitionBuilder->build()->toArray();

        // then
        assertThat($responseDefArray, hasEntry('status', 404));
        assertThat($responseDefArray, hasEntry('headers', array('X-Header' => 'four oh four')));
        assertThat($responseDefArray, hasEntry('proxyBaseUrl', 'foo'));
    }

    public function testProxyAdditionalHeadersIsInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder = $responseDefinitionBuilder
            ->proxiedFrom('foo')
            ->withAdditionalRequestHeader('X-Header', 'val');

        // when
        $responseDefArray = $responseDefinitionBuilder->build()->toArray();

        // then
        assertThat($responseDefArray, hasEntry('additionalProxyRequestHeaders', array('X-Header' => 'val')));
    }

    public function testFixedDelayMillisecondsIsInArrayIfSet()
    {
        // given
        $responseDefinitionBuilder = new ResponseDefinitionBuilder();
        $responseDefinitionBuilder->withFixedDelay(2000);

        // when
        $responseDefinition = $responseDefinitionBuilder->build();
        $responseDefArray = $responseDefinition->toArray();

        // then
        assertThat($responseDefArray, hasEntry('fixedDelayMilliseconds', 2000));
    }
}
