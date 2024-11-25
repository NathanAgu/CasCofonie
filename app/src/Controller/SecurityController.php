<?php 

namespace Signature\Controller;

class SecurityController extends AbstractController
{
    public function login()
    {
        if ($user = $this->container->getCurrentUser()) {
            $this->redirect('/');
        }

        $error = null;
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])){
            $user = $this->container
                ->getUserRepository()
                ->identify(
                  $_POST['login'], 
                  $_POST['password']
                )
             ;


            if ($user != null) {
                $this->container->loginUser($user);
                $this->redirect('/');
            } else {
                $this->addMessage('failure','Mot de passe incorrect');
            }
        }
        

        $this->render(
            '404.html.twig',
            //'security/connection.html.twig',
            [
            ]
        );
    }

    public function logout() 
    {
        $this->container->logoutUser();
        $this->addMessage('success','Vous avez bien été deconnecté');
        $this->redirect('/');
    }

    public function register() 
    {       
        $errorList = [];
        $values = [];

        if ($user = $this->container->getCurrentUser()) {
            $this->redirect('/');
        }

        $error = null;
        /** @var UserRepository $userRepository */
        $userRepository = $this->container->getUserRepository();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])){

            $values = [];
            $errorList = $this->validateRegister($values);
            
            if (empty($errorList)) {
                $user = $this->container->getUserRepository()->add($values);
                $this->container->loginUser($user);
                $this->addMessage('success', "Compte créé avec succès !");
                $this->redirect('/');
            }
        }

        $this->render(
            '404.html.twig',
            //'security/register.html.twig',
            [
                'values' => $values,
                'errorList' => $errorList,
            ]
        );
    }

    public function validateRegister(array &$values)
    {
        $errorList = [];

        // $values['login'] = $_POST['login'];
        // if (!$this->container->getUserRepository()->doesLoginExist($_POST['login'])){
        //     if ($error = $this->validateField('login', "#^[a-z]{3}$#")) {
        //         $errorList['login'] = $error;
        //     }
        // } else {
        //     $errorList['login'] = 'Un compte existe déjà pour ce trigramme';
        // }
        // if ($_POST['password'] == $_POST['passwordConfirm']) {
        //     $values['password'] = $_POST['password'];
        //     if ($error = $this->validateField('password', '#^.{8,255}#')) {
        //         $errorList['password'] = $error;
        //     }
        // }
        // $values['name'] = $_POST['name'];
        // if ($error = $this->validateField('name', "#^[A-Za-z][A-Za-z-]{3,40}$#")) {
        //     $errorList['name'] = $error;
        // }
        // $values['firstName'] = $_POST['firstName'];
        // if ($error = $this->validateField('firstName', "#^[A-Za-z][A-Za-z-]{3,40}$#")) {
        //     $errorList['firstName'] = $error;
        // }
        // $values['poste'] = $_POST['poste'];
        // if ($error = $this->validateField('poste', "#^[A-Za-z].{0,40}$#")) {
        //     $errorList['poste'] = $error;
        // }
        // $values['entite'] = (int)$_POST['entite'];
        // if (!isset($_POST['entite'])) {
        //     $errorList['entite'] = 'Le champ entité est obligatoire';
        // }
        // $values['email'] = $_POST['email'];
        // if ($error = $this->validateField('email', '#^[A-Za-z\d@.-_]{6,255}#')) {
        //     $errorList['email'] = $error;
        // }
        // $values['numPro'] = $_POST['numPro'];
        // if ($error = $this->validateField('numPro', "#^[\d]{10}$#", false)) {
        //     $errorList['numPro'] = $error;
        // }

        return $errorList;
    }
} 