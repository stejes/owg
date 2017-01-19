<?php

/* include_once 'DBConfig.php';
  include_once 'entities/User.php'; */

namespace OWG\Weggeefwinkel\Data;

use OWG\Weggeefwinkel\Data\DBConfig;
use OWG\Weggeefwinkel\Entities\User;
use OWG\Weggeefwinkel\Exceptions\UsernameExistsException;
use PDO;

class UserDAO {

    public function isValidUser($username, $password) {
        $sql = "select id from users where username = :username and password = :password";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':username' => $username, ':password' => $password));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        print_r($rij);
        if ($rij) {

            return $rij["id"];
        }
        return false;
    }

    public function getById($id) {
        $sql = "select id, username, city_id, email, password from users where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $cityDAO = new CityDAO();
        $city = $cityDAO->getById($rij["city_id"]);
        $user = User::create($rij["id"], $rij["username"], $city, $rij["email"], $rij["password"]);
        $dbh = null;
        return $user;
    }

    public function create($username, $cityId, $password) {
        //$existingUser = $this->getByUsername($username);
        /*if (!is_null($existingUser)) {
            throw new UsernameExistsException();
        }*/

        $sql = "insert into users (username, password, city_id) values (:username, :password, :city)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':username' => $username, ':password' => $password, ":city" => $cityId));
        $userId = $dbh->lastInsertId();
        $dbh = null;
        return $userId;
    }

    public function getByUsername($username) {
        $sql = "select id, username, city_id, email, password from users where username = :username";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':username' => $username));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        if (!$rij) {

            return null;
        }
        $cityDAO = new CityDAO();
        $city = $cityDAO->getById($rij["city_id"]);
        $user = User::create($rij["id"], $rij["username"], $city, $rij["email"], $rij["password"]);
        /* print_r($user);
          print 'id: ' . $user->getId(); */
        return $user;
    }

    public function update($user) {
        $sql = "update users set email = :email, city_id = :city, password = :password where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);

        $stmt->execute(array(":email" => $user->getEmail(), ":city" => $user->getCity()->getId(), ":password" => $user->getPassword(), ":id" => $user->getId()));
        $dbh = null;
    }

}
