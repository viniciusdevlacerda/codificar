<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Factories\CommonFactory;
use Application\Model\Deputado;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $common;
    private $deputado;
    public function __construct()
    {
        $this->deputado = new Deputado();
        $this->common = new CommonFactory();
    }

    public function indexAction()
    {
        $data = [];
        foreach ($this->common->getMeses() as $mes){
            $arrVebasMes = $this->deputado->getVerbasByMes($mes);
            if(!empty($arrVebasMes)){
                foreach ($arrVebasMes as $key => $verba){
                    $data[$verba['id_deputado']][$verba['dt_mes_referencia']] = $verba;
                }
            }
        }

        var_dump($data);die;


    }
}
