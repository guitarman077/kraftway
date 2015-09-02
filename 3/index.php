<?

/*
 * Вопрос: каким образом можно реализовать фильтрацию (по стране, региону, городу) и пагинацию, какие компоненты будут задействованы?   
 * 
 * Во вьюхе нам понатобится форма с 3 полями типа select c аттрибутами name равными id_city, id_region, id_country  в эти select-поля 
 * мы и будем вводить параметры (город, регион, страну) по которым мы и будем осуществлять фильтрацию.
 * При сабмите id выбранных нами города, региона, страны отправятся в контроллер где мы и сможем осуществить фильтрацию    
 * 
 */

// пример обработки переданных для фильтрации параметров в котроллере
// Абстрактный пример приведен на базе ZendFramework в связке с Doctrine ORM

/** @var Doctrine_Query $query */
$query = Doctrine_Query::create()
    ->select('u.*')
    ->from('User u, u.Location l');

if ($id_city = $this->_getParam('id_city', false)) { // если выбран город уже нет смысла фильтровать по региону или стране
    $query->addWhere('l.id_city = ?', $id_city);
} elseif ($id_region = $this->_getParam('id_region', false)) {
    $query->addWhere('l.id_region = ?', $id_region);
} elseif($id_country = $this->getParam('id_country', false)) {
    $query->addWhere('l.id_country = ?', $id_country);
}

// пагинацию удобно осуществить при помощи специально написанного класса Shared_Paginator
$this->view->list = Shared_Paginator::execute($query);