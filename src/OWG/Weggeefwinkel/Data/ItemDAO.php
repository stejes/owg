<?php

namespace OWG\Weggeefwinkel\Data;

use OWG\Weggeefwinkel\Entities\Item;
use OWG\Weggeefwinkel\Entities\Section;
use OWG\Weggeefwinkel\Entities\City;
use OWG\Weggeefwinkel\Entities\User;
use PDO;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemDAO
 *
 * @author steven.jespers
 */
class ItemDAO {

    public function getLast() {
        $sql = "select items.id, items.title, description, user_id, users.username, password, email, img, postcode, section_id, sections.name as sectionname, date, city_id, cities.name as cityname from items, users, cities, sections where items.section_id = sections.id and user_id = users.id and city_id = cities.id and items.date in(select max(date) from items group by section_id)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        /* print $section;
          print "in dao, titel = " . $rij["title"]; */
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $section = Section::create($rij["section_id"], $rij["sectionname"]);
            $city = City::create($rij["city_id"], $rij["postcode"], $rij["cityname"]);
            $user = User::create($rij["user_id"], $rij["username"], $city, $rij["email"], $rij["password"]);
            $item = Item::create($rij["id"], $rij["title"], $rij["description"], $rij["img"], $rij["date"], $user, $section);
            array_push($lijst, $item);
            //print_r($item);
        }

        $dbh = null;
        return $lijst;
    }

    public function getByUser($username) {
        $sql = "select items.id as id, title, description, img, section_id, sections.name as sectionname, date, city_id, cities.name as cityname, cities.postcode, user_id, users.username, email, password from items, sections, users, cities where section_id = sections.id and user_id = users.id and cities.id = city_id and users.username = :username order by section_id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':username' => $username));
        $lijst = array();
        while ($rij = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section = Section::create($rij["section_id"], $rij["sectionname"]);
            $city = City::create($rij["city_id"], $rij["postcode"], $rij["cityname"]);
            $user = User::create($rij["user_id"], $rij["username"], $city, $rij["email"], $rij["password"]);
            $item = Item::create($rij["id"], $rij["title"], $rij["description"], $rij["img"], $rij["date"], $user, $section);
            array_push($lijst, $item);
        }
        $dbh = null;
        return $lijst;
    }

    public function getById($id) {
        $sql = "select items.id as id, title, section_id, sections.name as sectionname, city_id, cities.postcode, cities.name as cityname, img, description, date, user_id, username, email, password from items, users, cities, sections where user_id = users.id and section_id = sections.id and city_id = cities.id and items.id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $section = Section::create($rij["section_id"], $rij["sectionname"]);
        $city = City::create($rij["city_id"], $rij["postcode"], $rij["cityname"]);
        $user = User::create($rij["user_id"], $rij["username"], $city, $rij["email"], $rij["password"]);
        $item = Item::create($rij["id"], $rij["title"], $rij["description"], $rij["img"], $rij["date"], $user, $section);

//print_r($item);
        $dbh = null;
        return $item;
    }

    public function getByConditions($keywordArray, $postcode, $section) {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);

        $sql = "select items.id as id, title, section_id, sections.name as sectionname, city_id, cities.postcode, cities.name as cityname, img, description, date, user_id, username, email, password from items, users, cities, sections where user_id = users.id and section_id = sections.id and city_id = cities.id";
        foreach ($keywordArray as $keyword) {
            $sql .= " and (description LIKE ? or title LIKE ?)";
        }
        if ($postcode != "") {
            $sql .= " and postcode = ?";
        } else {
            $sql .= " and ? is not null";
        }
        if ($section != 0) {
            $sql .= " and section_id = ?";
        } else {
            $sql .= " and ? is not null";
        }
        //print_r($postcode);
        $stmt = $dbh->prepare($sql);
        $j = 1;
        for ($i = 0; $i < sizeof($keywordArray); $i++) {
            //print("ja");
            $stmt->bindValue($j, '%' . $keywordArray[$i] . '%');
            $j++;
            $stmt->bindValue($j, '%' . $keywordArray[$i] . '%');
            $j++;
        }
        $stmt->bindValue($j, $postcode);
        $j++;
        $stmt->bindValue($j, $section);
        //print_r($stmt);


        $stmt->execute();
        //print "sql: " . $sql;
        $lijst = array();
        while ($rij = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section = Section::create($rij["section_id"], $rij["sectionname"]);
            $city = City::create($rij["city_id"], $rij["postcode"], $rij["cityname"]);
            $user = User::create($rij["user_id"], $rij["username"], $city, $rij["email"], $rij["password"]);
            $item = Item::create($rij["id"], $rij["title"], $rij["description"], $rij["img"], $rij["date"], $user, $section);
            array_push($lijst, $item);
        }
        return $lijst;
    }

    public function update($item) {
        $sql = "update items set title = :title, description = :description, img = :img, section_id = :section where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $item->getId(), ":title" => $item->getTitle(), ":description" => $item->getDescription(), ":img" => $item->getImg(), ":section" => $item->getSection()->getId()));
    }

    public function create($title, $description, $img, $sectionId, $userId) {
        $sql = "insert into items (title, description, img, section_id, user_id, date) values (:title, :description, :img, :section, :user, :date)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $date = date('Y-m-d H:i:s');
        //print $date;
        $stmt->execute(array(":title" => $title, ":description" => $description, ":img" => $img, ":section" => $sectionId, ":user" => $userId, ":date" => $date));
        $itemId = $dbh->lastInsertId();
        $dbh = null;
        $sectionDAO = new SectionDAO();
        $section = $sectionDAO->getById($sectionId);
        $userDAO = new UserDAO();
        $user = $userDAO->getById($userId);
        $item = Item::create($itemId, $title, $description, $img, $date, $user, $section);
        return $item;
    }

    public function delete($id) {
        $sql = "delete from items where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(":id" => $id));
        $dbh = null;
    }

}
