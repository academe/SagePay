# Client Library for SagePay Server - Protocol V3 #

Provides the functionality for Protocol 3 of the SagePay Server service.

## Main Requirements ##

A store is needed to track the transaction. This is handled by a descemndant class of Academe\SagePay\Model\TransactionAbstract.php.
This library allows you you use any store you like, e.g. an active database record, a WP post type,
a REST resource.

## Limitations ##

The first working release of this library will focus on paying PAYMENT transactions. It will
not deal with repeating transactions for handling DEFERRED or AUTHENTICATE transaction types, or
the myriad other types. However, they should all be straight-forward to implement.

## Status ##

This library is being actively worked on. Having said that, is *is* production-ready and is in service now.
The intention is for a back-end library for SagePay
protocol version 3, that can use any storage mechanism you like and does not have side-effects
related to input (i.e. does not read POST behind your back, so your application controls all
routing and input validation).

So far there is a storage model abstract, with an example PDO storage implementation. There are
models for the basket, addresses, customers, and surcharges.

This library does not depend on any other composer libraries at present. If using composer, it
can be installed like this:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/academe/SagePay"
            }
        ],
        "require": {
            "php": ">=5.3.0",
            "academe/sagepay": "dev-master"
        }
    }

Or if working on a clone of this repository in in vendor/sagepay:

    {
        "autoload": {
            "psr-0": "Academe\\SagePay": "vendor/sagepay/src"
        }
    }

## Usage ##

