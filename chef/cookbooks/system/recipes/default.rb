#
# Cookbook Name:: system
# Recipe:: default
#
# Copyright 2015, Krzysztof Bukowski
#
# All rights reserved - Do Not Redistribute
#

['vim', 'nc', 'wget', 'epel-release'].each do |pkg|
    package pkg
end

bash "install_ius-repo" do
    code <<-EOH
        rpm -ivh https://centos6.iuscommunity.org/ius-release.rpm
    EOH
    not_if "rpm -qa | grep 'ius-release'"
end
