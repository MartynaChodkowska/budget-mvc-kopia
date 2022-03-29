<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Transactions;
use \App\Models\TransactionsGroups;
use \App\Auth;
use \App\Flash;


/**
 * Budget controller
 * 
 * PHP version 7.0
 */

 class Transaction extends Authenticated
 {
    /**
     * Before filter - called before each action method
     *
     * @return void
     */
	protected function before()
    {
    parent::before();
            
		$this->user = Auth::getUser();
    }
		
    
    
    /**
      * Show the budget panel page
      *
      *@return void
      */
      public function panelAction()
      {
          View::renderTemplate('Transaction/panel.html');
      }

      /**
       * Show the add income page
       * 
       * @return void
       */
         public function addincomeAction()
        {
            $incomesGroups = TransactionsGroups::getIncomesGroups();
            
            View::renderTemplate('Transaction/addIncome.html', [
                'groups' => $incomesGroups
            ]);
        }

        /**
         * Show the add expense page
         * 
         * @return void
         */
        public function addexpenseAction()
        {
            $expensesGroups = TransactionsGroups::getExpensesGroups();

            View::renderTemplate('Transaction/addExpense.html',[
                'groups' => $expensesGroups
            ]);
        }

        
        /**
         * Save new transaction
         * 
         * @return void
         */
        public function createAction()
        {
            $transaction = new Transactions($_POST);

            if ($transaction->save())
            {
                Flash::addMessage('Transaction has been successfully added!', Flash::SUCCESS);
                $this->lastTransactions();
            } else {
                View::renderTemplate('Transaction/panel', [
                    'transaction' => $transaction
                ]);
            }
        }

        /**
         * Show last transaction with given number of rows
         * 
         * @return void
         */
        public function lasttransactionsAction()
        {
            $this->lastTransactions();
        }

        /**
         * Get logged user last transactions with given number of rows and pass it to the view
         * 
         * @return void
         */
        public function lastTransactions()
        {   
            $last_transactions = Transactions::getLastTransactions($_SESSION['user_id']);
            $groups = TransactionsGroups::getAllGroups();
                    View::renderTemplate('Transaction/lasttransactions.html', [
                    'last_transactions'  => $last_transactions,
                    'groups'             => $groups
                ]);

        }


}