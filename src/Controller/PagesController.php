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

        if (!$this->request->is('post')) {
            $this->request = $this->request->withData('home-value-before', '250000');
            $this->request = $this->request->withData('home-value-after', '250000');
            $this->request = $this->request->withData('income', '55000');
        }

        $this->set([
            'title_for_layout' => '',
            'counties' => $counties
        ]);
    }
}
