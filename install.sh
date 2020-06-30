#!/bin/bash

# Exit if any command fails
set -e

# Include useful functions
. "$(dirname "$0")/includes.sh"

# Check Docker is installed and running and launch the containers
echo -e "Running launch containers script"
. "$(dirname "$0")/launch-containers.sh"

# Set up WordPress Development site.
echo -e "Running install WordPress script"
. "$(dirname "$0")/install-wordpress.sh"

CURRENT_URL=$(docker-compose $DOCKER_COMPOSE_FILE_OPTIONS run -T --rm cli option get siteurl)

echo -e "\nWelcome to...\n"
echo -e "\033[95m$WORDPRESS\033[0m"

# Give the user more context to what they should do next: Run the environment and start testing!
echo -e "\nOpen $(action_format "$CURRENT_URL") to get started!"

echo -e "\n\nAccess the above install using the following credentials:"
echo -e "Default username: $(action_format "admin"), password: $(action_format "password")"
