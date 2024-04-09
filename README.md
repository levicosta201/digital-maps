
# Digital Maps
Digital Maps é um projeto que tem como objetivo criar um sistema de cadastro de usuários e locais, onde o usuário pode cadastrar locais e visualizar locais cadastrados por outros usuários. Foi escolhido a utilização da linguagem PHP com o framework Laravel, por ser um framework robusto e com uma comunidade ativa, além de ser um framework que possui uma curva de aprendizado baixa. A arquitetura do projeto foi pensada para ser escalável e de fácil manutenção, utilizando os princípios do SOLID e DDD.

## Run Services

Para fazer o deploy desse projeto é necessário ter o Docker e o docker-compose rodando na máquina e seguir os passos abaixo.

Clone o projeto

```bash
  git clone git@github.com:levicosta201/digital-maps.git
```

Entre no diretório do projeto

```bash
  cd digital-maps
```

Rode o comando para realizar o build

```bash
  make build
```

E após isso, rode o comando para criar as tabelas

```bash
  make migrate
```

As vezes pode ser necessário rodar os comandos abaixo, para dar
permissão de escrita nas pastas

```bash
  sudo chmod 777 -R storage
```

```bash
  sudo chmod 777 -R bootstrap/cache
```

Caso o arquivo .env não tenha sido criado, crie um arquivo .env e copie o conteúdo do arquivo .env.example para ele

```bash
  cp .env.example .env
``` 

## Rodando os testes

Para rodar os testes, rode o seguinte comando

```bash
  make test
```
Caso deseje gerar automaticamente dados para os testes, rode o comando abaixo

```bash
  make seed
```

Para acessar o coverage e visualizar a coberturas do testes unitários, basta acessar **reports/index.html**


# Documentação da Api
Para localizar a documentação da api, você pode, após subir o projeto, acessar a url **/swagger** e encontratrá todos os endpoints livres para realizar requisições.



## Autor

- [@levicosta201](https://github.com/levicosta201/)




