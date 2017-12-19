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
use Cake\Http\Response;

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
     * @return Response
     */
    public function home()
    {
        $calculatorForm = new CalculatorForm();
        if ($this->request->is('post')) {
            $calculator = new Calculator($this->request->getData());
            if ($calculatorForm->validate($this->request->getData())) {
                $this->set('calculator', $calculator);

                return $this->render('output');
            }
        }

        $defaultData = [
            'home-value-before' => '250000',
            'home-value-after' => '250000',
            'income' => '55000',
            'is_married' => '0'
        ];
        foreach ($defaultData as $var => $val) {
            $this->request = $this->request->withData($var, $val);
        }
        $this->loadModel('Counties');
        $this->set([
            'calculatorForm' => $calculatorForm,
            'counties' => $this->Counties->getCountyOptions(),
            'title_for_layout' => ''
        ]);

        return $this->render('input');
    }
}
