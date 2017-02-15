<?php
/**
 * Functions for LDAP-Login by tubIT.
 *
 * Implementation by Clemens Zimmermann, tubIT.
 * Adaption to onIT code style by Per Broman.
 */

class Login {

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
                $this->logMg( "User $account ist authenticated", 5);
            }
            else {
                $this->logMg( "no valid User: $account", 5);
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
        $ldapserver = "ldaps://ldap.tu-berlin.de";
        $ldapconn = ldap_connect($ldapserver);

        if (!$ldapconn) {
            $this->logMg("Could not connect to LDAP server: $ldapserver", 5);
            return false;
        }
        $this->logMg( "LDAP conn successful...", 5);

        $ldapuser = "uid=" . $user . ',ou=user,dc=tu-berlin,dc=de';

        $ldapbind = ldap_bind($ldapconn, $ldapuser, $password);
        if (!$ldapbind) {
            $this->logMg("User not found in TUB-LDAP : Error trying to bind: " . ldap_error($ldapconn), 5);
            ldap_close($ldapconn);
            return false;
        }
        $this->logMg( "LDAP bind successful... - User found in TUB-LDAP ", 5);
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
     *
     * @param $mess
     * @param $level
     */
    private function logMg($mess, $level)
    {
        $logfile = "/var/log/pdfapilot.log";
        $loglevel = 1;

        if ( $level < $loglevel ) {
            return;
        }

        $date = date('r');
        $message=$date.": ". $_SERVER['SCRIPT_NAME']." - ".$mess."\n";
        file_put_contents($logfile, $message, FILE_APPEND | LOCK_EX);
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
