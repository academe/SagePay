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


