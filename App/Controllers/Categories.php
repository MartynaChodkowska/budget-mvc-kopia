<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\TransactionsGroups;
use \App\Flash;

/**
 * Categories controller
 */

class Categories extends Authenticated
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
      * Show categories panel page
      *
      *@return void
      */
      public function panelAction()
      {
          View::renderTemplate('Categories/panel.html');
      }

    /**
     * Get transactions categories and pass it to categories review
     * 
     * @return void
     */
      public function reviewAction()
      {
          $incomesCategories = TransactionsGroups::getIncomesGroups();
          $expensesCategories = TransactionsGroups::getExpensesGroups();

          View::renderTemplate('Categories/review.html',[
              'incomesCategories'   => $incomesCategories,
              'expensesCategories'  => $expensesCategories
          ]);
      }

      /**
       * Show category edition panel
       * 
       * @return void
       */
      public function editAction()
      { 
          $incomesCategories = TransactionsGroups::getIncomesGroups();
          $expensesCategories = TransactionsGroups::getExpensesGroups();
          
          View::renderTemplate('Categories/edit.html', [
              'incomesCategories'   => $incomesCategories,
              'expensesCategories'  => $expensesCategories
          ]);
      }

      /**
       * Save edited category
       * 
       * @return void
       */
      public function updateAction()
      {
        $category = new TransactionsGroups($_POST);

        if($category->save())
        {
          Flash::addMessage('category has been succesfully edited!', Flash::SUCCESS);
          $this->reviewAction();
        }
        else
        {
          $this->editAction();
        }
      }

      /**
       * Show category deletion panel
       * 
       * @return void
       */
      public function deleteAction()
      { 
          $incomesCategories = TransactionsGroups::getIncomesGroups();
          $expensesCategories = TransactionsGroups::getExpensesGroups();
          
          View::renderTemplate('Categories/delete.html', [
              'incomesCategories'   => $incomesCategories,
              'expensesCategories'  => $expensesCategories
          ]);
      }

      /**
       * deleting category
       * 
       * @return void
       */
      public function removeAction()
      {
        $category = new TransactionsGroups($_POST);

        if($category->delete())
        {
          Flash::addMessage('category has been succesfully deleted!', Flash::SUCCESS);
          $this->reviewAction();
        }
        else
        {
          $this->deleteAction();
        }
      }

      /**
       * Show category adding panel
       * 
       * @return void
       */
      public function addAction()
      { 
          View::renderTemplate('Categories/add.html');
      }

      /**
       * add a new category to database
       * 
       * @return void
       */
      public function createAction()
      {
        $category = new TransactionsGroups($_POST);
        
        if($category->add())
        {
          Flash::addMessage('category has been succesfully added!', Flash::SUCCESS);
          $this->reviewAction();
        }
        else
        {
          Flash::addMessage('category already taken!', Flash::WARNING);
          View::renderTemplate('Categories/add.html',[
              'name' => $category
          ]);
        }

      }
     
}