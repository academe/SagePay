<?php

/**
 * Concrete storage example.
 * This uses PDO to write directly to a database table.
 * A table is created with a separate column for all SagePay transaction values, in all
 * directions. In reality you may not want to store all these values, or you may want to
 * serialise a bunch of them up into a single field, for convenience.
 */

namespace Academe\SagePay\Model;

use Academe\SagePay\Metadata as Metadata;

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

            // Get the field data and the metadata that describes those fields.
            $fields = $this->toArray();
            $field_meta = Metadata\Transaction::get();

            // Make a list of the fields we want to set in the database.
            $fields_to_update = array();
            foreach($fields as $field_name => $field_value) {
                // Skip null values so we don't update columns we have not set up yet.
                if (!isset($field_value)) continue;

                // Check the metadata to see if this is a field we want to save.
                if ( ! isset($field_meta->{$field_name}) || ! $field_meta->{$field_name}->store) {
                    continue;
                }

                $fields_to_update[$field_name] = $field_name;
            }

            // Insert or update?

            if ($new_record) {
                // Insert.
                $sql = 'INSERT INTO ' . $this->transaction_table_name . ' (';

                $sep = '';
                foreach($fields as $field_name => $field_value) {
                    // Skip null values so we don't update columns we have not set up yet.
                    if (!isset($field_value)) continue;

                    // Check the metadata to see if this is a field we want to save.
                    if ( ! isset($field_meta->{$field_name}) || ! $field_meta->{$field_name}->store) {
                        continue;
                    }

                    $sql .= $sep . $field_name;
                    $sep = ', ';
                }

                $sql .= ') VALUES (';

                $sep = '';
                foreach($fields as $field_name => $field_value) {
                    // Skip fields not listed as ones we want to store.
                    if ( ! isset($fields_to_update[$field_name])) continue;

                    $sql .= $sep . ':' . strtolower($field_name);
                    $sep = ', ';
                }
                $sql .= ')';

                $stmt = $pdo->prepare($sql);

                foreach($fields as $field_name => $field_value) {
                    // Skip fields not listed as ones we want to store.
                    if ( ! isset($fields_to_update[$field_name])) continue;

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
                    // Skip fields not listed as ones we want to store.
                    if ( ! isset($fields_to_update[$field_name])) continue;

                    // Skip updating the primary key for the update field list.
                    // We will still bind to it though, for use in the WHERE clause.
                    if ($field_name == 'VendorTxCode') continue;

                    $sql .= $sep . $field_name . ' = :' . strtolower($field_name);
                    $sep = ', ';
                }

                $sql .= ' WHERE VendorTxCode = :vendortxcode';

                $stmt = $pdo->prepare($sql);

                foreach($fields as $field_name => $field_value) {
                    // Skip fields not listed as ones we want to store.
                    if ( ! isset($fields_to_update[$field_name])) continue;

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
     * Returns true if found, false if not found.
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

            $sql = 'CREATE TABLE IF NOT EXISTS ' . $this->transaction_table_name . " (\n";
            $sql .= $this->createColumnsDdl();
            $sql .= ', PRIMARY KEY pk_' . $this->transaction_table_name . ' (VendorTxCode) )';


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
     * Generate the database column creation statements from the Transaction metadata.
     */

    public function createColumnsDdl()
    {
        // NOTE: the column lengths are to support unicode (UTF-8) characters, and not ASCII bytes.
        // If the database is not set up with a unicode charset, then we need to add a percentage 
        // to the lengths of all columns.
        $field_meta = Metadata\Transaction::get();

        $columns = array();

        foreach($field_meta as $name => $field) {
            if ( ! $field->store ) continue;

            $column = $name . ' ';

            if (!isset($field->max)) continue;

            $max = $field->max;

            // If currency, then take the max value as a string and add 3 (for the dp and 2 dp digits)
            if ($field->type == 'currency') $max = strlen((string)$max) + 3;

            // Now here is a nasty hack.
            // Columns that can accept UTF-8 data need to have their length multiplied by
            // four to guarantee a full UTF-8 string can fit in. There are probably ways around
            // this, but the documentation is (and always has been) veru confused, mixing up the
            // storage of charactersets, the searching of charactersets and the automatic
            // conversion between the two. This is nasty, nasty, but should work everywhere.
            if (
                (isset($field->chars) && in_array('^', $field->chars))
                || $field->type == 'rfc532n'
                || $field->type == 'html'
                || $field->type == 'xml'
            ) {
                $max = $max * 4;
            }

            // VARCHAR for most columns, apart from the really long ones.
            $datatype = 'VARCHAR';
            if ($max > 2048) {
                $datatype = 'TEXT';
                $max = null;
            }

            $column .= $datatype;
            if (isset($max)) $column .= '(' . $max . ')';

            if (!empty($field->required)) $column .= ' NOT NULL';

            $columns[] = $column;
        }

        return implode(",\n", $columns);
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

