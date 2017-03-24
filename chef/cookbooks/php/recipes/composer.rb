remote_file "#{Chef::Config[:file_cache_path]}/composer-install.php" do
    source 'https://getcomposer.org/installer'
    action :create
    not_if "test -f #{Chef::Config[:file_cache_path]}/composer-install.php"
end

bash 'install_composer' do
    cwd "#{Chef::Config[:file_cache_path]}"
    code <<-EOH
        php composer-install.php --install-dir=/usr/local/bin --filename=composer
    EOH
    not_if 'test -f /usr/local/bin/composer'
end
