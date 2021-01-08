<?php

define('VERSION', 837); // версия сайта /для обновления кэша

/*Части ножа для получения path*/
define('PATH_BLADE_GET_ID', 1);
define('PATH_BOLSTER_GET_ID', 2);
define('PATH_HANDLE_GET_ID', 3);

/*Части ножа отвечающие за цвет*/
define('COLOR_STEEL_ID', 1);
define('COLOR_HANDLE_ID', 2);

/*Порядковый номер частей ножа в конструкторе*/
define('STEEL_ID', 1);
define('BLADE_ID', 2);
define('BOLSTER_ID', 3);
define('HANDLE_ID', 4);
define('HANDLE_MATERIAL_ID', 5);
define('ADDITION_ID', 6);
define('SPUSK_ID', 7);

/*Типы заказов*/
define('CONSTRUCT_ORDER', 1);
define('IMAGE_ORDER', 2);
define('CART_ORDER', 3);
define('ALL_ACTIVE_ORDERS', 4);

/*статусы пользователей системы*/
define('CUSTOMER', 1);
define('MASTER', 2);
define('MAIN_MASTER', 3);
define('OPERATOR', 4);
define('MAIN_OPERATOR', 5);
define('ADMIN', 9);

/*Статусы заказов*/
define('ENTERED', 1);
define('CONFIRMED', 2);
define('CONVERSATION', 3);
define('MADE', 4);
define('READY', 5);
define('PENDING', 6);
define('SENT', 7);
define('ONTHEPOSTOFFICE', 8);
define('DELIVERED', 9);
define('REFUSED', 10);
define('DONE', 11);

/*Статусы сообщений*/
define('SIMPLE_MESSAGE', 1);
define('STATUS_CHANGED_MESSAGE', 2);
define('MASTER_CHANGED_MESSAGE', 3);
define('SUM_CHANGED_MESSAGE', 4);
define('PAYED_MESSAGE', 5);

/*На линии заказа*/
define('NOT_ONLINE', 0);
define('ONLINE', 1);

/*Статусы ножей*/
define('AVAILABLE', 1);
define('IN_ORDER', 2);
define('SELLED', 3);
define('NOT_AVAILABLE', 4);
define('IN_REFUSED', 5);

/*Статусы аккаунтов*/
define('ACTIVE', 1); // аккаунт активен
define('DELETED', 2); // аккаунт удален

/*Разрешения на смс оповещения*/
define('SEND_SMS', 1); // разрешено
define('NO_SMS', 2); // не разрешено

/*Коды ошибок*/
define('ACCESS_ERROR', 99); // ошибка доступа
define('UNAUTH_ERROR', 98); // ошибка отсутствия авторизации
define('CSRF_NOT_VALID', 97); // невалиден токен
define('KNIFE_IN_ORDER', 96); // нож в заказе
define('WRONG_LOGIN_PASSWORD', 95); // не верный логин пароль
define('UNCHANGE_ERROR', 94); // нельзя менять заказ
define('ALREADY_BUYED', 93); // нож уже в другом заказе


define('WAIT_LOGIN_TIME', 12); //секунды сколько ждать авторизации если не верный пароль
define('RESET_TIME', 300); //секунды
define('RESET_TIME_EMAIL', 900); //секунды
define('RESET_LIMIT', 2); // лимит восстановления пароля в день

/*Типы оплаты*/
define('PAY_LATER', 1); //оплатить позже
//define('PAY_MAIL', 2);
define('PAY_CARD', 3); //оплата картой (сейчас значит -> оплатить сейчас полную сумму)
define('PAY_PERSENT', 4); //оплата картой (сейчас значит -> оплатить сейчас процент от заказа как залог)

/*Оплаченность*/
define('NOT_PAYED', 1);
define('PAYED', 2);

define('TIME_BEFORE_ERASE', 600); // время(сек) после которого стираются неоплаченные заказы сразу

define('START_WORK_DAYSHIFT', 8); // начало рабочего дня
define('END_WORK_DAYSHIFT', 18); // конец рабочего дня

define('PERSENT', 30); // процент предоплаты
define('WITHOUT_PAY', 3000); // цена изделий изготавливаемых без предоплаты
define('COMPANY_PHONE', '+7 (925) 195-59-78'); // цена изделий изготавливаемых без предоплаты
