<?php

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;

class User extends Model 
{
    
    const SESSION = 'User';
    
    public static function login($login, $senha) 
    {
        
        $sql = new Sql();
        
        $result = $sql->select('SELECT * FROM tb_users WHERE deslogin = :LOGIN', [
            ':LOGIN'=>$login
        ]);
        
        if (count($result) === 0) {
            
            throw new \Exception("Usuario inexistente ou senha invalida.");
        }
        
        $data = $result[0];
        
        if (password_verify($senha, $data['despassword']) === true) {
            
            $user = new user();
            
            $user->setData($data);
            
            $_SESSION[User::SESSION] = $user->getValues();
            
            return $user;
            
        } else {
        
            throw new \Exception("Usuario inexistente ou senha invalida.");
        }
        
    }
    
    public static function loginVerify($inadmin = true)
    {
        
        if (
                !isset($_SESSION[User::SESSION])
                ||
                !$_SESSION[User::SESSION]
                ||
                !(int)$_SESSION[User::SESSION]["iduser"] > 0
                ||
                (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
            ){
            
                header("Location: /admin/login");
                exit;
        }
        
    }
    
    public static function logOut()
    {
        
        session_unset($_SESSION[User::SESSION]);
        
        User::loginVerify();
        
    }
    
}
