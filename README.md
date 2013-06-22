
# SagePay Server #

Provides the functionality for Protocol 3 of the SagePay Server service.

## Main Requirements ##

A store is needed to track the transaction. This is handled by a descemndant class of xxx.
This library allows you you use any store you like, e.g. an active database record, a WP post type,
a REST resource.

## Limitations ##

The first instance of this library will focus on paying straight transactions. It will
no deal with repeating transactions for handling DEFERRED or AUTHENTICATE transaction types.


