<?php
namespace Application\Model;

use Application\Factories\AdapterFactory;
use Application\Factories\CommonFactory;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;


class Deputado extends AbstractTableGateway
{
    /** @var String */
    protected $table = "";
    protected $common;

    /**
     *
     * @return Void Seta adptador do banco de dados
     */
    public function __construct()
    {
        $this->adapter = AdapterFactory::getAdapter('codificar');
        $this->sql = new Sql($this->adapter);
        $this->common = new CommonFactory();
    }

    public function getAllDeputados()
    {
        $select = $this->sql
            ->select()
            ->from('tb_deputados')
            ->order('no_deputado');
        return $this->selectWith($select)->toArray();
    }

    public function getDeputadosByParam($param)
    {
        $select = $this->sql
            ->select()
            ->from('tb_deputados')
            ->where($param)
            ->order('no_deputado');
        return $this->selectWith($select)->current();
    }
    public function getVerbasDeputado($id_deputado, $mes)
    {
        $select = $this->sql
            ->select()
            ->from('tb_verbas_detalhes')
            ->where(['id_deputado' => $id_deputado])
            ->where(['dt_mes_referencia' => $mes]);
        return $this->selectWith($select)->toArray();
    }

    public function getVerbasByMes($mes)
    {
        $select = $this->sql
            ->select()
            ->from('tb_verbas_mes')
            ->where(['dt_mes_referencia' => $mes]);
        return $this->selectWith($select)->toArray();
    }

    public function getAllVerbas()
    {
        $select = $this->sql
            ->select()
            ->from('tb_verbas')
            ->order('dt_referencia ASC');
        return $this->selectWith($select)->toArray();
    }
    public function getNuVerbas()
    {
        $data = [];
        $select = $this->sql
            ->select()
            ->from('tb_verbas')
            ->join('tb_deputados','tb_verbas.id_deputado = tb_deputados.id_deputado',['no_deputado'])
            ->order('dt_referencia ASC');
        $verbasArr = $this->selectWith($select)->toArray();
        foreach ($verbasArr as $key => $verbas):
            $id_deputado = $verbas['id_deputado'];
            $mes_referencia = $verbas['dt_mes_referencia'];
            $select = $this->sql
                ->select()
                ->from('tb_verbas_detalhes')
                ->where(['id_deputado' => $id_deputado])
                ->where(['dt_mes_referencia' => $mes_referencia]);
            $arrVerbas = $this->selectWith($select)->toArray();
            if (!empty($arrVerbas)) {
                $verbas['nu_total_verbas'] = count($arrVerbas);
                $data['verbas_deputados'][$verbas['nu_total_verbas']] = $verbas;
            }
        endforeach;
        krsort($data);
        return $data;
    }

    public function getRedesSociais()
    {
        $data = $arrRedes = [];
        $select = $this->sql
            ->select()
            ->from('tb_redes_sociais');
        $RedesSociais = $this->selectWith($select)->toArray();
        foreach ($RedesSociais as $key => $redes):
            $data[$redes['id_rede_social']][] = $redes;
        endforeach;
        foreach($data as $value):
            $arrRedes['redes_sociais'][] = ['nome_rede_social' => $value[0]['no_rede_social'],'nu_deputados_usuarios'=> count($value)];
        endforeach;
        return $arrRedes;
    }

    public function setDeputados($data)
    {
        $this->table = 'tb_deputados';
        $this->insertUpdate($this->table, $data);
    }
    public function setVerbasDeputados($data)
    {
        $this->table = 'tb_verbas';
        $this->insertUpdate($this->table, $data);
    }
    public function setVerbasMesDeputados($data)
    {
        $this->table = 'tb_verbas_mes';
        $this->insertUpdate($this->table, $data);
    }
    public function setVerbasDetalhes($data)
    {
        $this->table = 'tb_verbas_detalhes';
        $this->insertUpdate($this->table, $data);
    }

    public function setRedesSociais($data)
    {
        $this->table = 'tb_redes_sociais';
        $this->insertUpdate($this->table, $data);
    }

    public function insertUpdate($table, $data, $arrKeys = null)
    {
        $tableGateway = new TableGateway($table, $this->adapter);

        $where = "1=1";

        $select = $this->sql
            ->select()
            ->from($table);
        if ($arrKeys):
            foreach ($arrKeys as $key):
                $value = $data[$key];
                $select->where("$key = '$value'");
                $where .= " and $key = '$value' ";
            endforeach;
        else:
            $key = array_keys($data)[0];
            $value = array_values($data)[0];
            $select->where("$key = '$value'");
            $where .= " and $key = '$value' ";
        endif;

        if (count($this->selectWith($select)->toArray())) {
            try {
                $tableGateway->update($data, $where);
            } catch (\Exception $e) {
                echo '<pre>Erro UPDATE: ', $e->getMessage(), "<br>\n";
                var_dump($table, $data) . "<br><br>\n\n</pre>";
            }
        } else {
            try {
                $tableGateway->insert($data);
            } catch (\Exception $e) {
                echo '<pre>Erro INSERT: ', $e->getMessage(), "<br>\n";
                var_dump($table, $data) . "<br><br>\n\n</pre>";
            }
        }
    }

    public function queryExec($type, $table, $data, $where = [])
    {
        $this->table = $table;
        $tableGateway = new TableGateway($this->table, $this->adapter);
        if ($type == 'INSERT') $tableGateway->insert($data);
        if ($type == 'UPDATE') $tableGateway->update($data, $where);
        if ($type == 'DELETE') $tableGateway->delete($where);
    }



}