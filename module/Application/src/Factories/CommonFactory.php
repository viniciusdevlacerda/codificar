<?php
namespace Application\Factories;

class CommonFactory
{

    public static $config;

    /**
     *
     * @param Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Void seta o service manager
     */
    public function __construct()
    {
        self::$config = AdapterFactory::$config;
    }

    public static function somenteNumero($strValue)
    {
        return preg_replace('/\D/', '', $strValue);;
    }

    /**
     * @param $strData
     * @return false|string
     *
     * Converte data do formato dd/mm/aaaa 'brasileiro' PARA aaaa-mm-dd 'database'
     */
    public static function dataParaBanco($strData)
    {
        return date_format(date_create_from_format('d/m/Y', $strData), "Y-m-d");
    }

    public static function noSpecialChars($string)
    {
        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');

        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

        // devolver a string
        return str_replace($what, $by, $string);
    }

    public static function sg2uf($sg){
        switch ($sg) {
            case 'AC'; $uf = 'Acre'; break;
            case 'AL'; $uf = 'Alagoas'; break;
            case 'AM'; $uf = 'Amazonas'; break;
            case 'AP'; $uf = 'Amapá'; break;
            case 'BA'; $uf = 'Bahia'; break;
            case 'CE'; $uf = 'Ceará'; break;
            case 'DF'; $uf = 'Distrito Federal'; break;
            case 'ES'; $uf = 'Espírito Santo'; break;
            case 'GO'; $uf = 'Goiás'; break;
            case 'MA'; $uf = 'Maranhão'; break;
            case 'MG'; $uf = 'Minas Gerais'; break;
            case 'MS'; $uf = 'Mato Grosso do Sul'; break;
            case 'MT'; $uf = 'Mato Grosso'; break;
            case 'PA'; $uf = 'Pará'; break;
            case 'PB'; $uf = 'Paraíba'; break;
            case 'PE'; $uf = 'Pernambuco'; break;
            case 'PI'; $uf = 'Piauí'; break;
            case 'PR'; $uf = 'Paraná'; break;
            case 'RJ'; $uf = 'Rio de Janeiro'; break;
            case 'RN'; $uf = 'Rio Grande do Norte'; break;
            case 'RO'; $uf = 'Rondônia'; break;
            case 'RR'; $uf = 'Roraima'; break;
            case 'RS'; $uf = 'Rio Grande do Sul'; break;
            case 'SC'; $uf = 'Santa Catarina'; break;
            case 'SE'; $uf = 'Sergipe'; break;
            case 'SP'; $uf = 'São Paulo'; break;
            case 'TO'; $uf = 'Tocantins'; break;
        }
        return $uf;
    }

    public function formataTelefone($numero)
    {
        $numero = str_replace('+55', '', $numero);
        $numero = str_replace('+', '', $numero);

        if (strlen($numero) == 10) {
            $novo = substr_replace($numero, '(', 0, 0);
            $novo = substr_replace($novo, '9', 3, 0);
            $novo = substr_replace($novo, ')', 3, 0);
        } else {
            $novo = substr_replace($numero, '(', 0, 0);
            $novo = substr_replace($novo, ')', 3, 0);
        }
        return $novo;
    }

    public function trataTelefone($telefone)
    {
        $tel = preg_replace('#[^0-9]#', '', $telefone);
        $num['ddd'] = \substr($tel, 0, 2);
        $num['tel'] = \substr($tel, 2, 14);
        return $num;
    }

    public function array2table($array)
    {
        $html = "";
        // data rows
        $html .= '<tr>';
        foreach ($array as $key2 => $value2) {
            $html .= '<td>' . htmlspecialchars($value2) . '</td>';
        }
        $html .= '</tr>';
        return $html;
    }

    public function removeArrayDuplicado($array)
    {
        return array_filter(array_map("unserialize", array_unique(array_map("serialize", $array))));
    }

    public function stringEmLinha($string)
    {
        return preg_replace("/\r|\n/", "", $string);
    }

}
