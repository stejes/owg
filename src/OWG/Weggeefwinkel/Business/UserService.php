<?php

//include_once 'data/UserDAO.php';

namespace OWG\Weggeefwinkel\Business;

use OWG\Weggeefwinkel\Data\UserDAO;
use OWG\Weggeefwinkel\Data\CityDAO;
//use OWG\Weggeefwinkel\Entities\User;
use OWG\Weggeefwinkel\Exceptions\UsernameExistsException;
use OWG\Weggeefwinkel\Exceptions\InvalidCityException;

class UserService {

    public function checkLogin($username, $password) {
        if ($username == "" || $password == "") {
            return false;
        }
        $userDao = new UserDAO();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $user = $userDao->getByUsername($username);

        if ($user != null && password_verify($password, $user->getPassword())) {
            return $user;
        } else {
            return false;
        }
    }

    public function registerUser($username, $password, $password2, $cityId) {

        //check of username al bestaat
        $userDao = new UserDAO();
        $user = $userDao->getByUsername($username);
        if ($user != null) {
            throw new UsernameExistsException();
        }

        //check of het opgegeven cityId geldig is
        if (!ctype_digit($cityId)) {
            throw new InvalidCityException();
        } else {
            $cityDao = new CityDAO();
            $city = $cityDao->getById($cityId);
            if ($city == null) {
                throw new InvalidCityException();
            }
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $newId = $userDao->create($username, $cityId, $hash);
        return $newId;
        
    }

    public function getByUsername($username) {
        $userDAO = new UserDAO();
        return $userDAO->getByUsername($username);
    }

    public function getByEmail($email) {
        $userDAO = new UserDAO();
        return $userDAO->getByEmail($email);
    }

    public function update($email, $cityId) {
        $userDao = new UserDAO();
        $user = $userDao->getByUsername($_SESSION["username"]);
        $cityDao = new CityDAO();
        $city = $cityDao->getById($cityId);
        $user->setEmail($email);
        $user->setCity($city);
        $userDao->update($user);
    }

    public function updatePass($oldPass, $pass, $pass2) {
        $userDao = new UserDAO();
        $user = $userDao->getByUsername($_SESSION["username"]);
        //print_r($user);
        $oldHash = password_hash($oldPass, PASSWORD_DEFAULT);
        if ($oldHash == $user->getPassword()) {


            if ($pass == $pass2) {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $user->setPassword($hash);
                //print_r($user);
                $userDao->update($user);
            }
        }
    }

}
