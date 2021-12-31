<?php

namespace WireMock\Matching;

use WireMock\Client\BasicCredentials;
use WireMock\Client\MultipartValuePattern;
use WireMock\Client\ValueMatchingStrategy;

class RequestPattern
{
    /** @var string */
    private $_method;
    /** @var UrlMatchingStrategy  */
    private $_urlMatchingStrategy;
    /** @var ValueMatchingStrategy[] */
    private $_headers;
    /** @var ValueMatchingStrategy[] */
    private $_cookies;
    /** @var ValueMatchingStrategy[] */
    private $_queryParameters;
    /** @var ValueMatchingStrategy[] */
    private $_bodyPatterns;
    /** @var null|MultipartValuePattern[] */
    private $_multipartPatterns;
    /** @var BasicCredentials */
    private $_basicCredentials;
    /** @var CustomMatcherDefinition */
    private $_customMatcherDefinition;
    /** @var ValueMatchingStrategy */
    private $_hostPattern;

    /**
     * @param string $method
     * @param UrlMatchingStrategy $urlMatchingStrategy
     * @param ValueMatchingStrategy[] $headers
     * @param ValueMatchingStrategy[] $cookies
     * @param ValueMatchingStrategy[] $bodyPatterns
     * @param ValueMatchingStrategy[] $multipartPatterns
     * @param ValueMatchingStrategy[] $queryParameters
     * @param BasicCredentials $basicCredentials
     * @param CustomMatcherDefinition $customMatcherDefinition
     * @param ValueMatchingStrategy $hostPattern
     */
    public function __construct(
        $method,
        $urlMatchingStrategy,
        $headers = null,
        $cookies = null,
        $bodyPatterns = null,
        $multipartPatterns = null,
        $queryParameters = null,
        $basicCredentials = null,
        $customMatcherDefinition = null,
        $hostPattern = null
    ) {
        $this->_method = $method;
        $this->_urlMatchingStrategy = $urlMatchingStrategy;
        $this->_headers = $headers;
        $this->_cookies = $cookies;
        $this->_bodyPatterns = $bodyPatterns;
        $this->_queryParameters = $queryParameters;
        $this->_basicCredentials = $basicCredentials;
        $this->_multipartPatterns = $multipartPatterns;
        $this->_customMatcherDefinition = $customMatcherDefinition;
        $this->_hostPattern = $hostPattern;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return UrlMatchingStrategy
     */
    public function getUrlMatchingStrategy()
    {
        return $this->_urlMatchingStrategy;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->_queryParameters;
    }

    /**
     * @return array
     */
    public function getBodyPatterns()
    {
        return $this->_bodyPatterns;
    }

    /**
     * @return array
     */
    public function getMultipartPatterns()
    {
        return $this->_multipartPatterns;
    }

    /**
     * @return BasicCredentials
     */
    public function getBasicCredentials()
    {
        return $this->_basicCredentials;
    }

    /**
     * @return CustomMatcherDefinition
     */
    public function getCustomMatcherDefinition()
    {
        return $this->_customMatcherDefinition;
    }

    public function toArray()
    {
        $array = array();
        if ($this->_method) {
            $array['method'] = $this->_method;
        }
        if ($this->_urlMatchingStrategy) {
            $array = array_merge($array, $this->_urlMatchingStrategy->toArray());
        }
        if ($this->_headers) {
            $array['headers'] = $this->_headers;
        }
        if ($this->_cookies) {
            $array['cookies'] = $this->_cookies;
        }
        if ($this->_queryParameters) {
            $array['queryParameters'] = $this->_queryParameters;
        }
        if ($this->_bodyPatterns) {
            $array['bodyPatterns'] = $this->_bodyPatterns;
        }
        if ($this->_multipartPatterns) {
            $array['multipartPatterns'] = $this->_multipartPatterns;
        }
        if ($this->_basicCredentials) {
            $array['basicAuthCredentials'] = $this->_basicCredentials->toArray();
        }
        if ($this->_customMatcherDefinition) {
            $array['customMatcher'] = $this->_customMatcherDefinition->toArray();
        }
        if ($this->_hostPattern) {
            $array['host'] = $this->_hostPattern->toArray();
        }
        return $array;
    }

    /**
     * @param array $array
     * @return RequestPattern
     * @throws \Exception
     */
    public static function fromArray(array $array)
    {
        return new RequestPattern(
            $array['method'],
            UrlMatchingStrategy::fromArray($array),
            isset($array['headers']) ? $array['headers'] : null,
            isset($array['cookies']) ? $array['cookies'] : null,
            isset($array['bodyPatterns']) ? $array['bodyPatterns'] : null,
            isset($array['multipartPatterns']) ? $array['multipartPatterns'] : null,
            isset($array['queryParameters']) ? $array['queryParameters'] : null,
            isset($array['basicAuthCredentials']) ? BasicCredentials::fromArray($array['basicAuthCredentials']) : null,
            isset($array['customMatcher']) ? CustomMatcherDefinition::fromArray($array['customMatcher']) : null,
            isset($array['host']) ? ValueMatchingStrategy::fromArray($array['host']) : null
        );
    }
}
