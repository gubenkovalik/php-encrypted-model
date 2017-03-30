<?php
namespace jencat\encryptedmodel\src;
interface EncryptedModelInterface
{
	
	public function getEncryptedFields() : array;
	
	public function encrypt(string $data) : string;
	
	public function decrypt(string $data) : string;
	
	private function getKey() : string;
}

abstract class CoreModel 
{
	
	public function create($values){}
	public function read($column){}
	public function update($column, $value){}
	public function delete($id){}
	
	abstract public function getTableName() : string;
}

abstract class EncryptedModel extends CoreModel implements EncryptedModelInterface
{
	private static final $KEY = 'jd165c7cb189oauc6ac';
	
	public function encrypt($data) : string
	{
		// STUB: Encrypt
return $data;
	}
	
	public function decrypt($data) : string
	{
		// STUB: Decrypt
return $data;
	}
	
	private function getKey() : string
	{
		return self::$KEY;
	}
	
	protected function checkColumn(string $column) : bool
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
	public function getTableName() : string 
	{
		return 'users';
	}

	public function getEncryptedFields() : array
	{
		return ['card_number', 'card_cvv', 'card_expiration_date', 'merchant_password'];
	}
}
