<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Transactions;
use \App\Models\TransactionsGroups;
use \App\Auth;
use \App\Date;
use \App\Flash;

/**
 * Balance sheet controler
 */

 class Balancesheet extends Authenticated
 {
    
    private $action = NULL;
    
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
     * set action to display appropriate balancesheet headline
     */
    public static function setaction($actionToSet)
    {
        $action = $actionToSet;
        
    }


    /**
     * get current month data and pass it to getTransaction function to get rows from given period
     * 
     * @return void
     */
    public function currentmonthAction()
    {
        $start = Date::getfirstDayOfCurrentMonth();
        $end = Date::getLastDayOfCurrentMonth();
        
        $action = "running month";
        $this->getTransactions($start, $end, $action);
    }

     /**
     * get previous month data and pass it to getTransaction function to get rows from given period
     * 
     * @return void
     */
    public function previousmonthAction()
    {
        $start = Date::getfirstDayOfPreviousMonth();
        $end = Date::getLastDayOfPreviousMonth();

        $action = "previous month";
        $this->getTransactions($start, $end, $action);
    }

    /**
     * get current year data and pass it to getTransaction function to get rows from given period
     * 
     * @return void
     */
    public function currentyearAction()
    {
        $start = Date::getFirstDayOfCurrentYear();
        $end = Date::getLastDayOfCurrentYear();

        $action = "running year";
        $this->getTransactions($start, $end, $action);
    }

    /**
     * get previous year data and pass it to getTransaction function to get rows from given period
     * 
     * @return void
     */
    public function previousyearAction()
    {
        $start = Date::getFirstDayOfPreviousYear();
        $end = Date::getLastDayOfPreviousYear();

        $action = "previous year";
        $this->getTransactions($start, $end, $action);
    }

    /**
     * show balance sheet panel
     * 
     * @return void
     */
    public function panelAction()
    {
        View::renderTemplate('Balancesheet/panel.html');
    }

    /**
     * show balance sheet with fileds to select dates  
     * 
     * @return void
     */
    public function selectdatesAction()
    {
        View::renderTemplate('Balancesheet/selectdates.html');
    }

    /**
     * get start and end date from user and pass it to getTransaction function to get rows from given period
     * 
     * @return void
     */
    public function customdatesAction()
    {
        $start = $_POST['startBalanceDate'];
        $end = $_POST['endBalanceDate'];

        if($start > $end)
        {
            Flash::addMessage('Wrong range of selected dates, please try again', Flash::WARNING);
            View::renderTemplate('Balancesheet/selectdates.html', [
                'start' => $start,
                'end'    => $end
            ]);
        }
        else
        {
            $action = $start . " to " . $end;
            $this->getTransactions($start, $end, $action);
        }
    }

    /**
     * Get transactions with given data and pass it to the view
     * 
     * @param $start, $end string
     * 
     * @return void
     */
    public function getTransactions($start, $end, $action)
    {
        $incomes = Transactions::getIncomes($start, $end);
        $totalIncomes = $this->getTotalIncomeAmount($incomes);
        $expenses = Transactions::getExpenses($start, $end);
        $totalExpenses = $this->getTotalExpenseAmount($expenses);

        $groups = TransactionsGroups::getAllGroups();

        View::renderTemplate('Balancesheet/balancesheet.html', [
            'incomes'       => $incomes,
            'totalIncomes'  => $totalIncomes,
            'expenses'      => $expenses,
            'totalExpenses' => $totalExpenses,
            'action'        => $action,
            'groups'        => $groups
        ]);
    }

    /**
     * Get total amount of incomes rows
     * 
     * @param $incomes transaction model
     * 
     * @return mixed 0 if no incomes, float otherwise
     */
    public function getTotalIncomeAmount($incomes)
    {
        $totalIncomeAmount = 0;
        foreach($incomes as $income){
            $totalIncomeAmount+=$income->totalAmount;
        }
        return $totalIncomeAmount;
    }

     /**
     * Get total amount of expenses rows
     * 
     * @param $expenses transaction model
     * 
     * @return mixed 0 if no expenses, float otherwise
     */
    public function getTotalExpenseAmount($expenses)
    {
        $totalExpenseAmount = 0;
        foreach($expenses as $expense){
            $totalExpenseAmount+=$expense->totalAmount;
        }
        return $totalExpenseAmount;
    }

    /**
     * Show piechart
     * 
     * @return void
     */
    public function piechartAction()
    {
        $data2d = Transactions::getExpenses('2022-03-01', '2022-03-31');
        $data2djson = json_encode($data2d);
        View::renderTemplate('Balancesheet/piechart.html', [
            'data2d'    => $data2djson
        ]);
    }

 }