Very roughly, registering a [payment] transaction request will look like this:

    // In all the code examples here, I will assume a PSR-0 autoloader is configured.
    // e.g. for composer this may be included like this, taking the path to the vendor
    // directory into account:
    
    require 'vendor/autoload.php';

    // This just half the process. This registers a payment request with the gateway.
    
    // Create the registration object.
    
    $register = new Academe\SagePay\Register();
    
    // Create a storage model object.
    // A basic PDO storage is provided, but just extend Model\Transaction and use your own.
    // Your framework may have active record model, or you may want to use WordPress post types, for example.
    // You can write your own transaction storage model, perhaps storing the transaction data in a custom
    // post type in WordPress, or a database model in your framework. This TransactionPdo model is just
    // a usable example that comes with the library.
    
    $storage = new Academe\SagePay\Model\TransactionPdo();
    $storage->setDatabase('mysql:host=localhost;dbname=foobar', 'myuser', 'mypassword');
    
    // Within WordPress, setting the database details looks like this:
    
    $storage->setDatabase('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    
    // Inject the storage object.
    
    $register->setTransactionModel($storage);
    
    // If you want to create a table ("sagepay_transactions" by default) for the PDO storage, do this.
    // The table will be created from the details in Metadata\Transaction and should provide a decent
    // out-of-the-box storage to get you up and running. You could execute this in the initialisation
    // hook of a plugin, assuming you are not using a custom post type to track the transactions.
    
    $storage->createTable();
        
    // Set the main mandatory details for the transaction.
    
    $register->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');
    
    // Indicate which platform you are connecting to - test or live.
    
    $register->setPlatform('test');
    
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
    
    $register->sendRegistration();

The response will provide details of what to do next: it may be a fail, or give a SagePay URL to jump to, or
just a simple data validation error to correct. If `$register->getField('Status')` is "OK" then redirect
the user to `$register->getField('NextURL')` otherwise handle the error.

SagePay is very strict on data validatin. If a postcode is too long, or an address has an invalid character
in, then it will reject the registration, but will not be very clear exactly why it was rejected, and
certainly not in a form that can be used to keep the end user informed. For this reason, do not just
throw an address at this library, but make sure you validate it according to SagePay validation rules, 
and provide a pre-submit form for the user to make corrections (e.g. to remove an invalid character from
an address field - something that may be perfectly valid in the framework that the address came from,
and may be perfectly valid in other payement gateways). Just get used to it - this is the Sage way - always
a little bit clunky in some unexpected ways.

The field metadata in this library provides information on the validation rules.
The library should validate everything before it goes to SagePay, but also those rules should be available
to feed into the framework that the end user interacts with. Work is still to be done here.

Once a transaction registration is submitted successfuly, and the user is sent to SagePay to enter their
card details, SagePay will send the result to the callback URL. This is easily handled, with mycallback.php
looking something like this:

    // Gather the POST data.
    // For some platforms (e.g. WordPress) you may need to do some decoding of the POST data.
    
    $post = $_POST;
    
    // Set up the transaction model, same as when registering. Here is a slightly shorter-hand version.
    
    $register = new Academe\SagePay\Register();
    $register->setTransactionModel(new Academe\SagePay\Model\TransactionPdo())
        ->setDatabase('mysql:host=localhost;dbname=foobar', 'myuser', 'mypassword');
    
    // Handle the notification.
    // The final URL sent back, which is where the user will end up. We are also passing the
    // status with the URL for convenience, but don't rely on looking at that status to
    // determine if the payment was successful - a user could fake it.
    
    $result = $register->notification(
        $post, 
        'http://example.com/mysite/final.php?status={{Status}}'
    );
    
    // Other actions can be performed here, based on what we find in `$register->getField('Status')`
    // For example, you may want to inform an application that an invoice has been paid.
    // You may also want to send the user an email at this point (to `$register->getField('CustomerEMail')`
    
    // Return the result to SagePay.
    // Do not output *anything* else on this page. SagePay is expecting the contents of $result *only*.
    
    echo $result;
    
    exit();
    
Now, at this point the user will be sent back to mysite/final.php

Here the user needs to be informed about the outcome, and that will depend on the result and contents
of the transaction. The page will need the VendorTxCode to get hold of the transaction like this:

    // Set up the transaction model, same as when registering. Here is a slightly shorter-hand version.
    
    $register = new Academe\SagePay\Register();
    $register->setTransactionModel(new Academe\SagePay\Model\TransactionPdo())
        ->setDatabase('mysql:host=localhost;dbname=foobar', 'myuser', 'mypassword');

    // Fetch the transaction from storage.
    
    $register->findTransaction($VendorTxCode);
    
    // Look at the result and take it from there.
    
    $status = $register->getField('Status');
    
    if ($status == 'OK' || $status == 'AUTHENTICATED' || $status == 'REGISTERED') {
        echo "Cool. Your payment was successful.";
    } elseif ($status == 'PENDING') {
        echo "Your payment has got delayed while being processed - we will email you when it fimally goes through.";
    } else {
        echo "Whoops - something went wrong here. No payment has been taken.";
    }
    
The question is where the VendorTxCode comes from. It could be passed in via the URL by setting the final
URL in the callback page to `mysite/final.php?txcode={{VendorTxCode}}` However, you may not want that ID
to be exposed to the user. Also this final page would be permanently active - the transaction code will
always be there in storage until it is cleared out.

You may save VendorTxCode in the session when the payment registration is first
made, and then retrieve it (and delete it) on the final page. That way this becomes a once-only access to the
transaction result. If the user pays for several transactions at the same time, then the result of the
transaction started first will be lost, but the processing should otherwise work correctly.

The PENDING status is one to watch. For that status, the transaction is neither successful nor failed. It is
presumably on some queue at some bank somewhere to be processed. When it is processed, the callback page
will be called up by SagePay with the result. This is important to note, because the user will not be
around to see that happen. So if the user needs to be notified by email, or the transaction result needs
to be tied back to some action in the application (e.g. marking an invoice as paid or a membership as renewed)
then it MUST be done in the callback page. Do *not* rely on this kind of thing to be done in the final.php
page that the user is sent to.

You can make use of the `CustomerData` field in the transaction for linking the payment to a resource in
the application to be actioned.
