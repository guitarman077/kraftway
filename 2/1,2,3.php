<?php
/*
 * Для выполнения второй части задания будем использовать Doctrine ORM первой версии
 *
 * Для работы Doctrine ORM нам понадобятся классы моделей User, Location, Region, City, Country в которых прописаны соответствующие связки.
 * С учетом того что в рамках данного задание нами не предпологается наличие каких либо методов в моделей описывать выше перечисленные классы я счел излишним
 * особенно если учесть что Doctrine ORM может сгенерировать данные класс автоматически.
 */

// Вывод пользователей и их гео данных
Doctrine_Query::create()
    ->select('u.name, u.email, c.country, r.region, ci.city')
    ->from('User u, u.Location l, l.Country c, l.Region r, l.City ci')
    ->execute();

// Топ 5 стран (по количеству житеелй)
Doctrine_Query::create()
    ->select('c.country')
    ->from('Country c, c.Location l')
    ->groupBy('l.id_country')
    ->orderBy('COUNT(l.id)')
    ->limit(5)
    ->excute();

// Города в которых не проживают жители
Doctrine_Query::create()
    ->select('c.city')
    ->from('City c, c.Location l')
    ->where('l.id IS NULL')
    ->execute();
