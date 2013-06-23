<?php

/**
 * Concrete storage example.
 * This uses PDO to write directly to a database table.
 * A table is created with a separate column for all SagePay transaction values, in all
 * directions. In reality you may not want to store all these values, or you may want to
 * serialise a bunch of them up into a single field, for convenience.
 */

namespace Academe\SagePay\Model;

class TransactionPdo extends TransactionAbstract
{
    /**
     * Database connection details for creating tables or managing data in the table
     * (depending on which methods are being used).
     */

    protected $pdo_connect = '';
    protected $pdo_username = '';
    protected $pdo_password = '';

    /**
     * The name of the table used to store the transactions.
     */

    public $transaction_table_name = 'sagepay_transactions';

    /**
     * Details of any PDO error messages.
     */

    public $pdo_error_message = '';

    /**
     * Set the database credentials.
     * The $connect string is a PDO resource, e.g.
     *  'mysql:host=localhost;dbname=my_database'
     */

    public function setDatabase($connect, $username, $password, $tablename = null)
    {
        $this->pdo_connect = $connect;
        $this->pdo_username = $username;
        $this->pdo_password = $password;

        if (isset($tablename)) $this->setTablename($tablename);

        return $this;
    }

    /**
     * Set the database credentials.
     */

    public function setTablename($tablename)
    {
        $this->transaction_table_name = $tablename;
    }

    /**
     * Save the transaction record to storage.
     */

