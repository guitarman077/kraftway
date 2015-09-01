-- Вывод пользователей и их гео данных
-- прим. т.к как нет условия надо ли выводить юзеров не имеющих гео данных и естьли и могут ли быть ли быть таковые вообще используем оператор JOIN 
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

-- Топ 5 стран