SqlDiff
=======
Installation
------------
Installation via PEAR will be available shortly. Unitil then you will have to fetch the code from github:

    christer@aurora:~$ git clone https://github.com/christeredvartsen/sqldiff.git
    Initialized empty Git repository in /home/christer/sqldiff/.git/
    remote: Counting objects: 47, done.
    remote: Compressing objects: 100% (45/45), done.
    remote: Total 47 (delta 23), reused 0 (delta 0)
    Unpacking objects: 100% (47/47), done.
    
Usage
-----
    christer@aurora:~/sqldiff$ mysqldump -X -d -u <user> -p <source> > source.xml
    Enter password: 
    christer@aurora:~/sqldiff$ mysqldump -X -d -u <user> -p <target> > target.xml
    Enter password: 
    christer@aurora:~/sqldiff$ ./sqldiff.php source.xml target.xml 
    SqlDiff-dev by Christer Edvartsen.

    Run the following queries to add information to <target>:
    ================================================================================
    <list of SQL statements>
    ================================================================================
    