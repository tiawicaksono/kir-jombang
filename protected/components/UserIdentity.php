<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $username = strtolower($this->username);
        $user = TblUser::model()->find('LOWER(username)=?', array($username));
//        echo $this->password;
        if ($user === NULL) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
//        } else if ($user->password !== md5($this->password)) {
        } else if (!hash_equals((string) $user->password, md5($this->password))) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id_user;
            $this->username = $user->username;
            Yii::app()->session->add('employee_name', $user->otoritas);
            Yii::app()->session->add('username', $user->username);
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}
