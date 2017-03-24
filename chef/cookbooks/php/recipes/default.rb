#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2015, Krzysztof Bukowski
#
# All rights reserved - Do Not Redistribute
#
node['php']['packages'].each do |pkg|
   package pkg
end

template '/etc/php.ini' do
	owner 'root'
	group 'root'
	mode  '0644'
	notifies :reload, 'service[httpd]'
end

if node.chef_environment == 'development' then
    remote_file '/usr/local/bin/phpunit' do
        source 'https://phar.phpunit.de/phpunit.phar'
        action :create
        not_if 'test -f /usr/local/bin/phpunit'
        mode '0755'
    end
end

include_recipe 'php::composer'
