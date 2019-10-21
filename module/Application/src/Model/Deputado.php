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
    public function getVerbasParam($arrParam)
    {
        $select = $this->sql
            ->select()
            ->from('tb_verbas')
            ->where($arrParam);
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
            ->order('id_deputado');
        return $this->selectWith($select)->toArray();
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