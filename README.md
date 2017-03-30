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

## Logs

#### Apache logs

The application is run by the apache webserve. Apache logs can be found in the `/var/log/httpd/githubtrends.pl` folder.

#### Application logs

The application provides application level logs available in `/var/log/githubtrends.pl`.
Logging uses the (zend-log)[https://docs.zendframework.com/zend-log/] component. A logger instance is created
in `Module.php`. You can use `ServiceManager` to inject it into your service.


## API

Currently the API provides the following endpoints:

1. `http://api.dev.githubtrends.pl/` - get information about the API

Sample request

    curl -X GET -H "Cache-Control: no-cache" "http://api.dev.githubtrends.pl/"

Response code
    
    200 OK 

Sample response

    {
      "uptime": 92,
      "version": "1.0.0"
    }
    
2. `http://api.dev.githubtrends.pl/repo/{id}`

    - *id* string Repository name, i.e `angular/angular`

Sample request

    curl -X GET -H "Cache-Control: no-cache" "http://api.dev.githubtrends.pl/repo/angular/angular"
 
Sample response 

Response code
    
    200 OK - repository found
    404 Not found - repository not found
 
3. `http://api.dev.githubtrends.pl/compare/{id}` - get information about two repositories

    - *id* string Comma separated repository names, i.e `angular/angular,facebook/react`
     
Sample request
     
     curl -X GET -H "Cache-Control: no-cache" "http://api.dev.githubtrends.pl/compare/angular/angular%2Cfacebook/react"
     
Sample response

    [
      {
        "forks": 5725,
        "watchers": 22472,
        "stars": 22472,
        "full_name": "angular/angular",
        "latest_release": null,
        "open_pull_requests": 125,
        "closed_pull_requests": 5747,
        "last_merged_pull_request": "2017-03-29T17:27:48Z"
      },
      {
        "forks": 11670,
        "watchers": 63131,
        "stars": 63131,
        "full_name": "facebook/react",
        "latest_release": "2017-01-06T19:52:54Z",
        "open_pull_requests": 128,
        "closed_pull_requests": 4819,
        "last_merged_pull_request": "2017-03-29T12:55:04Z"
      }
    ]
    
If details for a repository are not found `null` will be returned
