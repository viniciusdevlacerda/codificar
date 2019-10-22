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
        foreach ($this->deputado->getAllVerbas() as $deputado):
            $arrVebas = $this->deputado->getVerbasDeputado($deputado['id_deputado'], $deputado['dt_mes_referencia']);
            if(!empty($arrVebas)){
                foreach ($arrVebas as $key => $verba){
                    $data[$verba['id_deputado']][$verba['dt_mes_referencia']][] = $verba;
                }
            }
        endforeach;

            foreach ($data as $value):
                foreach ($value as $v): var_dump(count($v));die;endforeach;
            endforeach;

    }
}
