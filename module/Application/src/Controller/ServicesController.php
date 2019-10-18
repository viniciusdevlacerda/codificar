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
    private static $containerVerbasIndenizatorias;

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
            $data = $this->getVerbasIndenizatoriasID($deputado['id_deputado']);
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
            $date = explode('-',$verba['dt_referencia']);
            $data = $this->getVerbasIndenizatoriasMes($verba['id_deputado'],$date[1]);
            if(!empty($data)):
                $arrVerbasMes[] = $data;
            endif;
        endforeach;

        if (!empty($arrVerbasMes)){
            foreach ($arrVerbasMes as $verbas):
                foreach ($verbas as $value):
                    $data = $this->treatData('verbas_mes',$value);
                    $this->deputado->setVerbasMesDeputados($data);
                endforeach;
            endforeach;
        }
        echo 'Verbas indenizatorias atualizadas com sucesso!';
        die();
    }

    private function getVerbasIndenizatoriasID($id_deputado){
        $header = ['cache-control: no-cache', 'content-type: application/x-www-form-urlencoded'];
        $url = "http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/legislatura_atual/deputados/$id_deputado/datas?formato=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrInfo = json_decode($response, true);
        return $arrInfo['list'];
    }

    private function getVerbasIndenizatoriasMes($id_deputado, $mes){
        $header = ['cache-control: no-cache', 'content-type: application/x-www-form-urlencoded'];
        $url = "http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/legislatura_atual/deputados/$id_deputado/2019/$mes?formato=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrInfo = json_decode($response, true);
        return $arrInfo['list'];
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
                    'id_deputado' => $dados['idDeputado'],
                    'dt_referencia' => $dados['dataReferencia']['$'],
                    'id_tipo_despesa' => $dados['codTipoDespesa'],
                    'ds_tipo_despesa' => $dados['descTipoDespesa'],
                    'valor' => $dados['valor'],
                ];
                break;
        }

        return $data;
    }
}
