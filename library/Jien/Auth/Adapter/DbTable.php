<?php

/**
 * I'd like to give credit for this nice adapter to some guy on stackoverflow, forgot where I got it from :/
 *
 */
class Jien_Auth_Adapter_DbTable extends Zend_Auth_Adapter_DbTable
{

    public function __construct ($zendDb = null, $tableName = null, $identityColumn = null, $credentialColumn = null)
    {

        //Get the default db adapter
        //From where?  It is not stored in the registry.
        if ($zendDb == null) {
            $zendDb = Jien::db();
        }

        //Set default values
        $tableName = $tableName ? $tableName : 'accounts';
        $identityColumn = $identityColumn ? $identityColumn : 'email';
        $credentialColumn = $credentialColumn ? $credentialColumn : 'password';

        parent::__construct($zendDb,
                                $tableName,
                                $identityColumn,
                                $credentialColumn);
    }

    protected function _authenticateCreateSelect()
    {
        // get select
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->_tableName)
                 ->where(
            $this->_zendDb->quoteIdentifier($this->_identityColumn, true)
            . ' = ?', $this->_identity);

        return $dbSelect;
    }

    protected function _authenticateValidateResult($resultIdentity)
    {
        //Check that hash value is correct
        $hash = new Jien_Auth_Hash(8, false);
        $check = $hash->CheckPassword($this->_credential,
                                    $resultIdentity['password']);

        if (!$check) {
            $this->_authenticateResultInfo['code'] =
                            Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $this->_authenticateResultInfo['messages'][] =
                            'Supplied credential is invalid.';
            return $this->_authenticateCreateAuthResult();
        }

        $this->_resultRow = $resultIdentity;

        $this->_authenticateResultInfo['code'] =
                            Zend_Auth_Result::SUCCESS;
        $this->_authenticateResultInfo['messages'][] =
                            'Authentication successful.';
        return $this->_authenticateCreateAuthResult();
    }


    public function getResultRowObject ($returnColumns = null, $omitColumns = null)
    {
        if ($returnColumns || $omitColumns) {
            return parent::getResultRowObject($returnColumns, $omitColumns);
        } else {
            $omitColumns = array('password');
            return parent::getResultRowObject($returnColumns, $omitColumns);
        }

    }

}