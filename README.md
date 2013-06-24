# SagePay Server #

Provides the functionality for Protocol 3 of the SagePay Server service.

## Main Requirements ##

A store is needed to track the transaction. This is handled by a descemndant class of xxx.
This library allows you you use any store you like, e.g. an active database record, a WP post type,
a REST resource.

## Limitations ##

The first instance of this library will focus on paying straight transactions. It will
no deal with repeating transactions for handling DEFERRED or AUTHENTICATE transaction types.

## Status ##

This library is being actively worked on. The intention is for a back-end library for SagePay
protocol version 3, that can use any storage mechanism you like and does not have side-effects
related to input (i.e. does not read POST behind your back, so your application controls all
routing and input validation).

So far there is a storage model abstract, with an example PDO storage implementation. There are
models for the basket, addresses, customers, and surcharges.

I am completing the part that first registers a transaction with SagePay.

## Usage ##

Very roughly, registering a [payment] transaction request will look like this:

    // Note: this just half the process. This registers a payment request with the gateway.
    // The other half is handling the callback from SageWay with the results of the payment attempt,
    // and is not yet coded or documented.
    
    // Create the registration object.
    $register = new Academe\SagePay\Register();
    
    // Create a storage model object.
    // A basic PDO storage is provided, but just extend Model\Transaction and use your own.
    // Your framework may have active record model, or you may want to use WordPress post types, for example.
    $storage = new Academe\SagePay\Model\TransactionPdo();
    $storage->setDatabase('mysql:host=localhost;dbname=foobar', 'myuser', 'mypassword');
    
    // Inject the storage object.
    $register->setTransactionModel();
    
    // If you want to create a table ("sagepay_transactions" by default) for the PDO storage, do this.
    // The table will be created from the details in Metadata\Transaction and should provide a decent
    // out-of-the-box storage to get you up and running.
    $storage->createTable();
        
    // Set the main mandatory details for the transaction.
    $register->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback');
    
    // Set the addresses.
    // You can just set one (e.g. billing) and the other will automatically mirror it. Or set both.
    $billing = new Academe\SagePay\Model\Address();
    $billing->setField('Surname', 'Judge');
    $billing->setField('Firstnames', 'Jason');
    $billing->setField('Address1', 'Some Address');
    // etc.
    $register->setBillingAddress($billing);
    
    // Set option stuff, including customer details, surcharges, basket.
    // Here is an example for the basket. This is a very simple example, as SagePay 3.0
    // can support many more structured data items and properties in the basket.
    $basket = new Academe\SagePay\Model\Basket();
    $basket->setDelivery(32.50, 5);
    $basket->addSimpleLine('Widget', 4.00, 3, 0.75, 3.75);
    $register->setBasketModel($basket);
    
    // Send the request to SagePay, get the response, The request and response will also
    // be saved in whatever storage you are using.
    $response = $register->sendRegistration();

The response will provide details of what to do next: it may be a fail, or give a SagePay URL to jump to, or
just a simple data validation error to correct.

Somewhere above you need to tell the registration which platform you want to sent to (test or live).

SagePay is very strict on data validatin. If a postcode is too long, or an address has an invalid character
in, then it will reject the registration, but will not be very clear exactly why it was rejected, and
certainly not in a form that can be used to keep the end user informed. For this reason, do not just
throw an address at this library, but make sure you validate it according to SagePay validation rules, 
and provide a pre-submit form for the user to make corrections (e.g. to remove an invalid character from
an address field - something that may be perfectly valid in the framework that the address came from,
and may be perfectly valid in other payement gateways). Just get used to it - this is the Sage way - always
a little bit clunky in some unexpected ways.

I intend to include field metadata in this library, that provides information on the validation rules.
The library should validate everything before it goes to SagePay, but also those rules should be available
to feed into the framework that the end user interacts with.
