-- Вывод пользователей и их гео данных
-- прим: т.к как нет условия надо ли выводить юзеров не имеющих гео данных и естьли и могут ли быть ли быть таковые вообще используем оператор JOIN 
SELECT 
  u.name,
  u.email, 
  c.country,
  r.region,
  ci.city
FROM User AS u 
JOIN Location AS l ON u.id = l.id_user
JOIN Country AS c ON l.id_country = c.id
JOIN Region AS r ON l.id_region = r.id
JOIN City AS ci ON l.id_city = ci.id

-- Топ 5 стран (по количеству житеелй)
-- прим: используем LEFT JOIN на случай если стран имеющих жителей буедт менее 5
SELECT
  c.country
FROM Country AS c 
LEFT JOIN Location AS l ON c.id = l.id_country  
GROUP BY l.id_country
ORDER BY COUNT(l.id) DESC, c.country -- сортируем по c.country чтобы страны с равным количеством жителей выводились по алфавиту 
LIMIT 5

-- Города в которых не проживают жители
-- прим: выбрал такой вариант так как работает во много раз выстрее чем варинт с GROUP BY ... HAVING COUNT(...) = 0
SELECT 
  c.city
FROM City AS c
LEFT JOIN Location AS l ON c.id = l.id_city
WHERE l.id IS NULL

