# Plugin installation

## Plugin configuration

Add the plugin configuration to your `config/bundles.php` file:

```php
return [
    // ...
    MonsieurBiz\SyliusAdvancedShippingPlugin\MonsieurBizSyliusAdvancedShippingPlugin::class => ['all' => true],
    // ...
];
```

Import the plugin's configuration by creating a new file `config/packages/monsieurbiz_sylius_advanced_shipping_plugin.yaml` with the following content:

```yaml
imports:
    - { resource: "@MonsieurBizSyliusAdvancedShippingPlugin/Resources/config/config.yaml" }
```

Add the plugin's routing by creating a new file `config/routes/monsieurbiz_sylius_advanced_shipping_plugin.yaml` with the following content:

```yaml
imports:
    resource: '@MonsieurBizSyliusAdvancedShippingPlugin/Resources/config/routes.yaml'
```

Run 

## Update entities

Your `Address` entity should implement `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface` and `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface` interfaces.  
And use the `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareTrait` and `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareTrait` traits.

```diff
amespace App\Entity\Addressing;

use Doctrine\ORM\Mapping as ORM;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareTrait;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareTrait;
use Sylius\Component\Core\Model\Address as BaseAddress;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_address")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_address')]
-class Address extends BaseAddress
+class Address extends BaseAddress implements AddressPickupPointAwareInterface, AddressTemporaryAwareInterface
{
+    use AddressPickupPointAwareTrait;
+    use AddressTemporaryAwareTrait;
}
```

Your `Order` entity should implement `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\TemporaryAddressesAwareInterface` and use the `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\TemporaryAddressesAwareTrait` trait.

```diff
namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\TemporaryAddressesAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\TemporaryAddressesAwareTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_order')]
-class Order extends BaseOrder
+class Order extends BaseOrder implements TemporaryAddressesAwareInterface
{
+    use TemporaryAddressesAwareTrait;
}
```

Your `Shipment` entity should implement `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareInterface` and use the `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareTrait` trait.

```diff
namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareTrait;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipment")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_shipment')]
-class Shipment extends BaseShipment
+class Shipment extends BaseShipment implements AdvancedShipmentMetadataAwareInterface
{
+    use AdvancedShipmentMetadataAwareTrait;
}
```

Your `ShippingMethod` entity should implement `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface` and `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeAwareInterface` interfaces.  
And use the `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareTrait` and `MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeAwareTrait` traits.

```diff
namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareTrait;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeAwareInterface;
+use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeAwareTrait;
use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;
use Sylius\Component\Shipping\Model\ShippingMethodTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipping_method")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_shipping_method')]
-class ShippingMethod extends BaseShippingMethod
+class ShippingMethod extends BaseShippingMethod implements AddressProviderAwareInterface, ShippingTypeAwareInterface
{
+    use AddressProviderAwareTrait;
+    use ShippingTypeAwareTrait;
+
    protected function createTranslation(): ShippingMethodTranslationInterface
    {
        return new ShippingMethodTranslation();
    }
}
```

## Update your database schema

Update your database schema with the plugin migrations:

```bash
bin/console doctrine:migrations:migrate
```

Generate the migration and update your database schema with the new entities fields:

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
