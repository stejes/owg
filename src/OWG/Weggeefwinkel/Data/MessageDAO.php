<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Data;
use OWG\Weggeefwinkel\Entities\Message;
use OWG\Weggeefwinkel\Data\UserDAO;
use PDO;

/**
 * Description of MessageDAO
 *
 * @author steven.jespers
 */
class MessageDAO {
    //put your code here
    
    public function create($message){
        $sql = "insert into messages(parent_id, title, text, timestamp, sender_id, receiver_id, deleted)"
                . "values (:parent_id, :title, :text, :timestamp, :sender_id, :receiver_id, false)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':parent_id'=>$message->getParentId(), ':title' => $message->getTitle(), ':text' => $message->getText(), ':timestamp' => $message->getDate()
                , ':sender_id' => $message->getSender()->getId(), ':receiver_id' => $message->getReceiver()->getId()));
        $messageId = $dbh->lastInsertId();
        $dbh=null;
        
        $sql = "insert into message_inbox(message_id, user_id, isSender, isRead)"
                . "values (:messageId, :user_id, false, false)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':messageId' => $messageId, ':user_id' => $message->getReceiver()->getId()));
        $dbh=null;
        $sql = "insert into message_inbox(message_id, user_id, isSender, isRead)"
                . "values (:messageId, :user_id, true, true)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':messageId' => $messageId, ':user_id' => $message->getSender()->getId()));
        $dbh=null;
        
    }
    
    public function getByUserId($id){
        $sql = "select message_id, isSender, isRead, parent_id, title, text, timestamp, isReply, sender_id, receiver_id, deleted"
                . " from message_inbox, messages where message_id = messages.id and user_id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        //print_r($stmt);
        $lijst = array();
        while($rij = $stmt->fetch(PDO::FETCH_ASSOC)){
            $userDao = new UserDAO();
            $sender = $userDao->getById($rij["sender_id"]);
            $receiver = $userDao->getById($rij["receiver_id"]);
            $message = new Message($rij["message_id"], $rij["title"], $rij["text"],
                    $sender, $receiver, $rij["timestamp"], $rij["parent_id"],
                    $rij["isReply"], $rij["isRead"], $rij["deleted"]);
            //print_r($message);
            array_push($lijst, $message);
        }
        $dbh=null;
        return $lijst;
    }
}
