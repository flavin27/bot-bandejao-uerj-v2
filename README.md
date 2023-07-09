# Bot do Cardápio do Restaurante da UERJ

Esse bot é um script em PHP que faz o scraping da página web do restaurante da UERJ para obter o cardápio diário e posta as informações no Twitter usando a API do Twitter. O bot foi configurado para rodar em um contêiner Docker para facilitar a implantação e a execução.

## Pré-requisitos:

- Docker instalado e configurado no seu ambiente.

## Instalação e Configuração:

1. Clone este repositório para o seu ambiente local:

```
git clone https://github.com/seu-usuario/bot-cardapio-uerj.git
```
2. Acesse o diretório do projeto:

```
cd <caminho do projeto>
```
3. Configure um .env com as chaves necessárias para usar a API

```
echo "<suas chaves>" > .env
```

4. Construa a imagem do Docker:

```
docker build -t <nome do seu projeto> . 
```

## Uso:

Para rodar o bot, execute o seguinte comando:

```
docker run --rm -it <nome do seu projeto>
```

## Contribuição:

Se você quiser contribuir para este projeto, sinta-se à vontade para abrir uma nova issue ou enviar um pull request.

## Licença:

Este projeto está licenciado sob a MIT License.