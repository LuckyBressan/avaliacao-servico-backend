# Back-end: Avaliação Serviço

Parte back-end do projeto de **Avaliação de Serviço**

Este projeto foi construído com:

* PHP

Para rodar o projeto basta copiar as pastas para dentro da pasta que seu servidor apache está monitorando e o front-end se encarregará de realizar as devidas requisições

Para as tabelas e dados previamente povoados você deve executar o SQL que está [neste arquivo](./src/db/schema.sql) no seu pgadmin

Para que o PHP possa se conectar corretamente com o banco, é necessário que na classe `Connection.php` seja alterado os dados do construct para corresponderem com sua configuração de banco.