<?php

class Item
{
    public $id;
    public $name;
    public $quantity;
    public $price;

    public function __construct($id, $name, $quantity, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getTotal(): float
    {
        return $this->quantity * $this->price;
    }
}

class Customer
{
    private $firstName;
    private $lastName;
    private $addresses = [];

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFullName(): string
    {
        return trim("{$this->firstName} {$this->lastName}");
    }

    public function addAddress(Address $address): void
    {
        $this->addresses[] = $address;
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}

class Address
{
    public $line_1;
    public $line_2;
    public $city;
    public $state;
    public $zip;

    public function __construct($line_1, $line_2, $city, $state, $zip)
    {
        $this->line_1 = $line_1;
        $this->line_2 = $line_2;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
    }
}

class Cart
{
    const TAX_RATE = 0.07;

    private $customer;
    private $items = [];
    private $shippingAddress;

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem($itemId): array
    {
        $this->items = array_filter($this->items, fn($item) => $item->id === $itemId);

        return $this->items;
    }

    public function setShippingAddress(Address $address): void
    {
        $this->shippingAddress = $address;
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function calculateSubtotal(): float
    {
        return array_reduce($this->items, fn($sum, $item) => $sum + $item->getTotal(), 0);
    }

    public function calculateTax(): float
    {
        return $this->calculateSubtotal() * self::TAX_RATE;
    }

    public function calculateShipping(): float
    {
        return 10.00;
    }

    public function calculateTotal(): float
    {
        return $this->calculateSubtotal() + $this->calculateTax() + $this->calculateShipping();
    }
}

$customer = new Customer('John', 'Doe');
$customer->addAddress(new Address('123 Main St', '', 'Anytown', 'CA', '12345'));

$cart = new Cart();
$cart->setCustomer($customer);
$cart->setShippingAddress($customer->getAddresses()[0]);
$cart->addItem(new Item(1, 'Widget', 2, 25.00));
$cart->addItem(new Item(2, 'Gadget', 1, 50.00));

echo "Customer: {$cart->getCustomer()->getFullName()}\n";
echo "Customer Adresses: {$cart->getShippingAddress()->line_1}\n";
echo "Items in cart: ".count($cart->getItems())."\n";
echo "Item #2 in cart: ".print_r($cart->getItem(2), true)."\n";
echo "Subtotal: $ {$cart->calculateSubtotal()}\n";
echo "Tax: $ {$cart->calculateTax()}\n";
echo "Shipping: $ {$cart->calculateShipping()}\n";
echo "Total: $ {$cart->calculateTotal()}\n";