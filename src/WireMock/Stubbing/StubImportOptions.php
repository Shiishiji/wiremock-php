<?php

namespace WireMock\Stubbing;

class StubImportOptions
{
    const OVERWRITE = 'OVERWRITE';
    const IGNORE = 'IGNORE';

    /** @var string */
    private $duplicatePolicy;
    /** @var boolean */
    private $deleteAllNotInImport;

    /**
     * StubImportOptions constructor.
     * @param string $_duplicatePolicy
     * @param bool $_deleteAllNotInImport
     */
    public function __construct($_duplicatePolicy, $_deleteAllNotInImport)
    {
        $this->duplicatePolicy = $_duplicatePolicy;
        $this->deleteAllNotInImport = $_deleteAllNotInImport;
    }
}