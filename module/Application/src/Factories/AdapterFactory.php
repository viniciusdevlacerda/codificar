<?php

namespace Application\Factories;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class AdapterFactory
{

    /**
     *
     * @var Array - array de configuração da aplicação
     * @example /config/autoload/global.php
     */
    public static $config;

    /**
     *
     * @param Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Void seta o service manager
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        self::$config = $serviceLocator->get('Config');
    }

    /**
     *
     * @param String $AdapterName nome do adaptador de banco de dados
     * @return Zend\Db\Adapter\AdapterAdapter retorna a estancia do
     * banco de dados caso não exista a estancia  retorna a mensagem
     * "The adapter '$AdapterName' or was not found"
     * @throws \Exception
     */
    public static function getAdapter($AdapterName)
    {
        if (!array_key_exists($AdapterName, self::$config['db']['adapters'])):
            throw new \Exception("The adapter '$AdapterName'  or was not found");
        endif;
        return new Adapter(self::$config['db']['adapters'][$AdapterName]);
    }

}
