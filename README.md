# Сервис работы с курсами обмена валют для биткоина

Этот демо проект разработан на Yii2 Basic (php 8.2) и использует Docker для контейнеризации.
Взаимодействие осуществляется через JSON API.

## Как запустить проект

Чтобы запустить проект, выполните следующие шаги:

1. **Установите Docker**: Если у вас еще нет Docker, установите его, следуя официальным инструкциям на [сайте Docker](https://docs.docker.com/get-docker/).

2. **Установите Composer**: Если у вас еще нет Composer, установите его, следуя инструкциям на [сайте Composer](https://getcomposer.org/download/).

3. **Пропишите переменные окружения** Небходимо создать файл .env скопировав его из .env.prod. В файле .env необходимо указать значения перменных.

4. **Запустите контейнеры Docker**:
   - Склонируйте репозиторий проекта на свой компьютер.
   - Перейдите в корневую папку проекта и выполните следующую команду для запуска контейнеров Docker:

   ```bash
   docker-compose up -d
   ```

5. **Установите зависимости PHP**:
   - Выполните команду установки зависимостей PHP с помощью Composer:

   ```bash
   composer install
   ```

6. **Выполните миграции**:
   - В контейнере `btc-currency-php` выполните команду миграции для создания таблицы с юзерами:

   ```bash
   php yii migrate
   ```
7. **Прописать алиас сайта**:
   - В файле /etc/hosts (c:\windows\system32\drivers\etc\hosts в случае Windows) прописать:

    ```bash
   127.0.0.1	local.btc-currency.ru
   ```

8. **Работа с сайтом**:
   - Теперь вы можете работать с сайтом, он доступен по адресу [http://local.btc-currency.ru/](http://local.btc-currency.ru/).
   - Работа с API доступна по адресу [http://local.btc-currency.ru/api/v1?method=<method>&parameter=<parameter>](http://local.btc-currency.ru/api/v1?method=<method>&parameter=<parameter>)
   - В таблице users создан пользователь с Bearer токеном ```pK5GkLAxQnY_3EujeayyOPt6ifgFRDa-UzyZOe.jFWTzA8wP1yyDDM3g0jVGwkQx```. Его нужно использовать для работы с API

9. **Примеры запросов**:
   - GET http://local.btc-currency.ru/api/v1?method=rates&parameter=USD,RUB,EUR выведет текущий курс покупки BTC для выбранных валют в порядке возрастания
   - POST http://local.btc-currency.ru/api/v1?method=convert с телом запроса currency_from: USD, currency_to: BTC, value: 1 выведет количество BTC, которое получится приобрести по текущему курсу и текущий курс, по которому осуществлялась операция конвертирования

Теперь проект запущен и готов к использованию!

## Лицензия

Этот проект распространяется под лицензией MIT.

## Задача по MySQL

Имеется три таблицы:
- users (id, first_name, last_name, birthday)
- books (id,name,author)
- user_books (id,user_id,book_id,get_date,return_date)

Необходимо написать запрос выборки данных из представленных таблиц, который найдет и выведет всех посетителей библиотеки, возраст которых попадает в диапазон от 7 и до 17 лет, которые  взяли две книги одного автора (взяли всего 2 книги и они одного автора), книги были у них в руках не более двух календарных недель (не просрочили 2-х недельный срок пользования).

Формат вывода:
- ID, Name (first_name  last_name), Author, Books (Book 1, Book 2, ...)

Запрос:

```mysql
SELECT 
  ub.user_id as ID, 
  CONCAT(u.first_name, ' ', u.last_name) AS Name, 
  b.author AS Author, 
  GROUP_CONCAT(b.name) AS Books 
FROM 
  users u 
  INNER JOIN user_books ub ON u.id = ub.user_id 
  INNER JOIN books b ON ub.book_id = b.id 
WHERE 
  TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) BETWEEN 7 AND 17 
  AND DATEDIFF(ub.return_date, ub.get_date) <= 14 
GROUP BY 
  u.id, 
  b.author 
HAVING 
  COUNT(*) = 2 AND COUNT(b.author) = 1;

```