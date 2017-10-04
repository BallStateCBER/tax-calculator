<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Calculator\Calculator;
use App\Form\CalculatorForm;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays the home page
     *
     * @return void
     */
    public function home()
    {
        $this->loadModel('Counties');
        $counties = [];
        foreach (['IN', 'IL'] as $state) {
            $counties[$state] = $this->Counties
                ->find('list')
                ->where(['state' => $state])
                ->orderAsc('name')
                ->toArray();
        }

        $calculatorForm = new CalculatorForm();

        if ($this->request->is('post')) {
            $calculator = new Calculator();
            if ($calculatorForm->validate($this->request->getData())) {
                $output = $calculator->calculate([
                    'from_county' => $this->request->getData('from-county'),
                    'to_county' => $this->request->getData('to-county'),
                    'home_value_before' => $this->request->getData('home-value-before'),
                    'home_value_after' => $this->request->getData('home-value-after'),
                    'income' => $this->request->getData('income'),
                    'dependents' => $this->request->getData('dependents')
                ]);
                $this->set($output);
                $this->render('output');
            }
        } else {
            $this->request = $this->request->withData('home-value-before', '250000');
            $this->request = $this->request->withData('home-value-after', '250000');
            $this->request = $this->request->withData('income', '55000');
            $this->set([]);
        }

        $this->set([
            'calculatorForm' => $calculatorForm,
            'counties' => $counties,
            'title_for_layout' => ''
        ]);
    }
}
