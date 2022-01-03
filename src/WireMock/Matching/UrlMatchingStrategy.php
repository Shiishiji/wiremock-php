<?php

namespace WireMock\Matching;

use WireMock\Serde\PostNormalizationAmenderInterface;

class UrlMatchingStrategy implements PostNormalizationAmenderInterface
{
    /** @var string */
    private $_matchingType;
    /** @var string */
    private $_matchingValue;

    /**
     * @param string $matchingType
     * @param string $matchingValue
     */
    public function __construct($matchingType, $matchingValue)
    {
        $this->_matchingType = $matchingType;
        $this->_matchingValue = $matchingValue;
    }

    /**
     * @return string
     */
    public function getMatchingType()
    {
        return $this->_matchingType;
    }

    /**
     * @return string
     */
    public function getMatchingValue()
    {
        return $this->_matchingValue;
    }

    public function toArray()
    {
        return array(
            $this->_matchingType => $this->_matchingValue,
        );
    }

    /**
     * @param array $array
     * @return UrlMatchingStrategy|null
     */
    public static function fromArray(array $array)
    {
        foreach (array('url', 'urlPattern', 'urlPath', 'urlPathPattern') as $type) {
            if (isset($array[$type])) {
                return new UrlMatchingStrategy($type, $array[$type]);
            }
        }
        return null;
    }

    public static function amendNormalisation(array $normalisedArray, $object): array
    {
        $matchingType = $normalisedArray['matchingType'];
        $matchingValue = $normalisedArray['matchingValue'];
        return [$matchingType => $matchingValue];
    }
}
