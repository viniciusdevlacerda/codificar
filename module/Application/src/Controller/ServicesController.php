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
                    $data = $this->treatData('verbas', $value);
                    $this->deputado->setVerbasDeputados($data);
                endforeach;
            endforeach;
        }
        echo 'Verbas indenizatorias atualizadas com sucesso!';
        die();
    }

    public function requestGetVerbaIndenizatoriaMesAction()
    {
        $dados = [];
        foreach ($this->deputado->getAllVerbas() as $deputado):
            $arrVerbasMes = $this->almg->getVerbasIndenizatoriasMes($deputado['id_deputado'], $deputado['dt_mes_referencia']);
            if(!empty($arrVerbasMes)):
                foreach ($arrVerbasMes as $verba):
                    $dados [] = $verba;
                endforeach;
            endif;
        endforeach;

        foreach ($dados as $value):
            $data = $this->treatData('verbas_mes', $value);
            $this->deputado->setVerbasMesDeputados($data);
        endforeach;

        echo 'Verbas indenizatorias por mês atualizadas com sucesso!';
        die();
    }

    public function requestGetDetalhesVerbasAction()
    {
        $data = [];
        foreach ($this->deputado->getAllVerbas() as $deputado):
            $arrVerbasMes = $this->almg->getVerbasIndenizatoriasMes($deputado['id_deputado'], $deputado['dt_mes_referencia']);
            if(!empty($arrVerbasMes)):
                foreach ($arrVerbasMes as $verbaMes):
                    if(!empty($verbaMes)):
                    foreach ($verbaMes['listaDetalheVerba'] as $detalheVerba):
                        $dados = $this->treatData('detalhes_verbas', $detalheVerba);
                        $this->deputado->setVerbasDetalhes($dados);
                    endforeach;
                    endif;
                endforeach;
            endif;
        endforeach;

        echo 'Detalhes de Verbas indenizatorias atualizadas com sucesso!';
        die();
    }

    private function campo2banco($field)
    {
        $campo = !vazio($field) ? (is_object($field) ? reset($field) : $field) : NULL;
        return $campo;
    }

    private function treatData($tipo, $dados){
        switch ($tipo){
            case 'deputados':

                $data = [
                    'id_deputado' => $this->campo2banco($dados['id']),
                    'no_deputado' => $this->campo2banco($dados['nome']),
                    'ds_partido' => $this->campo2banco($dados['partido']),
                    'nu_tag_localizacao' => $this->campo2banco($dados['tagLocalizacao']),
                ];
                break;

            case 'verbas':

                $data = [
                    'id_deputado' => $this->campo2banco($dados['idDeputado']),
                    'dt_referencia' => $this->campo2banco($dados['dataReferencia']['$']),
                    'dt_mes_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[1]),
                    'dt_ano_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[2]),
                    'nu_total_verbas' => NULL,
                ];
                break;

            case 'verbas_mes':

                $data = [
                    'id_deputado' => $this->campo2banco($dados['idDeputado']),
                    'dt_referencia' => $this->campo2banco($dados['dataReferencia']['$']),
                    'dt_mes_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[1]),
                    'dt_ano_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[2]),
                    'id_tipo_despesa' => $this->campo2banco($dados['codTipoDespesa']),
                    'ds_tipo_despesa' => $this->campo2banco($dados['descTipoDespesa']),
                    'nu_valor' => $this->campo2banco($dados['valor']),
                ];
                break;
            case 'detalhes_verbas':

                $data = [
                    'id_verba_detalhe' => $this->campo2banco($dados['id']),
                    'id_deputado' => $this->campo2banco($dados['idDeputado']),
                    'dt_referencia' => $this->campo2banco($dados['dataReferencia']['$']),
                    'dt_mes_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[1]),
                    'dt_ano_referencia' => $this->campo2banco(explode('-',$dados['dataReferencia']['$'])[2]),
                    'nu_valor_reembolso' => $this->campo2banco($dados['valorReembolsado']),
                    'dt_emissao' => $this->campo2banco($dados['dataEmissao']['$']),
                    'nu_cpf_cnpj' => $this->campo2banco($dados['cpfCnpj']),
                    'nu_valor_despesa' => $this->campo2banco($dados['valorDespesa']),
                    'no_emitente' => $this->campo2banco($dados['nomeEmitente']),
                    'ds_documento' => isset($data['descDocumento']) ? $this->campo2banco($dados['descDocumento']) : NULL,
                    'id_tipo_despesa' => $this->campo2banco($dados['codTipoDespesa']),
                    'ds_tipo_despesa' => $this->campo2banco($dados['descTipoDespesa']),
                ];
                break;
        }

        return $data;
    }
}
