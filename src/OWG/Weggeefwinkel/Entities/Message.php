<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 *
 * @author steven.jespers
 */

namespace OWG\Weggeefwinkel\Entities;

class Message {

    //put your code here
    private $id;
    private $title;
    private $text;
    private $sender;
    private $receiver;
    private $date;
    private $parentId;
    private $isReply;
    private $isRead;
    private $deleted;

    function __construct($id, $title, $text, $sender, $receiver, $date, $parentId, $isReply, $isRead, $deleted) {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->date = $date;
        $this->parentId = $parentId;
        $this->isRead = $isRead;
        $this->isReply = $isReply;
        
        $this->deleted = $deleted;
    }

    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getText() {
        return $this->text;
    }

    function getSender() {
        return $this->sender;
    }

    function getReceiver() {
        return $this->receiver;
    }

    function getDate() {
        return $this->date;
    }

    function getParentId() {
        return $this->parentId;
    }

    function getIsRead() {
        return $this->isRead;
    }

}
