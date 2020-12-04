#!/bin/bash
echo "127.0.0.1       localhost.localdomain localhost " `hostname` > /etc/hosts
echo "::1     localhost ip6-localhost ip6-loopback" >> /etc/hosts
echo "fe00::0 ip6-localnet" >> /etc/hosts
echo "ff00::0 ip6-mcastprefix" >> /etc/hosts
echo "ff02::1 ip6-allnodes" >> /etc/hosts
echo "ff02::2 ip6-allrouters" >> /etc/hosts
echo "172.17.0.2      cb6f603122ec" >> /etc/hosts
apt-get update -y
apt-get upgrade -y
service apache2 restart && service mysql restart && service sendmail restart && service postfix restart || echo "Somthing missing !!"

