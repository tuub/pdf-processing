<?php
/**
 * Functions for LDAP-Login by tubIT.
 *
 * Implementation by Clemens Zimmermann, tubIT.
 * Adaption to onIT code style by Per Broman.
 */

class SessionControl {

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
