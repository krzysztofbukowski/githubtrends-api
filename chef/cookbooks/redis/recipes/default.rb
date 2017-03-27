#
# Cookbook Name:: redis
# Recipe:: default
#
# Copyright 2015, Krzysztof Bukowski
#
# All rights reserved - Do Not Redistribute
#

package 'redis'

service 'redis' do
	action [:enable, :start]
end
