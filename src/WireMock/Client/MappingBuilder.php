<?php

namespace WireMock\Client;

use WireMock\PostServe\PostServeAction;
use WireMock\PostServe\WebhookDefinition;
use WireMock\Stubbing\StubMapping;

class MappingBuilder
{
    /** @var string A string representation of a GUID  */
    private $_id;
    /** @var string */
    private $_name;
    /** @var RequestPatternBuilder */
    private $_requestPatternBuilder;
    /** @var ResponseDefinitionBuilder */
    private $_responseDefinitionBuilder;
    /** @var int */
    private $_priority;
    /** @var ScenarioMappingBuilder */
    private $_scenarioBuilder;
    /** @var array */
    private $_metadata;
    /** @var boolean */
    private $_isPersistent;
    /** @var PostServeAction[]|null */
    private $_postServeActions;

    public function __construct(RequestPatternBuilder $requestPatternBuilder)
    {
        $this->_requestPatternBuilder = $requestPatternBuilder;
        $this->_scenarioBuilder = new ScenarioMappingBuilder();
    }

    /**
     * @param string $id A string representation of a GUID
     * @return MappingBuilder
     */
    public function withId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return MappingBuilder
     */
    public function withName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @param ResponseDefinitionBuilder $responseDefinitionBuilder
     * @return MappingBuilder
     */
    public function willReturn(ResponseDefinitionBuilder $responseDefinitionBuilder)
    {
        $this->_responseDefinitionBuilder = $responseDefinitionBuilder;
        return $this;
    }

    /**
     * @param int $priority
     * @return MappingBuilder
     */
    public function atPriority($priority)
    {
        $this->_priority = $priority;
        return $this;
    }

    /**
     * @param string $headerName
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return MappingBuilder
     */
    public function withHeader($headerName, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_requestPatternBuilder->withHeader($headerName, $valueMatchingStrategy);
        return $this;
    }

    /**
     * @param string $name
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return $this
     */
    public function withQueryParam($name, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_requestPatternBuilder->withQueryParam($name, $valueMatchingStrategy);
        return $this;
    }

    /**
     * @param string $cookieName
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return MappingBuilder
     */
    public function withCookie($cookieName, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_requestPatternBuilder->withCookie($cookieName, $valueMatchingStrategy);
        return $this;
    }

    /**
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return MappingBuilder
     */
    public function withRequestBody(ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_requestPatternBuilder->withRequestBody($valueMatchingStrategy);
        return $this;
    }

    /**
     * @param MultipartValuePatternBuilder $multipartBuilder
     * @return MappingBuilder
     */
    public function withMultipartRequestBody($multipartBuilder)
    {
        $this->_requestPatternBuilder->withMultipartRequestBody($multipartBuilder->build());
        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     * @return MappingBuilder
     */
    public function withBasicAuth($username, $password)
    {
        $this->_requestPatternBuilder->withBasicAuth($username, $password);
        return $this;
    }

    /**
     * @param string $scenarioName
     * @return MappingBuilder
     */
    public function inScenario($scenarioName)
    {
        $this->_scenarioBuilder->withScenarioName($scenarioName);
        return $this;
    }

    /**
     * @param string $requiredScenarioState
     * @return MappingBuilder
     */
    public function whenScenarioStateIs($requiredScenarioState)
    {
        $this->_scenarioBuilder->withRequiredState($requiredScenarioState);
        return $this;
    }

    /**
     * @param string $newScenarioState
     * @return MappingBuilder
     */
    public function willSetStateTo($newScenarioState)
    {
        $this->_scenarioBuilder->withNewScenarioState($newScenarioState);
        return $this;
    }

    /**
     * @param array $metadata
     * @return MappingBuilder
     */
    public function withMetadata(array $metadata)
    {
        $this->_metadata = $metadata;
        return $this;
    }

    /**
     * @param string $matcherName
     * @param array $params
     * @return MappingBuilder
     */
    public function andMatching($matcherName, $params = array())
    {
        $this->_requestPatternBuilder->withCustomMatcher($matcherName, $params);
        return $this;
    }

    /**
     * @return MappingBuilder
     */
    public function persistent()
    {
        $this->_isPersistent = true;
        return $this;
    }

    /**
     * @param string $name Name of the post-serve action
     * @param WebhookDefinition $webhook
     * @return $this
     */
    public function withPostServeAction($name, WebhookDefinition $webhook)
    {
        if (!isset($this->_postServeActions)) {
            $this->_postServeActions = array();
        }
        $this->_postServeActions[] = new PostServeAction($name, $webhook);
        return $this;
    }

    /**
     * @return StubMapping
     * @throws \Exception
     */
    public function build()
    {
        $responseDefinition = $this->_responseDefinitionBuilder->build();
        return new StubMapping(
            $this->_requestPatternBuilder->build(),
            $responseDefinition,
            $this->_id,
            $this->_name,
            $this->_priority,
            $this->_scenarioBuilder->build(),
            $this->_metadata,
            $this->_isPersistent,
            $this->_postServeActions
        );
    }
}
