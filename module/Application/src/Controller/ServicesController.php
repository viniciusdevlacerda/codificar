<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Deputado;
use Application\WebServices\Almg;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class ServicesController extends AbstractActionController
{
    private $view;
    private $deputado;
    private $almg;

    public function __construct()
    {
        $this->deputado = new Deputado();
        $this->view = new ViewModel();
        $this->almg = new Almg();
    }

    public function requestGetDeputadosListAction()
    {
        foreach ($this->almg->getDeputados() as $info):
            $dados = $this->treatData('deputados',$info);
            if (is_null($this->deputado->getDeputadosByParam($dados))){
                $this->deputado->setDeputados($dados);
                echo $dados['no_deputado']. " inserido com sucesso!<br>";
            }else{
                echo "Esse deputado já existe na nossa base<br>";
            }
        endforeach;

        die();
    }
    public function requestGetVerbaIndenizatoriaAction()
    {
        $arrVerbas = [];
        foreach ($this->deputado->getAllDeputados() as $deputado):
            $data = $this->almg->getVerbasIndenizatoriasDeputado($deputado['id_deputado']);
            if(!empty($data)):
                    $arrVerbas[] = $data;
            endif;
        endforeach;
        if (!empty($arrVerbas)){
            foreach ($arrVerbas as $verba):
                foreach ($verba as $value):
                    $data = $this->treatData('verbas',$value);
                    $this->deputado->setVerbasDeputados($data);
                endforeach;
            endforeach;
        }
        echo 'Verbas indenizatorias atualizadas com sucesso!';
        die();
    }

    public function requestGetVerbaIndenizatoriaMesAction()
    {
        $arrVerbasMes = [];
        foreach ($this->deputado->getAllVerbas() as $verba):
            $date = explode('-',$verba['dt_referencia'])[1];#mês de verba indenizatoria por deputado
            $data = $this->almg->getVerbasIndenizatoriasMes($verba['id_deputado'], $date);
            if(!empty($data)):
                $arrVerbasMes[] = $data;
            endif;
        endforeach;
        if (!empty($arrVerbasMes)){
            foreach ($arrVerbasMes as $verbas):
                foreach ($verbas as $value):
                    $data = $this->treatData('verbas_mes', $value);
                    $this->deputado->setVerbasMesDeputados($data);
                endforeach;
            endforeach;
        }
        echo 'Verbas indenizatorias atualizadas com sucesso!';
        die();
    }



    private function treatData($tipo, $dados){
        switch ($tipo){
            case 'deputados':
                $data = [
                    'id_deputado' => $dados['id'],
                    'no_deputado' => $dados['nome'],
                    'ds_partido' => $dados['partido'],
                    'nu_tag_localizacao' => $dados['tagLocalizacao'],
                ];
                break;

            case 'verbas':
                $data = [
                    'id_verba' => '',
                    'id_deputado' => $dados['idDeputado'],
                    'dt_referencia' => $dados['dataReferencia']['$']
                ];
                break;

            case 'verbas_mes':
                $data = [
                    'id_verbas_mes' => '',
                    'id_deputado' => $dados['idDeputado'],
                    'dt_referencia' => $dados['dataReferencia']['$'],
                    'id_tipo_despesa' => $dados['codTipoDespesa'],
                    'ds_tipo_despesa' => $dados['descTipoDespesa'],
                    'nu_valor' => $dados['valor'],
                ];
                break;
        }

        return $data;
    }
}
