<?php
function logoutHandler()
{
    session_destroy();
    echo successMsg("Erfolgreich ausgeloggt");
}
