<?php

namespace Application\AbstractClass;

abstract class WebserviceAbstract
{

    public function request()
    {
        try {
            $service_url = $this->urlPadrao . $this->url;
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_URL, $service_url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);

            if ($curl_response === false):
                $err = curl_error($curl);
                curl_close($curl);
                throw new Zend_Exception('Erro na chamada do Serviço: ' . $err . '.');
            endif;

            $json = json_decode($curl_response, true);
            if (!$json):
                return ($curl_response);
            endif;
            return $json;
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
