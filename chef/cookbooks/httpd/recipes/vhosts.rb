#create projects

node['httpd']['api'].each do |project|
	directory '/var/log/httpd/' + project do
		owner 'root'
		group 'root'
		mode  '0755'
		action :create
	end

    template node['httpd_home'] + 'sites-available/' + project + '.conf' do
        owner 'root'
        group 'root'
        mode  '0755'
        notifies :restart, 'service[httpd]'
        source 'api-vhost.conf.erb'
        variables({
             :project => project,
             :env => node.chef_environment
        })
    end

	link node['httpd_home'] + 'sites-enabled/' + project + '.conf' do
  		to node['httpd_home'] + 'sites-available/' + project + '.conf'
	end
end