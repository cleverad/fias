Fias
====

[![Latest Stable Version](https://poser.pugx.org/marvin255/fias/v/stable.png)](https://packagist.org/packages/marvin255/fias)
[![License](https://poser.pugx.org/marvin255/fias/license.svg)](https://packagist.org/packages/marvin255/fias)
[![Build Status](https://travis-ci.org/marvin255/fias.svg?branch=master)](https://travis-ci.org/marvin255/fias)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/marvin255/fias/badges/quality-score.png?b=feature%2F2.0)](https://scrutinizer-ci.com/g/marvin255/fias/?branch=feature%2F2.0)

Набор утилит на php для развертывания и обновления [ФИАС](http://fias.nalog.ru/).



Как использовать
----------------

В папке staging настроен vagrant со всеми необходимыми библиотеками.

Также показан пример консольного приложения, в котором есть команды установки и обновления.

Настройки для скриптов можно изменять в файле `staging/.conf.yaml`.

Алгоритм для проверки:

1. `cd staging/mysql`,
2. `vagrant up --provision`,
3. `vagrant ssh`,
4. `php /var/app/staging/app fias:install /var/app/staging/.conf.yaml` для установки полного ФИАС,
5. `php /var/app/staging/app fias:update 123 /var/app/staging/.conf.yaml` для установки указанной дельты ФИАС,
