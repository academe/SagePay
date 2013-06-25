<?php

/**
 * Models a single basket line.
 */

namespace Academe\SagePay\Model;

abstract class ProductLine //extends ProductLineAbstract
{
    /**
     * Mandatory properties.
     */

    protected $description;
    protected $quantity;
    protected $unitNetAmount;
    protected $unitTaxAmount;
    protected $unitGrossAmount;
    protected $totalGrossAmount;

    /**
     * Optional properties.
     * Structure is idicated by underscores.
     */

    $productSku = null;
    $productCode = null;
    $recipientFName = null;
    $recipientLName = null;
    $recipientMName = null;
    $recipientSal = null;
    $recipientEmail = null;
    $recipientPhone = null;
    $recipientAdd1 = null;
    $recipientAdd2 = null;
    $recipientCity = null;
    $recipientState = null;
    $recipientCountry = null;
    $recipientPostCode = null;
    $itemShipNo = null;
    $itemGiftMsg = null;

    // If a hotel is provided, the first set of fields are mandatory.
    $hotel_checkIn = null;
    $hotel_checkout = null; // CHECKME: typo in docs?
    $hotel_numberInParty = null;
    $hotel_guestName = null;

    $hotel_folioRefNumber = null;
    $hotel_confirmedReservation = null;
    $hotel_dailyRoomRate = null;

    /**
     * Set the main hotel fields.
     */

    public function setHotel($checkIn, $checkout, $numberInParty, $guestName)
    {
        $this->hotel_checkIn = $checkIn;
        $this->hotel_checkout = $checkout;
        $this->hotel_numberInParty = $numberInParty;
        $this->hotel_guestName = $guestName;

        return $this;
    }

    /**
     * Return the product line as a nested array in a structure that mimics the XML form.
     */

    public function toArray()
    {
        // TODO
    }
}
