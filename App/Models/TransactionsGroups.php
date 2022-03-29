<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 * Transactions groups model
 */

 class TransactionsGroups extends \Core\Model
 {
     /**
     * Class constructor
	 *
	 * @param array $data Initial property values
     *
     * @return void
     */
    public function __construct($data = [])
	{
		foreach($data as $key => $value){
			$this->$key = $value;
		};
	}
    
    /**
     * Get incomes Groups
     * 
     * @return mixed rows with group id and name, false otherwise
     */
    public static function getIncomesGroups()
    {
        $sql = 'SELECT id, name 
                FROM  transactiongroups
                WHERE type =:type';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':type', 'Income', PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get expenses Groups
     * 
     * @return mixed rows with group id and name, false otherwise
     */
    public static function getExpensesGroups()
    {
        $sql = 'SELECT id, name 
                FROM  transactiongroups
                WHERE type =:type';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':type', 'Expense', PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * get transactions groups
     * 
     * @return rows with group id, name and type 
     */
    public static function getAllGroups()
    {
        $sql = 'SELECT * FROM transactiongroups';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * save edited category
     * 
     * @return boolean  True if the category was saved, false otherwise 
     */
    public function save()
    {
        $this->validate();

        if(empty($this->errors)){
        
            $sql = 'UPDATE transactiongroups
                    SET name= :name
                    WHERE id= :id';
            
            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->newCategory, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);

            return $stmt->execute();
        }
        return false;
    }

     /**
     * Validate new category value, adding validation error messages to the errors array property
     *
     * @return void
     */
	public function validate()
    {
        if($this->newCategory == ''){
            $this->errors[] = 'new category name is required';
        }

        if (static::groupExists($this->newCategory, $this->selectType ?? null)){
			$this->errors[] = 'category already taken';
		}
    }

    /**
     * remove category from database
     * 
     * @return void
     */
    public function delete()
    {     
            $sql = 'DELETE FROM transactiongroups
                    WHERE id= :id';
            
            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $this->categoryId, PDO::PARAM_INT);

            return $stmt->execute();
      
    }

    /**
     * save new category
     * 
     * @return boolean  True if the category was saved, false otherwise 
     */
    public function add()
    {
        $this->validate();

        if(empty($this->errors)){
        
            $sql = 'INSERT INTO transactiongroups (name, type)
                    VALUES (:name, :type)';
            
            $db = static::getDB();

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->newCategory, PDO::PARAM_STR);
            $stmt->bindValue(':type', $this->selectType, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    /**
     * check if category already exists in database
     * 
     * @return boolean true is exits, false otherwise
     */
    public static function groupExists($group, $type)
    {
        $group = static::findGroup($group, $type);

        if ($group) {
				return true;
		}
		return false;
    }

    /**
     * looking for a group of transaction for passed name and transaction type
     * 
     * @param string $name group name to search for $type type of transaction assigned
     *
     * @return mixed Group object if found, false otherwise 
     */
     public static function findGroup($group, $type)
     {
        $sql = 'SELECT * FROM transactiongroups 
                WHERE name =:group
                AND type =:type';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':group', $group, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
		
		//zmieniamy na bardziej elastyczną wersję - w razie zmiany ścieżki czy coś..
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();

     }

 }