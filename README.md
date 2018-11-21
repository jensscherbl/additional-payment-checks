# Magento 2: Additional Payment Checks

Enables additional checks for payment methods in [Magento 2][1].

## Intro

Adding additional checks to payment methods – to decide if a certain payment method is applicable to a certain customer or not – is pretty easy and straight forward in Magento 2.

Magento [provides a rather simplistic interface][2] for custom payment method checks, and [uses a composite check][3] to process these individual checks. Adding a custom check is therefore just a matter of injecting it into Magento’s composite check via dependency injection.

```php
/**
 * Combines several checks with logic "AND" operation.
 *
 * Use this class to register own specifications.
 */
class Composite implements SpecificationInterface
{
    /**
     * Check whether payment method is applicable to quote
     */
    public function isApplicable(MethodInterface $paymentMethod, Quote $quote): bool
    {
        foreach ($this->list as $specification) {
            if (!$specification->isApplicable($paymentMethod, $quote)) {
                return false;
            }
        }
        return true;
    }
}
```

Well, at least in an ideal world. But since we’re talking about Magento here, it’s geting a bit more ~~messy~~ complex. Different checks might be necessary in different scenarios, so Magento [uses a factory][4] to instantiate its composite check dynamically with a varying list of checks in different places.

```php
/**
 * Creates complex specification.
 *
 * Use this class to register predefined list of specifications
 * that should be added to any complex specification.
 */
class SpecificationFactory
{
    /**
     * Creates new instances of payment method models
     */
    public function create(array $data): Composite
    {
        $specifications = array_intersect_key($this->mapping, array_flip((array)$data));
        return $this->compositeFactory->create(['list' => $specifications]);
    }
}
```

However, and in typical Magento fashion, this mechanism is only half baked. Magento [uses a class called MethodList][5] to retrieve applicable payment methods, and hardcodes the list of checks that are performed to figure out which payment methods to offer.

```php
/**
 * Methods List service class.
 */
class MethodList
{
    /**
     * Check payment method model
     */
    protected function _canUseMethod(MethodInterface $method, CartInterface $quote): bool
    {
        return $this->methodSpecificationFactory->create(
            [
                AbstractMethod::CHECK_USE_CHECKOUT,
                AbstractMethod::CHECK_USE_FOR_COUNTRY,
                AbstractMethod::CHECK_USE_FOR_CURRENCY,
                AbstractMethod::CHECK_ORDER_TOTAL_MIN_MAX,
            ]
        )->isApplicable($method, $quote);
    }
}
```

Fortunately, this extension provides a handy workaround.

## How to install

Simply require the extension via [Composer][6].

```sh
$ composer require smaex/additional-payment-checks ^1.0
```

Finally, enable the module via [Magento’s CLI][7].

```sh
$ magento module:enable Smaex_AdditionalPaymentChecks
```

## How to use

The extension plugs into the factory’s `create`-method and extends the list of checks that is passed to Magento’s composite check upon creation. All we have to do now, besides injecting our custom check into the factory as described above, is adding it to the plugin provided by this extension.

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <type name="Magento\Payment\Model\Checks\SpecificationFactory">
        <arguments>
            <argument name="mapping" xsi:type="array">
                <item name="acme_custom_payment_method_check" xsi:type="object">
                    Acme\Payment\Model\Checks\CustomPaymentMethodCheck
                </item>
            </argument>
        </arguments>
    </type>
    <type name="Smaex\AdditionalPaymentChecks\Plugin\WhitelistAdditionalChecks">
        <arguments>
            <argument name="additionalChecks" xsi:type="array">
                <item name="acme_custom_payment_method_check" xsi:type="string">
                    acme_custom_payment_method_check
                </item>
            </argument>
        </arguments>
    </type>
</config>
```

For a real-life example, check out [smaex/customer-group-payments][8] as well.

## We’re hiring!

Wanna work for [one of Germany’s leading Magento partners][9]? With agile methods, small teams and big clients? We’re currently looking for experienced ~~masochists~~ **PHP & Magento developers in Munich**. Sounds interesting? Just drop me a line via j.scherbl@techdivision.com

[1]: https://github.com/magento/magento2
[2]: https://github.com/magento/magento2/blob/2.2/app/code/Magento/Payment/Model/Checks/SpecificationInterface.php
[3]: https://github.com/magento/magento2/blob/2.2/app/code/Magento/Payment/Model/Checks/Composite.php
[4]: https://github.com/magento/magento2/blob/2.2/app/code/Magento/Payment/Model/Checks/SpecificationFactory.php
[5]: https://github.com/magento/magento2/blob/2.2/app/code/Magento/Payment/Model/MethodList.php
[6]: https://getcomposer.org
[7]: https://devdocs.magento.com/guides/v2.2/install-gde/install/cli/install-cli-subcommands-enable.html
[8]: https://github.com/smaex/customer-group-payments
[9]: https://www.techdivision.com/karriere/offene-stellen/magento-developer-m-w.html
