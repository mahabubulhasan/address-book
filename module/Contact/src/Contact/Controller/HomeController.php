<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractActionController {

    public function indexAction() {
        $contact = $this->getServiceLocator()->get('Contact\Model\Contact');

        $data['rows'] = $contact->getAllRows();
        return new ViewModel($data);
    }

    public function newAction() {

        $invalids = array();
        $filter = array(
            'name' => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'string_length',
                        'options' => array(
                            'min' => 3
                        ),
                    ),
                ),
            ),
            'email' => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'email_address',
                    ),
                ),
            ),
            'phone' => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                ),
            ),
        );

        if ($_POST) {
            $factory = new \Zend\InputFilter\Factory();
            $input = $factory->createInputFilter($filter);
            $input->setData($_POST);

            if ($input->isValid()) {
                $contact = $this->getServiceLocator()
                        ->get('Contact\Model\Contact');

                $contact->addRow($_POST);
                return $this->redirect()->toRoute('home');
            } else {
                $invalids = $input->getInvalidInput();
            }

            $data = $input->getValues();
        }

        return new ViewModel(array('row' => $data, 'invalids' => $invalids));
    }

    public function editAction() {
        $id = $this->params()->fromQuery('id', 0);

        $contact = $this->getServiceLocator()->get('Contact\Model\Contact');

        if ($_POST) {
            $contact->updateRow($_POST, $id);
            return $this->redirect()->toRoute('home');
        } else {
            $row = $contact->getRow($id);
        }

        return new ViewModel($row);
    }

    public function deleteAction() {
        $id = $this->params()->fromQuery('id', 0);

        $contact = $this->getServiceLocator()->get('Contact\Model\Contact');
        $contact->delRow($id);
        return $this->redirect()->toRoute('home');
    }

    public function fileUploadAction() {
        /*
        if($this->getRequest()->isPost()){
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setDestination('public/uploads');
            
            $files = $this->getRequest()->getFiles();
            
            if ($adapter->receive($files['image']['name'])) {
                return new ViewModel(array('msg'=>$files['image']['name'].' uploaded!'));
            }
        }
        */
        //-------------------------------------        
        if($this->getRequest()->isPost()){
            $size = new \Zend\Validator\File\Size(array('min' => '10kB', 'max' => '10MB'));
            $ext = new \Zend\Validator\File\Extension('pdf');
            
            $files = $this->getRequest()->getFiles();
                        
            $filter = new \Zend\Filter\File\Rename(array(
                'target'=> $files['doc']['name'],
                "randomize" => true,
            ));
            
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setValidators(array($size,$ext));   
            $adapter->setFilters(array($filter));
            
            $adapter->setDestination('public/uploads');

            if($adapter->isValid()){
                if ($adapter->receive($files['doc']['name'])) {
                    return new ViewModel(array('msg'=>$files['doc']['name'].' uploaded!'));
                }
                /*---
                foreach ($adapter->getFileInfo() as $info) {                    
                    if ($adapter->receive($info['name'])) {
                        echo $info['name'];                        
                    }
                }
                ---*/
            }else{                 
                return new ViewModel(array('errors'=>$adapter->getMessages()));
            }     
            
            
        }                  
    }

}
