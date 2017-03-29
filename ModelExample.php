<?php

interface EncryptedModelInterface
{
	
	public function getEncryptedFields();
	
	public function encrypt($data);
	
	public function decrypt($data);
	
	private function getKey();
}

abstract class CoreModel 
{
	
	public function create($values){}
	public function read($column){}
	public function update($column, $value){}
	public function delete($id){}
	
	abstract public function getTableName();
}

abstract class EncryptedModel extends CoreModel implements EncryptedModelInterface
{
	private static final $KEY = 'jd165c7cb189oauc6ac';
	
	public function encrypt($data)
	{
		// STUB: Encrypt
	}
	
	public function decrypt($data)
	{
		// STUB: Decrypt
	}
	
	private function getKey()
	{
		return self::$KEY;
	}
	
	protected function checkColumn($column)
	{
		return in_array($column, $this->getEncryptedFields());
	}
	
	public function read($column)
	{
		$result = parent::read($column);
		
		if($this->checkColumn($column)) {
			$result = $this->decrypt($result);
		}
		
		return $result;
	}
	
	public function update($column, $value)
	{
		if($this->checkColumn($column)) {
			$value = $this->encrypt($value);
		}
		
		parent::update($column, $value);
	}
	
	public function create($values)
	{
		foreach($values as $column=>$value) {
			if($this->checkColumn($column)) {
				$values[$column] = $this->encrypt($value);
			}		
		}
		
		return parent::create($values);
	}
}


class User extends EncryptedModel
{
	public function getEncryptedFields()
	{
		return ['card_number', 'card_cvv', 'card_expiration_date', 'merchant_password'];
	}
}