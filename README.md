# Proxy SMS API Wrapper

Простой прокси-сервис, оборачивающий API для получения номеров и SMS. Сделан как тестовое задание для Tiger Technology.

## Методы API

- `getNumber` — получить номер  
- `getSms` — получить SMS  
- `cancelNumber` — отменить номер  
- `getStatus` — статус активации  

## Что реализовано

- Ограничение количества запросов: 300 в минуту  
- Ретраи при ошибках  
- Корректная обработка исключений  
- Комментарии по использованию Redis  
- Валидация всех входных параметров  
  - Проверка всех полей  
  - Понятные сообщения об ошибках  
- Использован `FormRequest` для валидации  
- Сервисный слой для бизнес-логики  
- Middleware для ограничения запросов  
- Логирование всех обращений к внешнему API  
- Исправлен SSL для локального запуска  

## Запуск проекта

```bash
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan serve
