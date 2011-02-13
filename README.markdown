SqlDiff
=======
Installation
------------
SqlDiff should be installed using [PEAR](http://pear.php.net/).

The PEAR channel (`pear.starzinger.net`) that is used to distribute SqlDiff needs to be registered with the local PEAR environment.

    christer@aurora:~$ sudo pear channel-discover pear.starzinger.net
    Adding Channel "pear.starzinger.net" succeeded
    Discovery of channel "pear.starzinger.net" succeeded

This has to be done only once. Now, to install the package:

    christer@aurora:~$ sudo pear install stz/SqlDiff-alpha
    downloading SqlDiff-0.0.1.tgz ...
    Starting to download SqlDiff-0.0.1.tgz (11,394 bytes)
    .....done: 11,394 bytes
    install ok: channel://pear.starzinger.net/SqlDiff-0.0.1
    
Usage
-----
    christer@aurora:~$ mysqldump -X -d -u <user> -p <source> > source.xml
    Enter password: 
    christer@aurora:~$ mysqldump -X -d -u <user> -p <target> > target.xml
    Enter password: 
    christer@aurora:~$ sqldiff source.xml target.xml 
    SqlDiff-0.0.1 by Christer Edvartsen.

    Run the following queries to add information to <target>:
    ================================================================================
    <list of SQL statements>
    ================================================================================
    