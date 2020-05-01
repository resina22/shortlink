## Descrição
Projeto gera ShortLink e registra o acesso ao mesmo

## Requisitos  
- Docker - [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)
- Docker compose - [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)  

Para realizar as configurações das variáveis de ambiente renomeie o arquivo `.env.example` para `.env`  

## Iniciando aplicação
- Na pasta docker execute o comando `docker-compose up --build -d`
- Aguarde o composer instalar as dependências do projeto, para visualizar o log execute `docker logs -f composer`
- Execute as migrations para criar a estrutura do banco de dados `docker-compose exec php php ../../artisan migrate` (deve esta na pasta docker)
 
## Consumindo API
- Defina a URL como `http://localhost/graphql/link`
## ShortLink
#### Criar
- Short dinâmico
```json
mutation {
    createShortLink(target: "http://www.google.com/" ) {
    id, short, target, short_link }
}
```
- Especificar short
```json
mutation {
    createShortLink(target: "http://www.google.com/", short: "teste" ) {
        id, short, target, short_link
    } 
}
```
#### Listar
- Todos os registros
```json
{
    links{
        id, target, short, protocol, short_link, created_at, updated_at
    }
}
```
- Buscar pelo short
```json
{
    link(short: "teste") {
        id, target, short, protocol, short_link, created_at, updated_at
    }
}
```
## Redirecionamento
Ao acessar o ShortLink sera redirecionado para o link original

- Lista todos os redirecionamentos 
```json
{
    hits {
        id, user_agent, short, valid, created_at, user_agent
    }
}
```

- Busca o redirecionamento pelo short
```json
{
    hit(short: "teste") {
        id, user_agent, short, valid, created_at, user_agent
    }
}
```

## Testes
Execute o comando `docker-compose exec php ../../vendor/bin/phpunit ../../tests` (deve esta na pasta docker)
## Possível problema
`Access denied` na pasta monolog
- No diretório raiz do projeto `shortlinks` execute o comando `chmod -R 777 storage`
