# Stop all running containers
docker stop $(docker ps -a -q)

# Remove the ninja forms container
docker rm ninja-forms_wordpress_1

# Remove the ninja forms image
docker rmi ninja-forms_codeception