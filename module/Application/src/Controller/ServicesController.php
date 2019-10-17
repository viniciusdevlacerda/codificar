<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Deputado;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ServicesController extends AbstractActionController
{
    private $view;
    private $deputado;

    public function __construct()
    {
        $this->deputado = new Deputado();
        $this->view = new ViewModel();
    }

    public function requestGetDeputadosListAction()
    {
        $header = ['cache-control: no-cache', 'content-type: application/x-www-form-urlencoded'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $arrInfo = json_decode($response, true);

        foreach ($arrInfo['list'] as $info):
            $dados = $this->treatData('deputados',$info);
            if (is_null($this->deputado->getDeputadosByParam($dados))){
                $this->deputado->setDeputados($dados);
                echo $dados['no_deputado']. "inserido com sucesso!'\n";
            }else{
                echo "Esse deputado já existe na nossa base\n\n";
            }
        endforeach;

        die();
    }
    public function requestGetVerbaIndenizatoriaAction()
    {
        $arrVerbas = [];
        foreach ($this->deputado->getAllDeputados() as $deputado):
            $data = $this->getVerbasIndenizatoriasID($deputado['id_deputado']);
                if(!empty($data)): $arrVerbas = $data; endif;
            foreach ($arrVerbas as $verba):
                $data = $this->treatData('verbas',$verba);
                $this->deputado->setVerbasDeputados($data);
            endforeach;
            echo 'sucess<br>';
        endforeach;

        die();
    }

    private function getVerbasIndenizatoriasID($id_deputado){
        $info = file_get_contents("http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/legislatura_atual/deputados/$id_deputado/datas?formato=json");
        $arrInfo = json_decode($info, true);
        return $arrInfo['list'];
    }

    private function treatData($tipo, $info){
        switch ($tipo){
            case 'deputados':
                $data = [
                    'id_deputado' => $info['id'],
                    'no_deputado' => $info['nome'],
                    'ds_partido' => $info['partido'],
                    'nu_tag_localizacao' => $info['tagLocalizacao'],
                ];
                break;
            case 'verbas':
                $data = [
                    'id_verba' => '',
                    'id_deputado' => $info['idDeputado'],
                    'dt_referencia' => $info['dataReferencia']['$']
                ];
                break;
        }

        return $data;
    }
}
