## Introdução

Peach proporciona uma experiência de desenvolvimento local baseada em Docker. Nenhum software ou biblioteca precisa ser instalado localmente antes de utilizar o Peach. A CLI simples do Peach permite que você comece a construir seu aplicativo sem qualquer experiência anterior com o Docker.

#### Inspiração

Peach é inspirado e derivado do Sail, criado por Taylor Otwell. Para mais informações, confira o repositório do [Sail](https://github.com/laravel/sail).

## Documentação Oficial

##### Instale o Peach utilizando o Composer:

```shell
composer require ysocode/peach --dev
```

##### Configure os serviços para o Peach usando o Gerenciador Basket:

```shell
./vendor/bin/basket peach:install
```

##### Modifique os serviços existente do Peach usando o Gerenciador Basket:

```shell
./vendor/bin/basket peach:add
```

##### Configurando um Alias de Shell:

Por padrão, os comandos do Peach são invocados usando o script **vendor/bin/peach**
```shell
./vendor/bin/peach up
```

Entretanto, em vez de digitar repetidamente vendor/bin/peach para executar comandos do Peach, você pode desejar configurar um alias de shell que permita executar os comandos do Peach de forma mais fácil:
```shell
alias peach="[ -f peach ] && sh peach || sh vendor/bin/peach"
```

##### Iniciando e Parando o Peach:

Antes de iniciar o Peach, certifique-se de que nenhum outro servidor web ou banco de dados esteja em execução em seu computador local. Para iniciar todos os contêineres Docker definidos no arquivo docker-compose.yml da sua aplicação, execute o comando up:
```shell
peach up
```

Para iniciar todos os contêineres Docker em segundo plano, você pode iniciar o Peach no modo "detached":
```shell
peach up -d
```

Depois que os contêineres da aplicação forem iniciados, você poderá acessar o projeto em seu navegador da web pelo endereço: http://localhost.


Para parar todos os contêineres, você pode pressionar Control + C para interromper a execução do contêiner. Se os contêineres estiverem em execução em segundo plano, você pode usar o comando **stop**:
```shell
peach stop
```

Para voltar a executar os contêineres você pode usar o comando **start**:
```shell
peach start
```

Para parar e remover todos os contêineres você pode usar o comando **down**:
```shell
peach down
```

## Licença

Peach is open-sourced software licensed under the [MIT license](LICENSE.md).
