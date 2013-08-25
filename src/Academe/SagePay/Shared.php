<?php

/**
 * SagePay shared services.
 * Services shared by SagePay Server and SagePay Direct.
 * TODO: inheriting Register for now, but will change to Common once
 * Register is split up.
 */

namespace Academe\SagePay;

use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

class Shared extends Register //Common
{
    /**
     * Release a DEFERRED or REPEATDEFERRED payment.
     * CHECKME: SagePay does not document what the currency should be. It could be assumed to be
     * the same as the original transaction, so we could load up that transaction to get hold of
     * the currency. Although we do not need to send the currency in this transaction, the amount
     * still needs to be formatted correctly. We'll do this then remove it later if necessary.
     */

    public function release($OriginalVendorTxCode = '', $VPSTxId = '', $SecurityKey = '', $TxAuthNo = 0, $Amount = 0)
    {
        // Set the currency of the original transaction. This may not be necessary (see note above).
        $currency = $this->getTransactionCurrency($OriginalVendorTxCode);
        $this->setField('Currency', $currency);

        return $this->releaseOrAbort('RELEASE', $OriginalVendorTxCode, $VPSTxId, $SecurityKey, $TxAuthNo, $Amount);
    }

    /**
     * Abort or release a DEFERRED or REPEATDEFERRED payment.
     */

    protected function abort($TxType, $OriginalVendorTxCode = '', $VPSTxId = '', $SecurityKey = '', $TxAuthNo = 0)
    {
        return $this->releaseOrAbort('ABORT', $OriginalVendorTxCode, $VPSTxId, $SecurityKey, $TxAuthNo);
    }

    /**
     * Abort or release a DEFERRED or REPEATDEFERRED payment.
     * The VendorTxCode in this case is not the primary key of the transaction. It now
     * refers to the key of the original deferred transaction. We probably need an OriginalVendorTxCode
     * for storage, but that gets sent to SagePay as simply VendorTxCode.
     * So pass the VendorTxCode in here, or set the OriginalVendorTxCode field on the transaction.
     */

    protected function releaseOrAbort($TxType, $OriginalVendorTxCode = '', $VPSTxId = '', $SecurityKey = '', $TxAuthNo = 0, $Amount = 0)
    {
        if ($TxType == 'RELEASE') {
            $message_type = 'shared-release';
        } elseif ($TxType == 'ABORT') {
            $message_type = 'shared-abort';
        } else {
            // Not a valid transaction type.
            // TODO: throw error
        }

        // Set the transaction type.
        $this->setField('TxType', $TxType);

        // The VendorTxCode passed in points to the eoriginal transaction being released, and is not
        // the ID of this transaction.

        if ($OriginalVendorTxCode != '') {
            $this->setField('OriginalVendorTxCode', $OriginalVendorTxCode);
        } else {
            // If the VendorTxCode has been set (as the SagePay docs would lead) then move the
            // value to the Original field. This helps keep usability simple.

            $VendorTxCode = $this->getField('VendorTxCode');

            if (!empty($VendorTxCode)) {
                $this->setField('OriginalVendorTxCode', $VendorTxCode);
                $this->setField('VendorTxCode', null);
            }
        }

        // Other fields set straight-forward.
        if ($VPSTxId != '') $this->setField('VPSTxId', $VPSTxId);
        if ($SecurityKey != '') $this->setField('SecurityKey', $SecurityKey);
        if ($TxAuthNo != 0) $this->setField('TxAuthNo', $TxAuthNo);

        // The Amount is only relevant for the RELEASE transaction.

        if ($TxType == 'RELEASE') {
            // The amount is put into ReleaseAmount to send to SagePay and Amount for storage.

            if ($Amount != 0) {
                $this->setAmount($Amount);
            } else {
                // Make sure the amount is in both places.
                $amount = $this->getField('Amount');
                if (empty($amount)) $this->setAmount($this->getField('ReleaseAmount'));
            }
            $this->setField('ReleaseAmount', $this->getField('Amount'));
        }

        // The fields that we will be dealing with.
        // These fields are all mandatory, so we should validate that all are set (TODO).
        //$fields = \Academe\SagePay\Metadata\Transaction::get('object', array('source' => $message_type));

        // Construct the query string from data in the model.
        $query_string = $this->queryData(true, $message_type);

        // Get the URL, which is derived from the method, platform and the service.
        $sagepay_url = $this->getUrl();

        // Post the request to SagePay
        $output = $this->postSagePay($sagepay_url, $query_string, $this->timeout);

        if (isset($output['Status'])) {
            // Store the result (a Status and StatusDetail field only).
            $this->setField('Status', $output['Status']);
            $this->setField('StatusDetail', $output['StatusDetail']);
        } else {
            // TODO: fix postSagePay() so it guarantees to return a status pair. i.e. push this whole
            // condition back a layer.
            $this->setField('Status', 'FAIL');
            $this->setField('StatusDetail', 'Malformed return from SagePay');
        }

        // Save the result.
        $this->save();

        // Return true if successful.
        return ($this->getField('Status') == 'OK');
    }

    /**
     * Get the currency used on an existing transaction.
     */

    protected function getTransactionCurrency($VendorTxCode)
    {
        // We need to duplicate the current transaction object, as we don't want
        // to obliterate any data it already contains.
        $this->checkTxModel();
        $old_transaction = clone($this->tx_model);
        $old_transaction->find($VendorTxCode);

        if (!empty($old_transaction)) {
            return $old_transaction->getField('Currency');
        } else {
            // Just default to what we currently have.
            return $this->getField('Currency');
        }
    }
}

