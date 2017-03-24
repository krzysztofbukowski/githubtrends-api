node.default['httpd_docroot'] = '/var/www/'
node.default['httpd_home'] = '/etc/httpd/'

node.default['httpd']['server-admin'] = 'admin@krzysztofbukowski.pl'
node.default['httpd']['server-name'] = 'githubtrends'

#projects properties

node.default['httpd']['api'] = [
    'githubtrends.pl'
]
