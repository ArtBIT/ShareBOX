# -*- mode: ruby -*-
# vi: set ft=ruby :

# The most common configuration options are documented and commented below.
# For a complete reference, please see the online documentation at
# https://docs.vagrantup.com.
Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "sharebox.local"
  config.vm.network "forwarded_port", guest: 80, host: 1337, auto_correct: true
  config.vm.network :private_network, ip: "172.16.33.33"
  config.vm.synced_folder "./", "/var/www", create: true, group: "www-data", owner: "vagrant", mount_options: ["dmode=775,fmode=664"]
  config.vm.provision "fix-no-tty", type: "shell" do |s|
    s.privileged = false
    s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
  end
  config.vm.provision "shell", path: ".provision/bootstrap.sh"
end
