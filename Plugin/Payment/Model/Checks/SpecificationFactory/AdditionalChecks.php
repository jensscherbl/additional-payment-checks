<?php
namespace Smaex\AdditionalPaymentChecks\Plugin\Payment\Model\Checks\SpecificationFactory;

use Magento\Payment\Model\Checks\SpecificationFactory;

/**
 * Adds additional checks to payment methods.
 */
class AdditionalChecks
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
