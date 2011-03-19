Poster
======

Debugging web services applications and discovering what the POST and PUT requests look like is sometimes difficult. Poster gives you a single URL that you can point web services at. POST, PUT, DELETE, and HEAD requsts will write the request details (including http headers) to a log. GETs to the url (like from your browser) will display the log. 

The log rolls over every 24 hours. Any items older than 24 hours will be deleted.

Installation
------------

Copy the poster directory to your web server. Make sure the directory is writable by the web server, as log files will be writen to it.
