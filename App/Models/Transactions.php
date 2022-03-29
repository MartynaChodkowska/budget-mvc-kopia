<?php

namespace App\Models;

use PDO;
use \Core\View;


/**
 * Transactions model
 */

 class Transactions extends \Core\Model
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
     * Save the transaction model with the current property values
     * 
     * @return boolean  True if the transaction was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if(empty($this->errors)){

            $sql = 'INSERT INTO transactions (userId, date, amount, transactionGroup_id, transactionType, comment) 
                    VALUES (:userId, :date, :amount, :transactionGroup_id, :transactionType, :comment)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':transactionGroup_id', $this->selectMenu, PDO::PARAM_INT);
            $stmt->bindValue(':transactionType', $this->transactionType, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }


    /**
     * Validate current property values, adding validation error messages to the errors array property
     *
     * @return void
     */
	public function validate()
    {
        //Amount
        if ($this->amount == ''){
            $this->errors[] = 'Transaction amount is required';
        }

        //date
        if ($this->date == ''){
            $this->errors[] = 'Transaction date is required';
        }

        //category
        if ($this->selectMenu == ''){
            $this->errors[] = 'Transaction category is required';
        }
    }

    /** get incomes form given period of time
     * 
     * @return mixed incomes rows if found, false otherwise
     */
    public static function getIncomes($start, $end)
    {
        $sql = 'SELECT      transactionGroup_id, SUM(amount) as totalAmount 
                FROM        transactions
                WHERE       userId= :userId
                AND         date >= :start 
                AND         date <= :end
                AND         transactionType= :transactionType
                GROUP BY    transactionGroup_id
                ORDER BY    totalAmount DESC';
        
        $db = static::getDB();
        $incomes = $db->prepare($sql);
        $incomes->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $incomes->bindValue(':start', $start, PDO::PARAM_STR);
        $incomes->bindValue(':end', $end, PDO::PARAM_STR);
        $incomes->bindValue(':transactionType', 'Income', PDO::PARAM_STR);

        $incomes->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $incomes->execute();

        return $incomes->fetchAll();
    }

    /** get expenses form given period of time
     * 
     * @return mixed expenses rows if found, false otherwise
     */
    public static function getExpenses($start, $end)
    {
        
        $sql = 'SELECT      transactionGroup_id, SUM(amount) as totalAmount 
                FROM        transactions
                WHERE       userId= :userId
                AND         date >= :start 
                AND         date <= :end
                AND         transactionType= :transactionType
                GROUP BY    transactionGroup_id
                ORDER BY    totalAmount DESC';
        
        $db = static::getDB();
        $expenses = $db->prepare($sql);
        $expenses->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenses->bindValue(':start', $start, PDO::PARAM_STR);
        $expenses->bindValue(':end', $end, PDO::PARAM_STR);
        $expenses->bindValue(':transactionType', 'Expense', PDO::PARAM_STR);

        $expenses->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $expenses->execute();

        return $expenses->fetchAll();
    }

    /** get logged user last transactions
     * 
     * @return mixed expenses rows if found, false otherwise
     */
    public static function getLastTransactions($user_id)
    {
        if(isset($_POST['transactionsLimit']))	
            $limitNo = $_POST['transactionsLimit'];
		else 
            $limitNo = 5;

        $sql = 'SELECT date, amount, transactionGroup_id, comment 
                FROM transactions
                WHERE userId= :userId
                ORDER BY id DESC LIMIT :limitNo';

        $db = static::getDB();
        $last_transactions = $db->prepare($sql);
        $last_transactions->bindValue(':userId', $user_id, PDO::PARAM_INT);
        $last_transactions->bindValue(':limitNo', $limitNo, PDO::PARAM_INT);
        $last_transactions->execute();

        return $last_transactions->fetchAll();
    }

    /** get expenses form given period of time for PIE CHART
     * 
     * @return mixed expenses rows if found, false otherwise
     */
    public static function getExpensesForPieChart($start, $end)
    {
        
        $sql = 'SELECT      transactionGroup_id, SUM(amount) as totalAmount 
                FROM        transactions
                WHERE       userId= :userId
                AND         date >= :start 
                AND         date <= :end
                AND         transactionType= :transactionType
                GROUP BY    transactionGroup_id
                ORDER BY    totalAmount DESC';
        
        $db = static::getDB();
        $expenses = $db->prepare($sql);
        $expenses->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenses->bindValue(':start', $start, PDO::PARAM_STR);
        $expenses->bindValue(':end', $end, PDO::PARAM_STR);
        $expenses->bindValue(':transactionType', 'Expense', PDO::PARAM_STR);

        $expenses->execute();

        $arr = $expenses->fetchAll(PDO::FETCH_ASSOC);
    }
    
  }