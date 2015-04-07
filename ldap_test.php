<?php

// using ldap bind
$ldaprdn  = 'panand';     // ldap rdn or dn
$ldappass = 'Pari@2014';  // associated password

// connect to ldap server
$ldapconn = ldap_connect("ldap://svs01-dc01.corp.nextag.com",389)
    or die("Could not connect to LDAP server.");

if ($ldapconn) {
    print "===connected successfully=====";
    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

    // verify binding
    if ($ldapbind) {
        echo "LDAP bind successful...";
    } else {
        echo "LDAP bind failed...";
    }

}

?>
