# github trends api

This is a REST API application for the githubtrends.pl website. It's implemented using PHP 7.0.x and based on Zend Framework 3.x.


## Development

### Prerequisites

To run the API locally you'll need to install a few tools beforehand:

1. VirtualBox 5.0.x (https://www.virtualbox.org/wiki/Download_Old_Builds_5_0)
2. Vagrant 1.8.x (https://releases.hashicorp.com/vagrant/1.8.6/)

To check if everything's fine you can run:

    $ vagrant --version
    Vagrant 1.8.6

Additionally Vagrant requires some plugins to be installed:
1. vagrant hostsupdater plugin - automatically updates the local hosts file

Install the plugins by running in the project path:
    
    $ vagrant plugin install vagrant-hostsupdater

You can check if all plugins are installed correctly by running in the project path

    $ vagrant plugin list
    vagrant-hostsupdater (1.0.2)
    vagrant-omnibus (1.5.0)
    vagrant-share (1.1.5, system)

### Using Vagrant

After successful installation you should be able to run your development environment. Do it by executing in the project path
    
    $ vagrant up

If you run if for the first time it will take a bit longer as the VM needs to be provisioned.
It uses chef for that. All configuration files can be found in the `chef` directory.

Once it's finished provisioning the virtual box you will be able to access the local API via `http://api.dev.githubtrends.pl`. 
You should be able to see the api status page:
`{"status":"running","version":"1.0.0","uptime":1224}`

You can ssh into the VM by running `vagrant ssh`

#### Shared files

Your host machine shares the local files with the virtual machine so you can run the code on a production-like environment
while still using your favorite tools to edit the files. You can find the files inside the VM inside the `/var/www/githubtrends.pl/current` folder.

On Windows machines vagrant uses default VirtualBox shared folder.

On macOS and linux to speed up the VM Vagrant uses rsync.

**Note: For some reason rsync_auto doesn't work on macOS as expected and syncs the files only once during `vagrant up`. 
To workaround this problem you need to manually run `vagrant rsync-auto`**

### Installing dependencies


### Unit testing

#### Running

You can run unit tests inside the VM by running in the project path

    $ ./vendor/bin/phpunit

#### Code coverage

Code coverage is generated for the unit tests. You can browse the report by accessing `http://api.dev.githubtrends.pl/tests`

#### Apache logs

Apache logs can be found in the `/var/log/httpd/githubtrends.pl` folder.

#### Application logs

The application provides application level logs available in `/var/log/githubtrends.pl`.
Logging uses the (zend-log)[https://docs.zendframework.com/zend-log/] component. A logger instance is created
in `Module.php`. You can use `ServiceManager` to inject it into your service.
