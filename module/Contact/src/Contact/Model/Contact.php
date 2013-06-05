<?php

namespace Contact\Model;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class Contact implements EventManagerAwareInterface {

    private $_db;
    protected $events;

    public function setEventManager(EventManagerInterface $events) {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;
        return $this;
    }

    public function getEventManager() {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    public function __construct($db) {
        $this->_db = $db;
    }

    public function getAllRows() {
        $sql = "select * from contact";
        $stat = $this->_db->query($sql);        
        return $stat->fetchAll();
    }

    public function addRow($data){
        $this->getEventManager()->trigger('event.insert', $this);
        
        $sql = "INSERT INTO
            contact (name,email,phone)
            VALUES ('{$data['name']}','{$data['email']}','{$data['phone']}')";
            return $this->_db->exec($sql);
    }
    
    public function getRow($id) {
        $sql = "select * from contact where id=?";

        $stat = $this->_db->prepare($sql);
        $stat->execute(array($id));
        return $stat->fetch();
    }

    public function updateRow($data, $id) {
        $this->getEventManager()->trigger('event.edit', $this);
        $sql = "UPDATE contact SET
            name='{$data['name']}',
            email='{$data['email']}',
            phone='{$data['phone']}'
            WHERE id={$id}
            ";
            
        return $this->_db->exec($sql);
    }
    
    public function delRow($id){
        $this->getEventManager()->trigger('event.delete', $this);
        $sql = "delete from contact where id={$id}";
        return $this->_db->exec($sql);
    }
}
