jmc_swatters
============

##Steps to setup local environment on Mac:##

1.  enable Apache, PHP, MySql
2.  backup sanbox database and restore it in your local MySql; setup user to access it.
3.  clone git repository to /Users/your_user_name/Sites/. You don't have to figure out permissions if you just put files here.
4.  change the following files:
   /application/frontend/config.php: change base_url to local such as swatters.jmc.sixspokemedia.com; change /etc/hosts to point the domain to 127.0.0.1 as well.  
   /application/frontend/database.php: change database to local  
   /application/backend/config.php: change base_url to local  
   /application/backend/database.php: change database to local  
   /index.php: change timezone and enviornment:   
       date_default_timezone_set('America/Los_Angeles');  
       define('ENVIRONMENT', 'development');  
   /.htaccess: replace it with .htaccess.local  
5.  enable virutal host on your apache and add add the following:  
    <Directory "/Users/your_user_name/Sites/jmc_swatters/">  
        Options Indexes MultiViews  
        AllowOverride all  
        Order allow,deny  
        Allow from all  
    </Directory>  
    <VirtualHost *:80>  
        ServerAdmin webmaster@dummy-host.example.com  
        DocumentRoot "/Users/your_user_name/Sites/jmc_swatters/"  
        ServerName swatters.jmc.wangbrothers.net  
        ErrorLog "/private/var/log/apache2/swatters-error_log"  
        CustomLog "/private/var/log/apache2/swatters-access_log" common  
    </VirtualHost>  

