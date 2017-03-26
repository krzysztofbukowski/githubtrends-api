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

include_recipe 'php::composer'