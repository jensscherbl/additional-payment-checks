<?php
namespace Smaex\AdditionalPaymentChecks\Test\Unit\Plugin\Payment\Model\Checks\SpecificationFactory;

use Magento\Payment\Model\Checks\SpecificationFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Smaex\AdditionalPaymentChecks\Plugin\Payment\Model\Checks\SpecificationFactory\AdditionalChecks;

/**
 * @covers \Smaex\AdditionalPaymentChecks\Plugin\Payment\Model\Checks\SpecificationFactory\AdditionalChecks
 */
class AdditionalChecksTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockObject|SpecificationFactory
     */
    private $mockSpecificationFactory;

    /**
     * @param array $checksOriginal
     * @param array $checksAdditional
     * @param array $checksComposite
     *
     * @return void
     *
     * @dataProvider provideTestBeforeCreate
     */
    public function testBeforeCreate(array $checksOriginal, array $checksAdditional, array $checksComposite): void
    {
        $instance = new AdditionalChecks($checksAdditional);

        $this->assertSame(
            [
                $checksComposite
            ],
            $instance->beforeCreate($this->mockSpecificationFactory, $checksOriginal)
        );
    }

    /**
     * @return array
     */
    public function provideTestBeforeCreate(): array
    {
        return [
            [[                  ], [                    ], [                                      ]],
            [[ 'check_original' ], [                    ], [ 'check_original'                     ]],
            [[                  ], [ 'check_additional' ], [                   'check_additional' ]],
            [[ 'check_original' ], [ 'check_additional' ], [ 'check_original', 'check_additional' ]]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockSpecificationFactory = $this->createMock(SpecificationFactory::class);
    }
}
