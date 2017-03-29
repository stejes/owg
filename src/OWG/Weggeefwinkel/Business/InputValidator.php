<?php

//Business/InputValidator.php

namespace OWG\Weggeefwinkel\Business;

class InputValidator {

    public function firstNameValidator($firstName) {
        if (empty($firstName)) {
            $error = "Voornaam is verplicht";
        } else {
            $error = null;
        }

        return $error;
    }

    public function nameValidator($name) {
        if (empty($name)) {
            $error = "Naam is verplicht";
        } else {
            $error = null;
        }

        return $error;
    }

    public function emailValidator($email) {
        if (empty($email)) {
            $error = "E-mailadres is verplicht";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Ongeldig e-mailadres";
        } else {

            $userSvc = new UserService();
            $emailCheck = $userSvc->checkEmail(htmlentities($email, ENT_QUOTES, "UTF-8"));

            if ($emailCheck) {
                $error = "Er bestaat al een account met dit e-mailadres";
            } else {
                $error = null;
            }
        }

        return $error;
    }

    public function emailValidatorLogin($email) {
        if (empty($email)) {
            $error = "E-mailadres is verplicht";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Ongeldig e-mailadres";
        } else {
            $error = null;
        }

        return $error;
    }

    public function passwordValidator($password) {
        if (empty($password)) {
            $error = "Wachtwoord is verplicht";
        } elseif (strlen($password) < 12) {
            $error = "Wachtwoord moet minstens 12 karakters bevatten";
        } elseif (strlen($password) > 4096) {
            $error = "Wachtwoord mag maximum 4.096 karakters bevatten";
        } else {
            $error = null;
        }

        return $error;
    }

    public function repeatPasswordValidator($password, $password2) {
        if ($password != $password2) {
            $error = "Wachtwoorden komen niet overeen";
        } else {
            $error = null;
        }

        return $error;
    }

    public function notEmptyValidator($input) {
        if (empty($input)) {
            $error = "Gelieve alle verplichte velden in te vullen";
        } else {
            $error = null;
        }

        return $error;
    }

    public function alphaOnlyValidator($input) {
        if (preg_match('/[^a-zA-Z]+/', $your_string, $matches)) {
           $error = "Enkel lettertekens toegestaan";
        }
        else{
            $error = null;
        }
        
        return $error;
    }

}
