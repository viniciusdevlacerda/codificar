<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Deputado;
use Application\WebServices\Almg;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ServicesController extends AbstractActionController
{
    private $almg;
    private $deputado;
    private $view;

    public function __construct()
    {
        $this->almg = new Almg();
        $this->deputado = new Deputado();
        $this->view = new ViewModel();
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
        foreach ($this->deputado->getAllDeputados() as $deputado):
            $arrVerbas = $this->almg->getVerbasIndenizatoriasDeputado($deputado['id_deputado']);
            if(!empty($arrVerbas)):
                foreach ($arrVerbas as $verba):
                    $data = $this->treatData('verbas', $verba);
                    $this->deputado->setVerbasDeputados($data);
                endforeach;
            endif;
        endforeach;
        echo 'Verbas indenizatorias atualizadas com sucesso!';
        die();
    }

    public function requestGetVerbaIndenizatoriaMesAction()
    {
        foreach ($this->deputado->getAllVerbas() as $deputado):
            $arrVerbasMes = $this->almg->getVerbasIndenizatoriasMes($deputado['id_deputado'], $deputado['dt_mes_referencia']);
            if(!empty($arrVerbasMes)):
                foreach ($arrVerbasMes as $verbaMes):
                    $dados = $this->treatData('verbas_mes',$verbaMes);
                    $this->deputado->setVerbasMesDeputados($dados);
                endforeach;
            endif;
        endforeach;

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
                    'dt_referencia' => $dados['dataReferencia']['$'],
                    'dt_mes_referencia' => explode('-',$dados['dataReferencia']['$'])[1],
                    'dt_ano_referencia' => explode('-',$dados['dataReferencia']['$'])[2],
                ];
                break;

            case 'verbas_mes':
                $data = [
                    'id_verba_mes' => '',
                    'id_deputado' => $dados['idDeputado'],
                    'dt_referencia' => $dados['dataReferencia']['$'],
                    'dt_mes_referencia' => explode('-',$dados['dataReferencia']['$'])[1],
                    'dt_ano_referencia' => explode('-',$dados['dataReferencia']['$'])[2],
                    'id_tipo_despesa' => $dados['codTipoDespesa'],
                    'ds_tipo_despesa' => $dados['descTipoDespesa'],
                    'nu_valor' => $dados['valor'],
                ];
                break;
        }

        return $data;
    }
}