    public function save()
    {
        // If new_record is true, then we are inserting.
        $new_record = false;

        // Set a VendorTxCode (the primary key) if we don't have one.
        // Assume we will be creating a new record if we need to generate the code.

        try {
            // Connect to the database.
            $pdo = $this->getConnection();

            if ($this->getField('VendorTxCode')  == '') {
                $this->setField('VendorTxCode', $this->makeVendorTxCode());
                $new_record = true;
            } else {
                // Try looking for the record on the database. If it exists, then
                // we UPDATE the existing record, otherwise we INSERT a new record.
                // Just because we have a vendor transactino code, it does not mean the
                // record came from storage - the code may have been created externally
                // in advance.

                $stmt = $pdo->prepare(
                    'SELECT COUNT(*) FROM ' . $this->transaction_table_name
                        . ' WHERE VendorTxCode = :vendortxcode'
                );
                $stmt->bindParam(':vendortxcode', $this->getField('VendorTxCode'), \PDO::PARAM_STR);

                $stmt->execute();

                $row = $stmt->fetch(\PDO::FETCH_NUM);
                if ( ! isset($row[0])) return;
                if ($row[0] == 0) {
                    $new_record = true;
                }
            }

            // Insert or update.
            $fields = $this->toArray();
            if ($new_record) {
                // Insert.
                $sql = 'INSERT INTO ' . $this->transaction_table_name . ' (';

                $sep = '';
                foreach($fields as $field_name => $field_value) {
                    // Skip null values so we don't update columns we have not set up yet.
                    if (!isset($field_value)) continue;

                    $sql .= $sep . $field_name;
                    $sep = ', ';
                }

                $sql .= ') VALUES (';

                $sep = '';
                foreach($fields as $field_name => $field_value) {
                    if (!isset($field_value)) continue;

                    $sql .= $sep . ':' . strtolower($field_name);
                    $sep = ', ';
                }
                $sql .= ')';

                $stmt = $pdo->prepare($sql);

                foreach($fields as $field_name => $field_value) {
                    // Note we need to bind a variable, and not just a value.
                    $stmt->bindParam(
                        ':' . strtolower($field_name),
                        $fields[$field_name],
                        \PDO::PARAM_STR
                    );
                }

                $stmt->execute();
            } else {
                // Update.
                $sql = 'UPDATE ' . $this->transaction_table_name . ' SET ';

                $sep = '';
                foreach($fields as $field_name => $field_value) {
                    // Skip null values so we don't update columns we have not set up yet.
                    if (!isset($field_value)) continue;

                    if ($field_name == 'VendorTxCode') continue;

                    $sql .= $sep . $field_name . ' = :' . strtolower($field_name);
                    $sep = ', ';
                }

                $sql .= ' WHERE VendorTxCode = :vendortxcode';

                $stmt = $pdo->prepare($sql);

                foreach($fields as $field_name => $field_value) {
                    // Note we need to bind a variable, and not just a value.
                    $stmt->bindParam(
                        ':' . strtolower($field_name),
                        $fields[$field_name],
                        \PDO::PARAM_STR
                    );
                }

                $stmt->execute();
            }
        }
        catch (\PDOException $e) {
            $this->pdo_error_message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Retrieve a record from storage.
     */

    public function find($VendorTxCode)
    {
        try {
            // Connect to the database.
            $pdo = $this->getConnection();

            $stmt = $pdo->prepare(
                'SELECT * FROM ' . $this->transaction_table_name
                    . ' WHERE VendorTxCode = :vendortxcode'
            );
            $stmt->bindParam(':vendortxcode', $VendorTxCode, \PDO::PARAM_STR);

            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (empty($row)) {
                return false;
            }

            foreach($row as $field_name => $field_value) {
                $this->setField($field_name, $field_value);
            }
        }
        catch (\PDOException $e) {
            $this->pdo_error_message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Create the database table for the transactions.
     * Returns true if successful.
     */

    public function createTable()
    {
        try {
            // Connect to the database.
            $pdo = $this->getConnection();

            // Create the table.
            // TxAuthNo is returned as a long integer, but we will store it as a VARCHAR.
            // TODO: this table creation statement can be generated entirely from the
            // field metadata. It should work like that to keep consistency.

            $sql = <<<ENDSQL
                CREATE TABLE IF NOT EXISTS $this->transaction_table_name (
                    VendorTxCode VARCHAR(40) NOT NULL,
                    VPSProtocol VARCHAR(10) NOT NULL,
                    TxType VARCHAR(20) NOT NULL,
                    Vendor VARCHAR(80),
                    Amount VARCHAR(20) NOT NULL,
                    Currency VARCHAR(10) NOT NULL,
                    Description VARCHAR(100) NOT NULL,

                    BillingSurname VARCHAR(20),
                    BillingFirstnames VARCHAR(20),
                    BillingAddress1 VARCHAR(100),
                    BillingAddress2 VARCHAR(100),
                    BillingCity VARCHAR(40),
                    BillingPostCode VARCHAR(10),
                    BillingCountry VARCHAR(2),
                    BillingState VARCHAR(2),
                    BillingPhone VARCHAR(20),

                    DeliverySurname VARCHAR(20),
                    DeliveryFirstnames VARCHAR(20),
                    DeliveryAddress1 VARCHAR(100),
                    DeliveryAddress2 VARCHAR(100),
                    DeliveryCity VARCHAR(40),
                    DeliveryPostCode VARCHAR(10),
                    DeliveryCountry VARCHAR(2),
                    DeliveryState VARCHAR(2),
                    DeliveryPhone VARCHAR(20),

                    CustomerEMail VARCHAR(255),

                    AllowGiftAid VARCHAR(1),
                    ApplyAVSCV2 VARCHAR(1),
                    Apply3DSecure VARCHAR(1),
                    BillingAgreement VARCHAR(1),
                    AccountType VARCHAR(1),
                    CreateToken VARCHAR(1),

                    BasketXML TEXT,
                    CustomerXML VARCHAR(2000),
                    SurchargeXML VARCHAR(800),
                    VendorData VARCHAR(200),

                    Status VARCHAR(14),
                    StatusDetail VARCHAR(255),
                    VPSTxId VARCHAR(38),
                    SecurityKey VARCHAR(10),

                    TxAuthNo VARCHAR(20),
                    AVSCV2 VARCHAR(50),
                    AddressResult VARCHAR(20),
                    PostCodeResult VARCHAR(20),
                    CV2Result VARCHAR(20),
                    GiftAid VARCHAR(1),
                    3DSecureStatus VARCHAR(50),
                    CAVV VARCHAR(30),
                    AddressStatus VARCHAR(20),
                    PayerStatus VARCHAR(20),
                    CardType VARCHAR(15),
                    Last4Digits VARCHAR(4),

                    VPSSignature VARCHAR(100),
                    FraudResponse VARCHAR(10),
                    Surcharge VARCHAR(20),

                    BankAuthCode VARCHAR(6),
                    DeclineCode VARCHAR(2),
                    ExpiryDate VARCHAR(4),
                    Token VARCHAR(38),

                    PRIMARY KEY vendor_tx_code (VendorTxCode)
                )
ENDSQL;

            // TOOD: check the result.
            $success = $pdo->exec($sql);
        }
        catch (\PDOException $e) {
            $this->pdo_error_message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Drop the database table for the transactions.
     * Returns true if successful.
     */

    public function dropTable()
    {
        try {
            // Connect to the database.
            $pdo = new \PDO($this->pdo_connect, $this->pdo_username, $this->pdo_password);

            // TOOD: check the result.
            $success = $pdo->exec('DROP TABLE ' . $this->transaction_table_name);
        }
        catch (\PDOException $e) {
            $this->pdo_error_message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Get a database connection.
     */

    protected function getConnection()
    {
        // Connect to the database.
        $pdo = new \PDO($this->pdo_connect, $this->pdo_username, $this->pdo_password);

        // Capture all errors.
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}

