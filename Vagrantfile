# -*- mode: ruby -*-
# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
    config.vm.box = "krzysztof/centos67-chef"
    config.vm.box_check_update = false
    config.vm.provider :virtualbox do |vb|
        vb.customize ["modifyvm", :id, "--groups", "/aws"]
        vb.customize ["modifyvm", :id, "--memory", 2048]
        vb.customize ["modifyvm", :id, "--cpus", 2]
    end

    ################# DEV ##################

	config.vm.define "dev-web" do |dev_config|
		dev_config.vm.hostname = "vm-api-dev"
		dev_config.vm.network "private_network", ip: "192.168.120.100"
        dev_config.vm.synced_folder ".", "/var/www/githubtrends.pl/current", type: "rsync",
            rsync__exclude: [".git/", ".vagrant", ".idea", ".gitignore"]

		dev_config.hostsupdater.aliases = []
		['githubtrends.pl'].each do |project|
			dev_config.hostsupdater.aliases += [
                "api.dev." + project
            ]
		end

		dev_config.vm.provider :virtualbox do |vb|
			vb.name = "githubtrends-dev-web"
		end

		dev_config.vm.provision "chef_solo" do |dev_chef|
		    dev_chef.cookbooks_path = "chef/cookbooks"
		
			dev_chef.environments_path = "chef/environments"
			dev_chef.environment = "development"

            dev_chef.data_bags_path = "chef/data_bags"

			dev_chef.roles_path = "chef/roles"
			dev_chef.add_role "webserver"

            dev_chef.json = {
                "user" => [
                    "#{ENV["USER"]}"
                ],
                "httpd" => {
                    "vhost" => {
                        "allow_from_ip" => "192.168.120.1"
                    }
                },
                "groups" => {

                },
            }
		end
	end
end
