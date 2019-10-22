# Cidadão de Olho (Monitoramento público estadual de gastos em verbas indenizatórias)

### Intro
O produto em si consulta um WebService com dados abertos sobre os deputados disponibilizados na url: http://dadosabertos.almg.gov.br/ws/ajuda/sobre, onde é dados relacionados as movimentações financeiras, legislativas, representações partidárias etc de cada deputado elegido do estado de Minas Gerais. Onde o objetivo final é apresentar os gastos em verbas indenizatórias por parte dos deputados.

### Getting Started

Para iniciar a instalação é necessário:
  
  * XAMPP 
      * Download em: https://www.apachefriends.org/pt_br/index.html
      * Configurar VHost como descrito abaixo (C:\xammp\apache\conf\extra\httpd-vhosts.conf):
      
        ```
        <VirtualHost codificar:80>
           DocumentRoot "Caminho do diretorio onde for salva a aplicacao (Ex.:C:\Projects\codificar\public)"
           ServerName codificar
	         ErrorLog "logs/dummy-host2.example.com-error.log"
           CustomLog "logs/dummy-host2.example.com-access.log" common
         <Directory "Caminho do diretorio onde for salva a aplicacao (Ex.:C:\Projects\codificar\public)">
            Options Indexes FollowSymLinks Includes ExecCGI
            AllowOverride All
            Require all granted
          </Directory>
        </VirtualHost>
        ```
         * Obs.: O diretório deve ser aplicado apontando para a pasta public como no exemplo.
         
      * No Windows:
          Ir no arquivo: C:\Windows\System32\drivers\etc\hosts
          
          * Aplicar as seguintes configurações:
          
          ```# Copyright (c) 1993-2009 Microsoft Corp.
          #
          # This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
          #
          # This file contains the mappings of IP addresses to host names. Each
          # entry should be kept on an individual line. The IP address should
          # be placed in the first column followed by the corresponding host name.
          # The IP address and the host name should be separated by at least one
          # space.
          #
          # Additionally, comments (such as these) may be inserted on individual
          # lines or following the machine name denoted by a '#' symbol.
          #
          # For example:
          #
          #      102.54.94.97     rhino.acme.com          # source server
          #       38.25.63.10     x.acme.com              # x client host

          # localhost name resolution is handled within DNS itself.
          #	127.0.0.1       localhost
          #	::1             localhost
          127.0.0.1       codificar
          
        
  * ZendFramework 3 
      * Instalação com composer no Windows: https://getcomposer.org/doc/00-intro.md#installation-windows
      * Ao pedir arquivo PHP escolher o arquivo presente na pasta do XAMPP (Ex.:C:\xampp\php\php.exe)
      * Ao concluir a instalação, abrir pasta da aplicação e executar no CMD: `composer update`
      
  * Banco de Dados em MySql
      * Executar comandos que estão no arquivo: data/db/init.sql
      * Usar programa de preferencia
      * Deve ser executado com XAMPP obedecendo as seguintes configurações: 
        * host=localhost
        * user=root
        * password=''
   
