<?php
namespace Smaex\AdditionalPaymentChecks\Plugin;

use Magento\Payment\Model\Checks\SpecificationFactory;

/**
 * Whitelists additional payment checks when creating a new composite check.
 */
class WhitelistAdditionalChecks
{
    /**
     * @var array
     */
    private $additionalChecks;

    /**
     * Constructor.
     *
     * @param array $additionalChecks
     *
     * @codeCoverageIgnore
     */
    public function __construct(array $additionalChecks = [])
    {
        $this->additionalChecks = $additionalChecks;
    }

    /**
     * @param SpecificationFactory $subject
     * @param array                $checks
     *
     * @return array
     */
    public function beforeCreate(SpecificationFactory $subject, array $checks): array
    {
        $checks = array_merge(
            $checks,
            $this->additionalChecks
        );
        return [ $checks ];
    }
}
