<?php
/**
 * Functions for LDAP-Login by tubIT.
 *
 * Implementation by Clemens Zimmermann, tubIT.
 * Adaption to onIT code style by Per Broman.
 */

class Login {

    /**
     * The array with configurations.
     */
    var $configs = NULL;

    /**
     * Contructor loading the configuration.
     */
    function __construct ($configs)
    {
        $this->configs = $configs;
    }

    /**
     * Checks if there is a valid account for the user login.
     */
    public function authMe()
    {
        $account = "";
        $password = "";

        if ( isset($_POST['login']) && ( empty($_SESSION['login']) || !$_SESSION['login'] )) {
            if ( isset($_POST['account'] )) {
                $account = $_POST['account'];
            }
            if ( isset($_POST['password'] )) {
                $password = $_POST['password'];
            }
            if ( $this->isTubUser( $account, $password ) ) {
                $_SESSION['login'] = true;
            }
            else {
                $this->logMg( "no valid User: $account", 1);
            }
        }
    }

    /**
     * Checks via LDAP if the user has a valid account.
     *
     * @param $user
     * @param $password
     * @return bool
     */
    private function isTubUser( $user, $password )
    {
        $ldapconn = ldap_connect($this->configs['ldapserver']);

        if (!$ldapconn) {
            $this->logMg("Could not connect to LDAP server: " . $this->configs['ldapserver'], 4);
            return false;
        }
        $this->logMg( "LDAP conn successful...", 4);

        $ldapuser = "uid=" . $user . ',ou=user,dc=tu-berlin,dc=de';

        $ldapbind = ldap_bind($ldapconn, $ldapuser, $password);
        if (!$ldapbind) {
            $this->logMg("User not found in TUB-LDAP : Error trying to bind: " . ldap_error($ldapconn), 4);
            ldap_close($ldapconn);
            return false;
        }

        $this->logMg( "LDAP bind successful... - User found in TUB-LDAP ", 4);
        ldap_close($ldapconn);
        return true;
    }

    /**
     * Performs logout.
     *
     * @return bool
     */
    public function logoutWanted()
    {
        if ( isset($_POST['logout']) && $_SESSION['login'] ) {
            $this->destroySession();
            return true;
        }
        return false;
    }

    /**
     * Logging message.
     * LogLevels:
     * 0: kein Logging
     * 1: error
     * 2: warning
     * 3: info
     * 4: debug
     *
     * @param $mess
     * @param $level
     */
    private function logMg($mess, $level)
    {

        if ( $level > $this->configs['loglevel'] ) {
            return;
        }

        $date = date('r');
        $message = $date . ": " . $_SERVER['SCRIPT_NAME'] . " - " . $mess . "\n";
        file_put_contents($this->configs['logfile'], $message, FILE_APPEND | LOCK_EX);
        return;
    }

    /**
     * Deletes the uploaded and processed files and clears the session.
     */
    public function clearSession()
    {
        if (!empty($_SESSION['uploadFile'])) {
            unlink($_SESSION['uploadFile']);
        }
        if (!empty($_SESSION['processedFile'])) {
            unlink($_SESSION['processedFile']);
        }
        if (!empty($_SESSION['xmpFile'])) {
            unlink($_SESSION['xmpFile']);
        }
        unset($_SESSION['uploadFile']);
        unset($_SESSION['processedFile']);
        unset($_SESSION['xmpFile']);
    }

    /**
     * Destroys the session.
     */
    public function destroySession() {
        $this->clearSession();
        session_destroy();
    }

}
