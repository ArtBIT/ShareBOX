#!/bin/bash

ROOTDIR=/var/www
export DEBIAN_FRONTEND=noninteractive

get_config() {
   grep "$1" "$ROOTDIR/config.php" | sed -e 's/^.*, "//' -e 's/".*$//'
}

WEBSITE_DOMAIN=$(get_config "SHAREBOX_WEBSITE_HOSTNAME")

if [ ! -f /var/log/sharebox.packages.installed ]; 
then
    sudo apt-get update >> /vagrant/vm_build.log 2>&1
    sudo apt-get autoremove >> /vagrant/vm_build.log 2>&1 

    echo "Install base packages"
    apt-get -y install vim curl build-essential python-software-properties git >> /vagrant/vm_build.log 2>&1

    echo "Installing MySQL"
    # debconf-set-selections lets us set defaults in our Ubuntu config file (debconf).
    MYSQL_PASSWORD=$(get_config "VAGRANT_MYSQL_PASSWORD")
    debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_PASSWORD"
    debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_PASSWORD"
    apt-get -y install mysql-server >> /vagrant/vm_build.log 2>&1

    echo "Installing Apache"
    apt-get install -y apache2 >> /vagrant/vm_build.log 2>&1

    echo "Installing PHP"
    apt-get install -y php5 php5-common php5-dev php5-cli php5-fpm libapache2-mod-php5 >> /vagrant/vm_build.log 2>&1
            
    echo "Installing PHP extensions"
    apt-get install -y php5-curl php5-gd php5-mcrypt php5-mysql >> /vagrant/vm_build.log 2>&1

    echo "Setting up virtual host"
    #sudo cp "$ROOTDIR/.provision/000-default.conf" /etc/apache2/sites-available/
    sudo sed "s/sharebox.local/${WEBSITE_DOMAIN}/" "$ROOTDIR/.provision/000-default.conf" > /etc/apache2/sites-available/000-default.conf
    sudo a2ensite 000-default

    echo "Installing avahi-daemon to allow http://$WEBSITE_DOMAIN"
    sudo apt-get -y -q install build-essential avahi-daemon >> /vagrant/vm_build.log 2>&1
    ## Configure Avahi to enable .local hostnames used to connect between VMs.
    sudo sed -i 's/hosts:.*/hosts:          files mdns4_minimal [NOTFOUND=return] dns sharebox/g' /etc/nsswitch.conf

    # Enable rewrite module
    sudo a2enmod rewrite

    touch /var/log/sharebox.packages.installed
fi

SSL_DIR="$ROOTDIR/cert"
if [ ! -d "$SSL_DIR" ]
then
    sudo apt-get -y -q install libnss3-tools >> /vagrant/vm_build.log 2>&1
    mkdir -p "$SSL_DIR"
    cd "$SSL_DIR"
    export VER="v1.1.2"
    wget -O mkcert https://github.com/FiloSottile/mkcert/releases/download/${VER}/mkcert-${VER}-linux-amd64
    chmod +x mkcert
    sudo mv mkcert /usr/local/bin
    mkcert -install $WEBSITE_DOMAIN
    sudo a2enmod ssl
fi

# Restart Apache
sudo service apache2 restart

if [ ! -d "$ROOTDIR/vendor" ];
then
    cd "$ROOTDIR"
    echo "Installing composer dependencies"
    php composer.phar install >> /vagrant/vm_build.log 2>&1
fi

if [ ! -d "$ROOTDIR/public/assets/bower_components" ];
then
    if ! type "nodejs" 2>/dev/null; then
        echo "Installing NodeJS"
        curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
        sudo apt-get install -y nodejs git >> /vagrant/vm_build.log 2>&1
    fi
    cd "$ROOTDIR/public/assets"
    echo "Installing node modules"
    npm i >> /vagrant/vm_build.log 2>&1
fi

if [ -e "$ROOTDIR/public/index.html" ]; then
    rm "$ROOTDIR/public/index.html"
fi

# Create a database
if [ ! -f /var/log/sharebox.database.created ]; then
    echo "Creating database"
    SHAREBOX_PASSWORD=$(get_config "SHAREBOX_DATABASE_PASSWORD")
    mysql -uroot -p$MYSQL_PASSWORD -e "CREATE DATABASE sharebox;" >> /vagrant/vm_build.log 2>&1
    mysql -uroot -p$MYSQL_PASSWORD -e "GRANT ALL PRIVILEGES ON sharebox.* TO 'sharebox'@'localhost' IDENTIFIED BY '$SHAREBOX_PASSWORD';" >> /vagrant/vm_build.log 2>&1
    mysql -uroot -p$MYSQL_PASSWORD -e "FLUSH PRIVILEGES;" >> /vagrant/vm_build.log 2>&1
    mysql -uroot -p$MYSQL_PASSWORD < "$ROOTDIR/.provision/database.sql" >> /vagrant/vm_build.log 2>&1
    touch /var/log/sharebox.database.created
fi

if [ ! -f /var/log/sharebox.admin.created ]; then
    echo "Creating admin user"
    SHAREBOX_ADMIN_USERNAME=$(get_config "SHAREBOX_ADMIN_USERNAME")
    SHAREBOX_ADMIN_PASSWORD=$(get_config "SHAREBOX_ADMIN_PASSWORD")
    SHAREBOX_ADMIN_EMAIL=$(get_config "SHAREBOX_WEBMASTER_EMAIL")
    cd "$ROOTDIR/public"
    HTTP_HOST=$WEBSITE_DOMAIN php index.php Init admin_user "$SHAREBOX_ADMIN_USERNAME" "$SHAREBOX_ADMIN_PASSWORD" System Administrator "$SHAREBOX_ADMIN_EMAIL" >> /vagrant/vm_build.log 2>&1
    touch /var/log/sharebox.admin.created
fi


