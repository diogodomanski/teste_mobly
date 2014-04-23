Teste Mobly
=======================

Introdução
------------
Projeto teste para empresa Mobly. Implementação simples de carrinho de compras.

Instalação
------------
1. Criar copia local do projeto
	git clone https://github.com/diogodomanski/teste_mobly.git
2. Instalar/atualizar módulos do projeto
	php composer.phar self-update
	php composer.phar update
3. Criar banco de dados (MySQL). Script encontra-se no arquivo BD.sql
4. Informar dados de acesso ao BD no arquivo /config/autoload/doctrine_orm.global.php (propriedade 'connection')
5. Configurar servidor de páginas para rodar o projeto (vide seção Configuração Servidor Páginas)
6. Garantir que o diretório /data/DoctrineORMModule/Proxy tenha permissão de escrita pelo usuário do servidor de páginas
	

Configuração Servidor Páginas
----------------

### Servidor PHP CLI

A forma mais simples de iniciar, se você estiver usando PHP 5.4 ou superior, é rodar o servidor PHP interno no diretório raiz:

    php -S 0.0.0.0:8080 -t public/ public/index.php

Isto irá iniciar o cli-server na porta 8080 e vincula-lo a todas as interfaces de rede.


### Servidor Apache

Outra alternativa é criar um VirtualHost no Apache, apontando para o diretório public/ do projeto. Esta configuração é algo semelhante ao seguinte:

    <VirtualHost *:80>
        ServerName teste_mobly.localhost
        DocumentRoot /path/to/teste_mobly/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/teste_mobly/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
