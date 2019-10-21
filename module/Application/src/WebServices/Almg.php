<?php


namespace Application\WebServices;

use Application\AbstractClass\WebserviceAbstract;

class Almg extends WebserviceAbstract
{
    protected $urlPadrao;
    protected $params;

    public function __construct()
    {
        $this->urlPadrao = "http://dadosabertos.almg.gov.br/ws/";
    }

    public function getDeputados()
    {
        $this->url = "deputados/em_exercicio?formato=json";
        $data = $this->request();
        return $data['list'];
    }
    public function getVerbasIndenizatoriasDeputado($id_deputado)
    {
        $this->url = "prestacao_contas/verbas_indenizatorias/legislatura_atual/deputados/$id_deputado/datas?formato=json";
        $data = $this->request();
        return $data['list'];
    }
    public function getVerbasIndenizatoriasMes($id_deputado, $mes)
    {
        $this->url = "prestacao_contas/verbas_indenizatorias/legislatura_atual/deputados/$id_deputado/2019/$mes?formato=json";
        $data = $this->request();
        return $data['list'];
    }
}