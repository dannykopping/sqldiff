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

    christer@aurora:~$ sudo pear install stz/SqlDiff-beta
    downloading SqlDiff-0.0.3.tgz ...
    Starting to download SqlDiff-0.0.3.tgz (14,020 bytes)
    .....done: 14,020 bytes
    install ok: channel://pear.starzinger.net/SqlDiff-0.0.3
    
Usage
-----
First you will have to generate the schemas you want to diff:

    christer@aurora:~$ mysqldump -X -d -u <user> -p <source> > source.xml
    christer@aurora:~$ mysqldump -X -d -u <user> -p <target> > target.xml
    
Then, to generate the statements needed to upgrade `&lt;target&gt;` to `&lt;source>&gt;`:    
     
    christer@aurora:~$ sqldiff source.xml target.xml 
    SqlDiff-0.0.3 by Christer Edvartsen.

    Run the following statements to add information to <target>:
    ================================================================================
    ALTER TABLE `user` ADD `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    ALTER TABLE `user` ADD `password` varchar(32) NOT NULL AFTER `name`;
    ALTER TABLE `user` CHANGE `email` `email` varchar(255);
    ================================================================================

Use the `--colors` option to enable colors in the output. Constructive statements are colored green, destructive red, and statements that change content are colored yellow.

![Screenshot](https://github.com/christeredvartsen/sqldiff/raw/master/screenshots/sqldiff-colors.png "Output when using the --colors option")

To include or exclude tables from the generated statements use the `--include` or `--exclude` options respectively. When `--include` is used only the tables specified will be included, and when `--exclude` is used all other tables than the ones listed will be included. Tables are specified as a comma separated list:

    christer@aurora:~$ sqldiff --include table1,table2 source.xml target.xml
    
or
 
    christer@aurora:~$ sqldiff --exclude table1,table2 source.xml target.xml    
