#
# Cookbook Name:: httpd
# Recipe:: vhosts
#
# Copyright 2015, krzysztofbukowski.pl
#
# All rights reserved - Do Not Redistribute
#

package 'httpd' do
	action :install
	not_if 'ls /etc/init.d/httpd'
end

service 'httpd' do
	action [:enable]
end

#remove default files
['html', 'cgi-bin', 'error', 'icons'].each do |dir|
	directory node['httpd_docroot'] + dir do
		recursive true
		action :delete
	end
end

directory node['httpd_docroot'] do
    owner 'apache'
	group 'apache'
	mode  '2775'
    recursive true
end

['conf.d/welcome.conf', 'conf.d/README'].each do |file|
	file node['httpd_home'] + file do
		action :delete
	end
end

directory '/var/log/httpd' do
	owner 'root'
	group 'root'
	mode  '0755'
end

#create directories for vhosts
['sites-enabled','sites-available'].each do |dir|
	directory node['httpd_home'] + dir do
		owner 'root'
		group 'root'
		mode  '0744'
		action :create
	end
end

#apply configuration files

template node['httpd_home'] + 'conf/httpd.conf' do
	owner 'root'
	group 'root'
	mode  '0644'
	notifies :restart, 'service[httpd]'
end

include_recipe 'httpd::vhosts'

service 'httpd' do
	action [:start]
end
