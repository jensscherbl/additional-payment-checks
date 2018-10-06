# Magento 2: Additional Payment Checks

Enables additional checks for payment methods in [Magento 2][1].

## Intro

Adding additional checks to payment methods – to decide if a certain payment method is applicable to a certain customer or not – is pretty easy and straight forward in Magento 2.

Magento [provides a rather simplistic interface][2] for custom payment method checks, and [uses a composite check][3] to process these individual checks. Adding a custom check is therefore just a matter of injecting it into Magento’s composite check via dependency injection.

Well, at least in an ideal world. But since we’re talking about Magento here, it’s geting a bit more ~~messy~~ complex. Different checks might be necessary in different scenarios, so Magento [uses a factory][4] to instantiate its composite check dynamically with a varying list of checks in different places.

```xml
<type name="Magento\Payment\Model\Checks\SpecificationFactory">
    <arguments>
        <argument name="mapping" xsi:type="array">
            <item name="customPaymentMethodCheck" xsi:type="object">
                <![CDATA[Acme\Payment\Model\Checks\CustomPaymentMethodCheck]]>
            </item>
        </argument>
    </arguments>
</type>
```

However, and in typical Magento fashion, this mechanism is only half baked. Magento [uses a class called MethodList][5] to retrieve applicable payment methods, and hardcodes the list of checks that are performed to figure out which payment methods to offer.

Fortunately, this extension provides a handy workaround.

## How it works

The extension plugs into the factory’s `create`-method and extends the list of checks that is passed to Magento’s composite check upon creation. All we have to do now, besides injecting our custom check into the factory as seen above, is adding it to the plugin provided by this extension.

```xml
<type name="Smaex\AdditionalPaymentChecks\Plugin\Payment\Model\Checks\SpecificationFactory\AdditionalChecks">
    <arguments>
        <argument name="additionalChecks" xsi:type="array">
            <item name="customPaymentMethodCheck" xsi:type="string">
                <![CDATA[customPaymentMethodCheck]]>
            </item>
        </argument>
    </arguments>
</type>
```

## How to install

Add the extension’s repository to the `composer.json` in your project’s root directory.

```json
"repositories": [
    {
        "type": "vcs",
        "url":  "https://github.com/smaex/additional-payment-checks.git"
    }
],
```

Then, simply require the extension via [Composer][6].

```sh
composer require smaex/additional-payment-checks ^1.0
```

## We’re hiring!

Wanna work for [one of Germany’s leading Magento partners][7]? With agile methods, small teams and big clients? We’re currently looking for experienced ~~masochists~~ **PHP & Magento developers in Munich**. Sounds interesting? Just drop me a line via j.scherbl@techdivision.com

[1]: https://github.com/magento/magento2
[2]: https://github.com/magento/magento2/blob/2.3-develop/app/code/Magento/Payment/Model/Checks/SpecificationInterface.php
[3]: https://github.com/magento/magento2/blob/2.3-develop/app/code/Magento/Payment/Model/Checks/Composite.php
[4]: https://github.com/magento/magento2/blob/2.3-develop/app/code/Magento/Payment/Model/Checks/SpecificationFactory.php
[5]: https://github.com/magento/magento2/blob/2.3-develop/app/code/Magento/Payment/Model/MethodList.php
[6]: https://getcomposer.org
[7]: https://www.techdivision.com
