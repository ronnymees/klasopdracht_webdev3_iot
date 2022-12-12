# This is the base image where we start from.
# This image contains the nginx webserver (alternative to apache).
# Alpine is a minimalistic linux distro.
# You can more info about this image @ https://hub.docker.com/_/nginx
FROM nginx:1.23.2-alpine

# Copy all files in the root of this server directory.
# Files in .dockerignore are not copied
# Do note that nginx stores it files in another location compare to apache
COPY . /usr/share/nginx/html

# Inform docker that the container is listening on port 80.
# This is merely a hint that a certain port is useful.
# Docker won’t do anything with that information by itself.
EXPOSE 80

# The last command in the container to run (this launches the web server)
CMD ["nginx", "-g", "daemon off;"]