The error-codes.tsv are the errors that SagePay will produce, as collected from a number of
different sources. I suspect it is nowhere near complete, but we can keep adding messages as
we find them.

Every time we visit the SagePay site, we find different lists of error codes and messages.
Each visit has some new messages, and some missing, so the sets all overlap, but at least
the messages are consistent with the codes. Except, some of the messages are truncated with
and ellipsis, depending on which list you look at and which direction the wind is blowing
today.

Slowly we built up the list with merges. This is THE most complete list of SagePay error
codes that I know of. It does not record reasons and solutions for each message yet. That
is available for some messages, so it may be worth pulling them in too.

Please note that explanations and suggestions solutions in error-codes.tsv can be found
on the SagePay error codes tool here:

https://www.sagepay.co.uk/support/error-codes

The error-codes text in this library is for convenience only (e.g. for logging), was written
by SagePay and no claim of copyright is made by me or Academe Computing Ltd. Note also that
none of these error messages are designed to be put in front of end users (site visitors).
I am considering adding a "friendly" version of these to the error-codes.tsv file, as an
additoonal column. They would probably support placeholders too.

The "reason" and "solution" columns are slowly being added by Sagepay, but many are still
missing, and so are shown as blanks here. Many of the missing messages could be used to
fill in some of the gaps in the SagePay documentation, so I will keep my eye open for
any additions. For example, error 3175 is raised when the surcharge XML string is too long.
It does not say how long it can be, and the "reason" should be able to tell us it is
800 characters (I assume 800 ISO8859-1 bytes) even if the documentation remains silent
on that. The application-side validation should be able to catch most of that stuff
however, based on the limits set in the Transaction metadata.

